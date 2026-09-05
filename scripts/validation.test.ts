import assert from 'node:assert/strict'
import { spawnSync } from 'node:child_process'
import { mkdtempSync, mkdirSync, writeFileSync, rmSync, symlinkSync, readFileSync } from 'node:fs'
import { tmpdir } from 'node:os'
import { dirname, join, resolve } from 'node:path'
import { test } from 'node:test'

function fixture(run: (root: string, write: (path: string, value: string) => void) => void) {
  const root = mkdtempSync(join(tmpdir(), 'spectre-validation-'))
  try {
    run(root, (path, value) => {
      const target = join(root, path)
      mkdirSync(dirname(target), { recursive: true })
      writeFileSync(target, value)
    })
  } finally {
    rmSync(root, { recursive: true, force: true })
  }
}
function check(script: string, cwd: string) {
  return spawnSync(process.execPath, ['--experimental-strip-types', resolve('scripts', script)], {
    cwd,
    encoding: 'utf8',
  })
}

test('asset gate rejects missing, escaping, and non-file assets and missing imports', () => {
  fixture((root, write) => {
    const manifest = (file: string, css = 'css/main.css', extra = {}) =>
      write(
        'spectre-theme/dist/.vite/manifest.json',
        JSON.stringify({ 'src/js/main.ts': { file, css: [css], ...extra } })
      )
    write('spectre-theme/dist/js/main.js', '')
    write('spectre-theme/dist/css/main.css', '')
    manifest('js/main.js')
    assert.equal(check('check-theme-asset-contract.ts', root).status, 0)
    for (const file of [
      'js/missing.js',
      '../outside.js',
      '/outside.js',
      'js/directory.js',
      'js/link.js',
    ]) {
      mkdirSync(join(root, 'spectre-theme/dist/js/directory.js'), { recursive: true })
      write('outside.js', '')
      if (file === 'js/link.js')
        symlinkSync(join(root, 'outside.js'), join(root, 'spectre-theme/dist/js/link.js'))
      manifest(file)
      assert.notEqual(check('check-theme-asset-contract.ts', root).status, 0, file)
    }
    manifest('js/main.js', 'css/missing.css')
    assert.notEqual(check('check-theme-asset-contract.ts', root).status, 0)
    manifest('js/main.js', 'css/main.css', { imports: ['missing-entry'] })
    assert.notEqual(check('check-theme-asset-contract.ts', root).status, 0)
    manifest('js/main.js', 'css/main.css', { assets: ['assets/missing.woff2'] })
    assert.notEqual(check('check-theme-asset-contract.ts', root).status, 0)
  })
})

test('drift gate ignores generated output and recipes but rejects source violations', () => {
  fixture((root, write) => {
    write(
      'src/styles/main.css',
      '.shell { padding: var(--sp-space-4); box-shadow: var(--sp-shadow-sm); }'
    )
    write('spectre-theme/index.php', '<div class="sp-prose sp-btn">Content</div>')
    write('spectre-theme/dist/css/main.css', '.generated { color: #fff; padding: 10px; }')
    write('spectre-theme/screenshot.png', '#fff 10px')
    assert.equal(check('check-drift.ts', root).status, 0)
    for (const source of [
      '.shell { color: #fff; }',
      '.shell { padding: 1rem; }',
      '.shell { --sp-space-4: 0; }',
      '.shell { color: var(--sp-text-default, #fff); }',
      '<div class="p-4 rounded-lg">Content</div>',
    ]) {
      write('src/styles/main.css', source)
      const result = check('check-drift.ts', root)
      assert.equal(result.status, 1, source)
      assert.match(result.stderr, /src\/styles\/main.css:1:/)
    }
    rmSync(join(root, 'src'), { recursive: true })
    assert.notEqual(check('check-drift.ts', root).status, 0)
  })
})

test('release version gate rejects every mismatched or missing metadata reference', () => {
  fixture((root, write) => {
    write(
      'scripts/check-readme-version.ts',
      readFileSync('scripts/check-readme-version.ts', 'utf8')
    )
    const files: Record<string, string> = {
      'package.json': JSON.stringify({ type: 'module', version: '1.2.3' }),
      'package-lock.json': JSON.stringify({
        version: '1.2.3',
        packages: { '': { version: '1.2.3' } },
      }),
      'README.md': '| Current version/status | 1.2.3 |',
      'spectre-theme/style.css': 'Version: 1.2.3',
      'spectre-theme/readme.txt': 'Stable tag: 1.2.3',
    }
    const run = () => check(join(root, 'scripts/check-readme-version.ts'), root)
    for (const [path, value] of Object.entries(files)) write(path, value)
    assert.equal(run().status, 0)
    for (const [path, value] of Object.entries(files)) {
      write(path, value.replaceAll('1.2.3', '1.2.4'))
      assert.notEqual(run().status, 0, path)
      write(path, value)
      rmSync(join(root, path))
      assert.notEqual(run().status, 0, `missing ${path}`)
      write(path, value)
    }
    for (const lock of [
      { version: '1.2.4', packages: { '': { version: '1.2.3' } } },
      { version: '1.2.3', packages: { '': { version: '1.2.4' } } },
      { version: '1.2.3' },
    ]) {
      write('package-lock.json', JSON.stringify(lock))
      assert.notEqual(run().status, 0, JSON.stringify(lock))
    }
  })
})

test('public templates expose one main landmark and the header links to it', () => {
  const header = readFileSync('spectre-theme/header.php', 'utf8')
  assert.match(
    header,
    /<a class="spectre-skip-link [^"]+" href="#spectre-main-content">[\s\S]*Skip to main content/
  )

  for (const template of [
    '404.php',
    'archive.php',
    'front-page.php',
    'home.php',
    'index.php',
    'page.php',
    'search.php',
    'single.php',
  ]) {
    const source = readFileSync(`spectre-theme/${template}`, 'utf8')
    assert.equal(
      source.match(/<main id="spectre-main-content" tabindex="-1">/g)?.length,
      1,
      template
    )
    assert.equal(source.match(/<\/main>/g)?.length, 1, template)
  }
})
