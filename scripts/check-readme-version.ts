import { readFileSync } from 'node:fs'
import { resolve } from 'node:path'

const repoRoot = resolve(import.meta.dirname, '..')
const pkgPath = process.env.README_VERSION_PKG_PATH ?? 'package.json'
const read = (path: string) => readFileSync(resolve(repoRoot, path), 'utf8')
const pkg = JSON.parse(read(pkgPath)) as { version?: unknown }
if (
  typeof pkg.version !== 'string' ||
  !/^\d+\.\d+\.\d+(?:-[\w.-]+)?(?:\+[\w.-]+)?$/.test(pkg.version)
) {
  throw new Error(`Invalid version in ${pkgPath}`)
}
const lock = JSON.parse(read('package-lock.json')) as {
  version?: unknown
  packages?: Record<string, { version?: unknown }>
}
const versions: [string, unknown][] = [
  [
    'README.md Current version/status',
    read('README.md').match(/\|\s*Current version\/status\s*\|\s*([^\s|]+)\s*\|/i)?.[1],
  ],
  [
    'spectre-theme/style.css Version',
    read('spectre-theme/style.css').match(/^Version:\s*(\S+)\s*$/m)?.[1],
  ],
  [
    'spectre-theme/readme.txt Stable tag',
    read('spectre-theme/readme.txt').match(/^Stable tag:\s*(\S+)\s*$/m)?.[1],
  ],
  ['package-lock.json version', lock.version],
  ['package-lock.json packages[""] version', lock.packages?.['']?.version],
]
for (const [label, version] of versions) {
  if (version !== pkg.version) {
    throw new Error(
      `${label} is ${JSON.stringify(version) ?? 'missing'}; expected ${pkg.version} from ${pkgPath}`
    )
  }
}
console.log(`Release version parity: OK (${pkg.version}, all ${versions.length} references)`)
