import { mkdirSync, writeFileSync, existsSync } from 'node:fs'
import { resolve, join } from 'node:path'

const arg = process.argv[2]

if (!arg) {
  console.error('Usage: npm run create:child -- <client-name>')
  console.error('Example: npm run create:child -- acme-corp')
  process.exit(1)
}

const slug = arg
  .toLowerCase()
  .replace(/[^a-z0-9]+/g, '-')
  .replace(/^-|-$/g, '')

const displayName = slug
  .split('-')
  .map(w => w.charAt(0).toUpperCase() + w.slice(1))
  .join(' ')

const phpPrefix = `spectre_child_${slug.replace(/-/g, '_')}`
const handlePrefix = `spectre-child-${slug}`
const textDomain = `spectre-child-${slug}`
const outDir = resolve(`spectre-child-${slug}`)

if (existsSync(outDir)) {
  console.error(`Error: directory already exists: spectre-child-${slug}/`)
  process.exit(1)
}

mkdirSync(outDir)

writeFileSync(
  join(outDir, 'style.css'),
  `/*
Theme Name: Spectre Child - ${displayName}
Theme URI:
Author:
Author URI:
Description: Child theme for ${displayName} built on Spectre Base Theme.
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Template: spectre-theme
Text Domain: ${textDomain}

Tags: blog, custom-logo, custom-menu, featured-images, full-width-template, theme-options, translation-ready, block-styles, wide-blocks
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.2
*/
`
)

writeFileSync(
  join(outDir, 'functions.php'),
  `<?php
/**
 * Theme functions for Spectre Child - ${displayName}.
 */

if (!defined('ABSPATH')) exit;

function ${phpPrefix}_enqueue_styles() {
    wp_enqueue_style(
        '${handlePrefix}-style',
        get_stylesheet_uri(),
        array('spectre-base-style')
    );
}
add_action('wp_enqueue_scripts', '${phpPrefix}_enqueue_styles');
`
)

writeFileSync(
  join(outDir, 'theme.json'),
  JSON.stringify(
    {
      $schema: 'https://schemas.wp.org/trunk/theme.json',
      version: 3,
      settings: {
        color: {
          palette: [
            {
              slug: 'sp-brand-500',
              color: 'var(--sp-color-brand-500)',
              name: 'Brand 500',
            },
          ],
        },
        typography: {
          fontFamilies: [],
          fontSizes: [],
        },
      },
      styles: {
        color: {
          background: 'var(--sp-surface-page)',
          text: 'var(--sp-text-on-page-default)',
        },
      },
    },
    null,
    2
  ) + '\n'
)

console.log(`Created: spectre-child-${slug}/`)
console.log(`  style.css    — theme header (fill in Theme URI, Author)`)
console.log(`  functions.php — enqueues parent + child styles`)
console.log(`  theme.json   — inherits parent tokens, ready for client overrides`)
console.log(``)
console.log(`Next: copy spectre-child-${slug}/ to wp-content/themes/ and activate in WordPress.`)
