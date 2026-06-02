import { chromium } from 'playwright'
import { resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

const BASE_URL = process.env.SPECTRE_E2E_BASE_URL ?? 'http://localhost:8888'
const __dirname = fileURLToPath(new URL('.', import.meta.url))
const OUTPUT = resolve(__dirname, '../spectre-theme/screenshot.png')

const browser = await chromium.launch()
const page = await browser.newPage({ viewport: { width: 1200, height: 900 } })

await page.goto(BASE_URL, { waitUntil: 'networkidle' })
await page.screenshot({ path: OUTPUT, clip: { x: 0, y: 0, width: 1200, height: 900 } })

await browser.close()
console.log(`Screenshot saved → ${OUTPUT}`)
