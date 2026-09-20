import { readFileSync } from 'node:fs'
import { resolve } from 'node:path'

const repoRoot = resolve(import.meta.dirname, '..')
const read = (path: string) => readFileSync(resolve(repoRoot, path), 'utf8')

const GPL_LICENSE_LINE = 'GNU General Public License v2 or later'
const GPL_LICENSE_URI = 'http://www.gnu.org/licenses/gpl-2.0.html'

const pkg = JSON.parse(read('package.json')) as { license?: unknown }
if (pkg.license !== 'MIT') {
  throw new Error(
    `package.json license is ${JSON.stringify(pkg.license)}; the spectre-base repository/framework must stay MIT`
  )
}

const rootLicense = read('LICENSE')
if (!/^MIT License\b/.test(rootLicense)) {
  throw new Error('Repository LICENSE must remain the MIT License text')
}

const styleCss = read('spectre-theme/style.css')
const styleLicense = styleCss.match(/^License:\s*(.+)\s*$/m)?.[1]
if (styleLicense !== GPL_LICENSE_LINE) {
  throw new Error(
    `spectre-theme/style.css License header is ${JSON.stringify(styleLicense ?? null)}; expected ${JSON.stringify(GPL_LICENSE_LINE)}`
  )
}
const styleLicenseUri = styleCss.match(/^License URI:\s*(\S+)\s*$/m)?.[1]
if (styleLicenseUri !== GPL_LICENSE_URI) {
  throw new Error(
    `spectre-theme/style.css License URI is ${JSON.stringify(styleLicenseUri ?? null)}; expected ${JSON.stringify(GPL_LICENSE_URI)}`
  )
}

const readmeTxt = read('spectre-theme/readme.txt')
const readmeLicense = readmeTxt.match(/^License:\s*(.+)\s*$/m)?.[1]
if (readmeLicense !== GPL_LICENSE_LINE) {
  throw new Error(
    `spectre-theme/readme.txt License field is ${JSON.stringify(readmeLicense ?? null)}; expected ${JSON.stringify(GPL_LICENSE_LINE)}`
  )
}
const readmeLicenseUri = readmeTxt.match(/^License URI:\s*(\S+)\s*$/m)?.[1]
if (readmeLicenseUri !== GPL_LICENSE_URI) {
  throw new Error(
    `spectre-theme/readme.txt License URI is ${JSON.stringify(readmeLicenseUri ?? null)}; expected ${JSON.stringify(GPL_LICENSE_URI)}`
  )
}

const themeLicense = read('spectre-theme/LICENSE.txt')
if (!/GNU GENERAL PUBLIC LICENSE/.test(themeLicense) || !/Version 2, June 1991/.test(themeLicense)) {
  throw new Error('spectre-theme/LICENSE.txt must contain the full GPLv2 license text')
}

const themeNotice = read('spectre-theme/NOTICE.txt')
for (const pkgName of [
  '@phcdevworks/spectre-tokens',
  '@phcdevworks/spectre-ui',
  '@phcdevworks/spectre-components',
]) {
  if (!themeNotice.includes(pkgName)) {
    throw new Error(`spectre-theme/NOTICE.txt must credit bundled MIT package ${pkgName}`)
  }
}
if (!/MIT License/.test(themeNotice)) {
  throw new Error('spectre-theme/NOTICE.txt must include the MIT License text for bundled components')
}

console.log('Theme license boundary: OK (repo MIT, spectre-theme package GPL-2.0-or-later)')
