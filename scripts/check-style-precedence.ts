import assert from 'node:assert/strict'
import { execFileSync } from 'node:child_process'
import { readFile } from 'node:fs/promises'
import { chromium } from 'playwright'

const manifest = JSON.parse(await readFile('spectre-theme/dist/.vite/manifest.json', 'utf8'))
const css = await readFile(`spectre-theme/dist/${manifest['src/js/main.ts'].css[0]}`, 'utf8')
const wrapped = execFileSync(
  'php',
  [
    '-r',
    `
define('ABSPATH', __DIR__);
function add_action(...$args) {}
class WP_Styles {
    public $css = 'h1 { font-size: 60px; }';
    function query(...$args) { return true; }
    function get_data(...$args) { return array($this->css); }
    function add_data($handle, $key, $value) { $this->css = implode("\\n", $value); }
}
$styles = new WP_Styles();
function wp_styles() { global $styles; return $styles; }
require 'spectre-theme/functions.php';
spectre_base_layer_global_styles();
$once = $styles->css;
spectre_base_layer_global_styles();
if ($once !== $styles->css) { exit(1); }
echo $styles->css;
`,
  ],
  { encoding: 'utf8' }
)

const browser = await chromium.launch({ headless: true })
try {
  const page = await browser.newPage()
  const theme = `<style>${css}\n@layer components { .precedence-probe { font-size: 20px; } }</style>`
  const globalStyles = `<style>${wrapped}</style>`
  for (const [name, styles] of [
    ['theme first', theme + globalStyles],
    ['global styles first', globalStyles + theme],
  ]) {
    await page.setContent(
      `${styles}<h1 class="precedence-probe">Component heading</h1><h1>Default heading</h1>`
    )
    assert.equal(
      await page.locator('.precedence-probe').evaluate((el) => getComputedStyle(el).fontSize),
      '20px',
      name
    )
    assert.equal(
      await page
        .locator('h1:not(.precedence-probe)')
        .evaluate((el) => getComputedStyle(el).fontSize),
      '60px',
      name
    )
    console.log(`Heading precedence: OK (${name})`)
  }
} finally {
  await browser.close()
}
