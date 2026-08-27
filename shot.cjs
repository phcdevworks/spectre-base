const { chromium } = require('playwright')
;(async () => {
  const b = await chromium.launch()
  const p = await b.newPage({ viewport: { width: 1440, height: 1200 } })
  await p.goto(process.argv[2], { waitUntil: 'networkidle' })
  const el = await p.$(process.argv[4] || '.cr-press-releases')
  await (el || p).screenshot({ path: process.argv[3] })
  await b.close()
})()
