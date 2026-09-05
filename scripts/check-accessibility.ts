import assert from 'node:assert/strict'
import { chromium } from 'playwright'

const baseUrl = process.env.SPECTRE_E2E_BASE_URL ?? 'http://127.0.0.1:8080/'
const browser = await chromium.launch({ headless: true })

try {
  const page = await browser.newPage()
  await page.goto(baseUrl, { waitUntil: 'networkidle' })

  const skipLink = page.getByRole('link', { name: 'Skip to main content' })
  const main = page.locator('main#spectre-main-content')

  await skipLink.waitFor()
  assert.equal(await main.count(), 1, 'Expected exactly one main content landmark')

  await page.keyboard.press('Tab')
  assert.equal(await skipLink.evaluate((element) => element === document.activeElement), true)
  const focusedBox = await skipLink.boundingBox()
  assert.ok(focusedBox && focusedBox.y >= 0, 'Skip link must be visible when focused')

  await page.keyboard.press('Enter')
  await page.waitForFunction(() => document.activeElement?.id === 'spectre-main-content')
  assert.equal(await main.evaluate((element) => element === document.activeElement), true)

  console.log('Skip link and main landmark: OK')
} finally {
  await browser.close()
}
