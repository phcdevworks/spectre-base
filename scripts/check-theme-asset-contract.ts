import { readFile, realpath, stat } from 'node:fs/promises'
import { isAbsolute, relative, resolve } from 'node:path'

interface ViteManifestEntry {
  css?: string[]
  file?: string
  assets?: string[]
  imports?: string[]
  dynamicImports?: string[]
}

const manifestPath = resolve('spectre-theme/dist/.vite/manifest.json')
const entryKey = 'src/js/main.ts'

const manifest = JSON.parse(await readFile(manifestPath, 'utf8')) as Record<
  string,
  ViteManifestEntry
>
if (!manifest || typeof manifest !== 'object' || Array.isArray(manifest)) {
  throw new Error('Expected a manifest object')
}
const mainEntry = manifest[entryKey]

if (!mainEntry || typeof mainEntry !== 'object') {
  throw new Error(`Missing manifest entry for ${entryKey}`)
}

if (typeof mainEntry.file !== 'string' || !mainEntry.file.endsWith('.js')) {
  throw new Error(`Expected ${entryKey} to emit one JavaScript file`)
}

if (!Array.isArray(mainEntry.css) || mainEntry.css.length !== 1) {
  throw new Error(
    `Expected ${entryKey} to emit exactly one CSS file, received ${Array.isArray(mainEntry.css) ? mainEntry.css.length : 0}`
  )
}

const [cssFile] = mainEntry.css

if (typeof cssFile !== 'string' || !cssFile.endsWith('.css')) {
  throw new Error(`Expected ${entryKey} CSS asset to end with .css`)
}

const distRoot = await realpath(resolve('spectre-theme/dist'))
for (const [key, entry] of Object.entries(manifest)) {
  if (!entry || typeof entry !== 'object' || typeof entry.file !== 'string') {
    throw new Error(`Invalid manifest entry: ${key}`)
  }
  const files = [entry.file]
  for (const field of ['css', 'assets'] as const) {
    const values = entry[field]
    if (values !== undefined && !Array.isArray(values)) {
      throw new Error(`Expected ${key}.${field} to be an array`)
    }
    files.push(...(values ?? []))
  }
  for (const file of files) {
    if (
      typeof file !== 'string' ||
      !file ||
      isAbsolute(file) ||
      file.includes('\\') ||
      file.split('/').includes('..')
    ) {
      throw new Error(`Invalid asset path in ${key}: ${file}`)
    }
    const assetPath = await realpath(resolve(distRoot, file))
    const inside = relative(distRoot, assetPath)
    if (inside === '..' || inside.startsWith('../') || isAbsolute(inside)) {
      throw new Error(`Asset escapes build directory: ${file}`)
    }
    if (!(await stat(assetPath)).isFile()) {
      throw new Error(`Asset is not a file: ${file}`)
    }
  }
  for (const field of ['imports', 'dynamicImports'] as const) {
    const imports = entry[field]
    if (imports !== undefined && !Array.isArray(imports)) {
      throw new Error(`Expected ${key}.${field} to be an array`)
    }
    for (const imported of imports ?? []) {
      if (typeof imported !== 'string' || !Object.hasOwn(manifest, imported)) {
        throw new Error(`Missing imported manifest entry: ${imported}`)
      }
    }
  }
}

console.log(
  JSON.stringify(
    {
      entry: entryKey,
      js: mainEntry.file,
      css: cssFile,
    },
    null,
    2
  )
)
