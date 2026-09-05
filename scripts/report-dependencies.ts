import { appendFileSync, readFileSync, readdirSync } from 'node:fs'
import { spawnSync } from 'node:child_process'

function jsonCommand(command: string, args: string[], allowed = [0]): unknown {
  const result = spawnSync(command, args, { encoding: 'utf8', maxBuffer: 10 * 1024 * 1024 })
  if (result.error || result.status === null || !allowed.includes(result.status)) {
    throw new Error(`${command} failed: ${result.error?.message ?? result.stderr}`)
  }
  const parsed: unknown = JSON.parse(result.stdout)
  if (parsed && typeof parsed === 'object' && 'error' in parsed) {
    throw new Error(`${command} returned an error: ${JSON.stringify(parsed)}`)
  }
  return parsed
}

const outdated = jsonCommand('npm', ['outdated', '--json'], [0, 1])
const audit = jsonCommand('npm', ['audit', '--json'], [0, 1]) as {
  metadata: { vulnerabilities: Record<string, number> }
}
const actions = new Map<string, Set<string>>()
for (const file of readdirSync('.github/workflows').filter((name) => /\.ya?ml$/.test(name))) {
  const source = readFileSync(`.github/workflows/${file}`, 'utf8')
  for (const match of source.matchAll(
    /\buses:\s*["']?([\w.-]+\/[\w.-]+)(?:\/[\w./-]+)?@([\w.-]+)/g
  )) {
    const refs = actions.get(match[1]) ?? new Set<string>()
    refs.add(match[2])
    actions.set(match[1], refs)
  }
}
const releases = []
for (const [repository, refs] of actions) {
  const release = jsonCommand('gh', ['api', `repos/${repository}/releases/latest`]) as {
    tag_name: string
  }
  releases.push({
    action: repository,
    configured: [...refs],
    latestStableRelease: release.tag_name,
  })
}
const report = [
  '# Dependency maintenance report',
  '',
  'Informational only. Updates follow the repository’s direct-to-main validation process.',
  '',
  '## npm updates',
  '```json',
  JSON.stringify(outdated, null, 2),
  '```',
  '',
  '## Audit counts (including development tooling)',
  '```json',
  JSON.stringify(audit.metadata.vulnerabilities, null, 2),
  '```',
  '',
  '## Workflow action releases',
  'Major-version references float within that major; compare the release major before updating.',
  '```json',
  JSON.stringify(releases, null, 2),
  '```',
  '',
].join('\n')
console.log(report)
if (process.env.GITHUB_STEP_SUMMARY) appendFileSync(process.env.GITHUB_STEP_SUMMARY, report)
