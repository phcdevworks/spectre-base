import { readdir, readFile } from 'node:fs/promises'
import { extname, join } from 'node:path'

const extensions = new Set(['.ts', '.css', '.php', '.json'])
const rules: [string, RegExp][] = [
  ['hardcoded color', /#[\da-f]{3,8}\b|\b(?:rgba?|hsla?|oklch|oklab|color)\s*\(/i],
  ['hardcoded dimension', /\b\d*\.?\d+(?:px|rem|em)\b/i],
  ['local gradient', /\b(?:repeating-)?(?:linear|radial|conic)-gradient\s*\(/i],
  ['local token definition', /--sp-[\w-]+\s*:/],
  [
    'utility drift',
    /(?<![\w-])(?:text-white|prose|(?:rounded|shadow|tracking)(?:-[\w.[\]-]+)?|(?:text|p[xytrblse]?|m[xytrblse]?|gap|space-[xy]|max-w|min-w|w|h)-(?:\d[\w./-]*|\[[^\]]+\]))(?![\w-])/,
  ],
]

async function scan(directory: string): Promise<number> {
  let violations = 0
  for (const entry of await readdir(directory, { withFileTypes: true })) {
    if (entry.name === 'dist' || entry.name === 'node_modules') continue
    const path = join(directory, entry.name)
    if (entry.isDirectory()) {
      violations += await scan(path)
    } else if (entry.isFile() && extensions.has(extname(path))) {
      const source = (await readFile(path, 'utf8'))
        .replace(/\/\*[\s\S]*?\*\/|<!--[\s\S]*?-->|^\s*\/\/[^\n]*/gm, (match) =>
          match.replace(/[^\n]/g, ' ')
        )
        .replace(/var\(\s*--sp-[\w-]+\s*\)/g, '')
      for (const [index, line] of source.split('\n').entries()) {
        for (const [reason, pattern] of rules) {
          if (reason === 'utility drift' && extname(path) === '.json') continue
          if (pattern.test(line)) {
            console.error(`${path}:${index + 1}: ${reason}: ${line.trim()}`)
            violations++
          }
        }
      }
    }
  }
  return violations
}

const violations = (await scan('src')) + (await scan('spectre-theme'))
if (violations) {
  process.exitCode = 1
} else {
  console.log('Design-system drift: OK (maintained source only)')
}
