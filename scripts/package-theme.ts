import { createWriteStream, existsSync } from 'node:fs'
import { readFile, readdir, stat } from 'node:fs/promises'
import { deflateRawSync } from 'node:zlib'
import { join, relative, resolve } from 'node:path'

const repoRoot = resolve(import.meta.dirname, '..')
const themeDir = resolve(repoRoot, 'spectre-theme')
const manifestPath = resolve(themeDir, 'dist/.vite/manifest.json')
const outputPath = resolve(repoRoot, 'spectre-theme.zip')

if (!existsSync(manifestPath)) {
  throw new Error(
    `Missing ${relative(repoRoot, manifestPath)} -- run "npm run build" before packaging the theme`
  )
}

const requiredFiles = ['LICENSE.txt', 'NOTICE.txt', 'style.css', 'readme.txt']
for (const file of requiredFiles) {
  if (!existsSync(resolve(themeDir, file))) {
    throw new Error(`Missing required theme package file: spectre-theme/${file}`)
  }
}

async function collectFiles(dir: string): Promise<string[]> {
  const entries = await readdir(dir, { withFileTypes: true })
  const files: string[] = []
  for (const entry of entries) {
    if (entry.name === '.DS_Store' || entry.name === 'Thumbs.db') continue
    const full = join(dir, entry.name)
    if (entry.isDirectory()) {
      files.push(...(await collectFiles(full)))
    } else if (entry.isFile()) {
      files.push(full)
    }
  }
  return files
}

function dosDateTime(date: Date): { time: number; date: number } {
  const time =
    (date.getHours() << 11) | (date.getMinutes() << 5) | Math.floor(date.getSeconds() / 2)
  const dosYear = date.getFullYear() - 1980
  const dosDate = (dosYear << 9) | ((date.getMonth() + 1) << 5) | date.getDate()
  return { time, date: dosDate }
}

const crcTable = (() => {
  const table = new Uint32Array(256)
  for (let n = 0; n < 256; n++) {
    let c = n
    for (let k = 0; k < 8; k++) {
      c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1
    }
    table[n] = c >>> 0
  }
  return table
})()

function crc32(buf: Buffer): number {
  let crc = 0xffffffff
  for (const byte of buf) {
    crc = crcTable[(crc ^ byte) & 0xff] ^ (crc >>> 8)
  }
  return (crc ^ 0xffffffff) >>> 0
}

interface ZipEntry {
  name: string
  data: Buffer
  crc: number
  compressed: Buffer
  offset: number
  time: number
  date: number
}

async function buildZip(files: string[]): Promise<Buffer> {
  const entries: ZipEntry[] = []
  const chunks: Buffer[] = []
  let offset = 0
  const { time, date } = dosDateTime(new Date())

  for (const file of files) {
    const relPath = relative(repoRoot, file).split('\\').join('/')
    const data = await readFile(file)
    const crc = crc32(data)
    const compressed = deflateRawSync(data)
    const useDeflate = compressed.length < data.length

    const nameBytes = Buffer.from(relPath, 'utf8')
    const localHeader = Buffer.alloc(30)
    localHeader.writeUInt32LE(0x04034b50, 0)
    localHeader.writeUInt16LE(20, 4)
    localHeader.writeUInt16LE(0, 6)
    localHeader.writeUInt16LE(useDeflate ? 8 : 0, 8)
    localHeader.writeUInt16LE(time, 10)
    localHeader.writeUInt16LE(date, 12)
    localHeader.writeUInt32LE(crc, 14)
    localHeader.writeUInt32LE(useDeflate ? compressed.length : data.length, 18)
    localHeader.writeUInt32LE(data.length, 22)
    localHeader.writeUInt16LE(nameBytes.length, 26)
    localHeader.writeUInt16LE(0, 28)

    const body = useDeflate ? compressed : data
    chunks.push(localHeader, nameBytes, body)
    entries.push({
      name: relPath,
      data,
      crc,
      compressed: body,
      offset,
      time,
      date,
    })
    offset += localHeader.length + nameBytes.length + body.length
  }

  const centralDirStart = offset
  for (const entry of entries) {
    const nameBytes = Buffer.from(entry.name, 'utf8')
    const central = Buffer.alloc(46)
    central.writeUInt32LE(0x02014b50, 0)
    central.writeUInt16LE(20, 4)
    central.writeUInt16LE(20, 6)
    central.writeUInt16LE(0, 8)
    central.writeUInt16LE(entry.compressed.length < entry.data.length ? 8 : 0, 10)
    central.writeUInt16LE(entry.time, 12)
    central.writeUInt16LE(entry.date, 14)
    central.writeUInt32LE(entry.crc, 16)
    central.writeUInt32LE(entry.compressed.length, 20)
    central.writeUInt32LE(entry.data.length, 24)
    central.writeUInt16LE(nameBytes.length, 28)
    central.writeUInt16LE(0, 30)
    central.writeUInt16LE(0, 32)
    central.writeUInt16LE(0, 34)
    central.writeUInt16LE(0, 36)
    central.writeUInt32LE((0o100644 << 16) >>> 0, 38)
    central.writeUInt32LE(entry.offset, 42)
    chunks.push(central, nameBytes)
    offset += central.length + nameBytes.length
  }
  const centralDirSize = offset - centralDirStart

  const eocd = Buffer.alloc(22)
  eocd.writeUInt32LE(0x06054b50, 0)
  eocd.writeUInt16LE(0, 4)
  eocd.writeUInt16LE(0, 6)
  eocd.writeUInt16LE(entries.length, 8)
  eocd.writeUInt16LE(entries.length, 10)
  eocd.writeUInt32LE(centralDirSize, 12)
  eocd.writeUInt32LE(centralDirStart, 16)
  eocd.writeUInt16LE(0, 20)
  chunks.push(eocd)

  return Buffer.concat(chunks)
}

const files = (await collectFiles(themeDir)).sort()
const zip = await buildZip(files)

await new Promise<void>((resolvePromise, reject) => {
  const stream = createWriteStream(outputPath)
  stream.on('error', reject)
  stream.on('finish', () => resolvePromise())
  stream.end(zip)
})

const { size } = await stat(outputPath)
console.log(
  `Packaged ${files.length} files from spectre-theme/ into ${relative(repoRoot, outputPath)} (${size} bytes)`
)
