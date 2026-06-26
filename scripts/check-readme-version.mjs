import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';

const repoRoot = resolve(import.meta.dirname, '..');

const pkg = JSON.parse(readFileSync(resolve(repoRoot, 'package.json'), 'utf-8'));
const readme = readFileSync(resolve(repoRoot, 'README.md'), 'utf-8');
const themeStyle = readFileSync(resolve(repoRoot, 'spectre-theme/style.css'), 'utf-8');
const themeReadme = readFileSync(resolve(repoRoot, 'spectre-theme/readme.txt'), 'utf-8');

const readmeMatch = readme.match(/\|\s*Current version\/status\s*\|\s*([^\s|]+)\s*\|/i);
const styleMatch = themeStyle.match(/^Version:\s*(\S+)/m);
const stableTagMatch = themeReadme.match(/^Stable tag:\s*(\S+)/m);

if (!readmeMatch) {
  throw new Error(
    'README.md is missing the "Current version/status" row in the Repository Snapshot table.',
  );
}
if (!styleMatch) {
  throw new Error('spectre-theme/style.css is missing a "Version:" header.');
}
if (!stableTagMatch) {
  throw new Error('spectre-theme/readme.txt is missing a "Stable tag:" field.');
}

const versions = {
  'package.json': pkg.version,
  'README.md "Current version/status"': readmeMatch[1],
  'spectre-theme/style.css "Version:"': styleMatch[1],
  'spectre-theme/readme.txt "Stable tag:"': stableTagMatch[1],
};

const mismatched = Object.entries(versions).filter(([, v]) => v !== pkg.version);

if (mismatched.length > 0) {
  const lines = Object.entries(versions).map(([source, v]) => `  ${source}: ${v}`);
  throw new Error(
    `Version Sync Checklist mismatch — all locations must match package.json ("${pkg.version}"):\n${lines.join('\n')}`,
  );
}

console.log(`Version sync parity: OK (${pkg.version})`);
