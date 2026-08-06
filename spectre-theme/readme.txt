=== Spectre Base Theme ===
Contributors: phcdevworks
Tags: blog, custom-logo, custom-menu, featured-images, full-width-template, theme-options, translation-ready, block-styles, wide-blocks
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 8.2
Stable tag: 3.0.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress theme foundation for Spectre-based sites with Vite, TypeScript, and Tailwind CSS.

== Description ==

Spectre Base Theme is a high-performance theme foundation for WordPress-based sites. It combines a standard WordPress theme directory with a Vite build pipeline, a TypeScript client entrypoint, and Tailwind CSS so teams can build theme-owned assets with a modern frontend workflow and deliver them through WordPress.

This theme consumes design tokens and UI primitives from the Spectre ecosystem without redefining them locally, keeping the theme layer focused on WordPress integration. In development it serves assets from the Vite dev server with hot module replacement. In production it reads a hashed Vite manifest and enqueues the compiled bundles.

= Features =

* Vite-powered asset pipeline with hot module replacement in development
* TypeScript entrypoint for theme-owned client logic
* Tailwind CSS 4 integration via Spectre design tokens
* Gutenberg block editor support with theme.json derived from Spectre tokens
* Development and production asset switching via WordPress environment detection
* Full standard WordPress template hierarchy coverage
* Compatible with the Spectre Icons plugin

== Installation ==

1. Build production assets by running `npm run build` from the repository root.
2. Copy or symlink the `spectre-theme` directory into your WordPress installation under `wp-content/themes/`.
3. Activate the theme from the WordPress admin under Appearance > Themes.

== Frequently Asked Questions ==

= How do I run the development server? =

Run `npm install` then `npm run dev` from the repository root. Set `WP_ENV` to `development` or use `wp_get_environment_type()` returning `development` so the theme loads assets from the Vite dev server at `http://localhost:5173`.

= How do I compile production assets? =

Run `npm run build` from the repository root. Compiled assets land in `spectre-theme/dist`. The theme reads `spectre-theme/dist/.vite/manifest.json` to resolve the correct hashed filenames.

= How do I validate the asset contract after a build? =

Run `npm run check:assets`. This confirms the manifest exists and the expected entry files are present.

= Does this theme work with the block editor (Gutenberg)? =

Yes. The theme registers block editor styles and ships a `theme.json` with typography, colors, and spacing derived from Spectre design tokens.

= Does this theme work with the Spectre Icons plugin? =

Yes. Install the `spectre-icons` plugin from WordPress.org. Once active, you can use the `[spectre-icon]` shortcode in any template or content area.

== Screenshots ==

1. Default theme appearance showing the header, post grid, and footer on the front page.

== Changelog ==

= 1.0.0 =
* Initial release
* Vite build pipeline with dev/prod switching
* TypeScript and Tailwind CSS 4 integration
* Gutenberg block editor support via theme.json
* Full standard WordPress template hierarchy
* Spectre tokens and Spectre UI integration
* Spectre Icons plugin compatibility
* GitHub Actions CI pipeline

== Upgrade Notice ==

= 1.0.0 =
Initial release.
