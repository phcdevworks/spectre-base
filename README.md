# @phcdevworks/spectre-wordpress-themes

[![CI](https://github.com/phcdevworks/spectre-wordpress-themes/actions/workflows/ci.yml/badge.svg)](https://github.com/phcdevworks/spectre-wordpress-themes/actions/workflows/ci.yml)
[![GitHub issues](https://img.shields.io/github/issues/phcdevworks/spectre-wordpress-themes)](https://github.com/phcdevworks/spectre-wordpress-themes/issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/phcdevworks/spectre-wordpress-themes)](https://github.com/phcdevworks/spectre-wordpress-themes/pulls)
[![License](https://img.shields.io/github/license/phcdevworks/spectre-wordpress-themes)](LICENSE)

`@phcdevworks/spectre-wordpress-themes` is the WordPress theme delivery layer
of the Spectre system for WordPress sites that consume Spectre tokens, Spectre
UI, and Spectre web components.

Maintained by PHCDevworks, it combines a standard WordPress theme directory
with a Vite build pipeline, a TypeScript client entrypoint, and Tailwind CSS 4.
It is for teams building custom WordPress themes that need modern frontend
assets without moving design-system ownership into PHP templates.

[Contributing](CONTRIBUTING.md) | [Changelog](CHANGELOG.md) |
[Security Policy](SECURITY.md)

## Key capabilities

- Ships a deployable WordPress theme directory in `spectre-theme/`
- Builds theme-owned CSS and JavaScript through Vite and TypeScript
- Switches between the Vite dev server in development and hashed assets in
  production
- Consumes Spectre tokens, Spectre UI, and Spectre web components without
  redefining their contracts
- Keeps PHP templates focused on WordPress structure and data delivery

## When to use this package

- You are building a WordPress site and need Spectre tokens, UI, and web
  components delivered through a theme
- You want a Vite + TypeScript build pipeline for theme-owned assets without
  owning the design system itself
- You need hot module replacement in development and hashed production assets
  without writing the plumbing yourself

## When not to use this package

- You want to define design tokens, color scales, typography, or component
  contracts — those belong in `@phcdevworks/spectre-tokens`,
  `@phcdevworks/spectre-ui`, and `@phcdevworks/spectre-components`
- You need a WordPress plugin — plugin logic belongs in a separate plugin
  repository such as `spectre-icons`
- You are building a non-WordPress frontend — this package is WordPress-specific

## Design system guardrail

**The CMS delivers; the design system defines.** This theme should never become
a second design system. WordPress owns template structure and data delivery;
Spectre packages own visual meaning, styling contracts, and components.

Use this rule when making changes:

- Use `@phcdevworks/spectre-tokens` for values through `var(--sp-*)`
- Use `@phcdevworks/spectre-ui` for shared styling contracts
- Use `@phcdevworks/spectre-components` for controls and form elements such as
  `<sp-button>` and `<sp-input>`
- Keep PHP templates structural, with semantic shell classes and WordPress functions
- Keep theme-specific styling in `src/styles/main.css`, and only with Spectre token variables

Do not add local hex colors, raw RGB/HSL/OKLCH values, gradients, pixel/rem/em
constants, or Tailwind presentation utilities for visual decisions in PHP
templates.

## Installation

**Prerequisites:** Node.js `^22.12.0 || >=24.0.0`, npm `11.14.1`, PHP 8.2, and
[ripgrep](https://github.com/BurntSushi/ripgrep#installation) (required for
`check:drift`).

```bash
npm install
```

## Quick start

1. Start the Vite dev server.

   ```bash
   npm run dev
   ```

2. Make the theme available to WordPress by symlinking or copying
   `spectre-theme/` into your local WordPress install.

   ```bash
   ln -s /path/to/spectre-wordpress-themes/spectre-theme /path/to/wordpress/wp-content/themes/spectre-theme
   ```

3. Set your WordPress environment to development mode so the theme loads assets
   from the Vite dev server instead of `spectre-theme/dist`.

   ```php
   define('WP_ENVIRONMENT_TYPE', 'development');
   ```

4. Build production assets when ready to deploy.

   ```bash
   npm run build
   npm run check:assets
   ```

## What this package owns

- The Vite build configuration for theme assets
- The TypeScript entrypoint in `src/js/main.ts`
- The CSS entrypoint in `src/styles/main.css`
- The WordPress theme files in `spectre-theme/`, including asset loading in
  `functions.php`
- The compiled output for theme-owned assets in `spectre-theme/dist`

The build is configured around one JavaScript entry and one CSS entry. In production, the theme resolves those compiled assets from the Vite manifest and serves one compiled JS bundle plus one compiled CSS bundle for the theme-owned frontend surface.

## What this package does not own

- Design token definitions. Those belong to `@phcdevworks/spectre-tokens`.
- Reusable UI component contracts and recipes. Those belong to
  `@phcdevworks/spectre-ui` and `@phcdevworks/spectre-components`.
- WordPress core installation, plugin management, or hosting concerns.
- Application state architecture beyond the theme entrypoint.

## Relationship to the rest of Spectre

- `@phcdevworks/spectre-tokens` provides the design tokens consumed by the
  frontend layer
- `@phcdevworks/spectre-ui` provides Spectre UI styles and primitives used by
  the theme layer
- `@phcdevworks/spectre-components` provides the web components used directly
  in WordPress templates

This repository is where those frontend foundations are delivered through a
WordPress theme. The theme should consume Spectre output, not redefine it.

By default, the theme stylesheet imports the Spectre UI default bundle, so a
fresh install starts from Spectre UI instead of a generic Tailwind-only
baseline.

## Styling rules

PHP templates may use semantic shell classes such as `spectre-site-container`,
`spectre-main`, `spectre-panel`, and `spectre-card`. Those classes live in
`src/styles/main.css` and must remain token-backed.

Avoid adding Tailwind presentation utilities directly to PHP templates for color, spacing, type, radius, shadow, or layout decisions. Examples to avoid include:

    text-white
    rounded-*
    shadow-*
    tracking-*
    p-* / px-* / py-*
    gap-* / space-y-*
    max-w-* / w-* / h-*

When a new reusable visual pattern is needed, prefer adding or updating it in
`@phcdevworks/spectre-ui` or `@phcdevworks/spectre-components`. The WordPress
theme may carry a small token-driven shell bridge, but it should not become the
permanent home for reusable component styling.

## Template hierarchy

The theme ships a complete WordPress template hierarchy. Each file handles a
specific route:

| Template file | WordPress route |
|---|---|
| `index.php` | Catch-all fallback for all unmatched routes |
| `home.php` | Blog posts index (when a static front page is set) |
| `front-page.php` | Static front page |
| `single.php` | Single post |
| `page.php` | Static page |
| `archive.php` | Date, category, tag, and author archives |
| `search.php` | Search results |
| `404.php` | Not found |
| `header.php` | Shared header — loaded via `get_header()` |
| `footer.php` | Shared footer — loaded via `get_footer()` |
| `sidebar.php` | Sidebar — loaded via `get_sidebar()` |
| `searchform.php` | Search form partial |
| `comments.php` | Comments section |

Template parts in `spectre-theme/template-parts/`:

| Part | Used by |
|---|---|
| `content-card.php` | `index.php`, `home.php`, `archive.php` |
| `content-single.php` | `single.php` |
| `content-page.php` | `page.php`, `front-page.php` |
| `content-none.php` | All list templates when no posts are found |

## Shell class reference

These CSS classes are defined in `src/styles/main.css` and are safe to use in
any PHP template. All values come from `var(--sp-*)` tokens.

| Class | Purpose |
|---|---|
| `spectre-site-container` | Centered, max-width container |
| `spectre-site-header` | Header shell with neutral background |
| `spectre-site-footer` | Footer shell with neutral background |
| `spectre-main` | Main content area with grid spacing |
| `spectre-main--spacious` | Variant with more block padding |
| `spectre-panel` | Bordered, padded surface (pages, 404) |
| `spectre-panel--roomy` | Panel with extra padding |
| `spectre-panel--centered` | Center-aligned panel |
| `spectre-panel--dashed` | Panel with dashed border (empty states) |
| `spectre-card` | Post card with hover shadow |
| `spectre-card__media` | Image area of a card |
| `spectre-card__body` | Content area of a card |
| `spectre-card__title` | Card headline |
| `spectre-card__excerpt` | Card excerpt text |
| `spectre-card__readmore` | Card "Read more" anchor |
| `spectre-post-grid` | Responsive auto-fit grid for cards |
| `spectre-section` | Content section with gap |
| `spectre-content` | Post body content area |
| `spectre-entry-meta` | Date and author metadata row |
| `spectre-eyebrow` | Small uppercase label |
| `spectre-title-lg` | Large heading |
| `spectre-title-xl` | Extra-large heading |
| `spectre-title-2xl` | 2× extra-large heading |
| `spectre-muted` | Muted text color |
| `spectre-button` | Base anchor-as-button shell |
| `spectre-button--primary` | Primary filled button variant |
| `spectre-widget` | Sidebar widget wrapper |
| `spectre-widget-title` | Sidebar widget heading |

## Using this as a starter

Fork or clone this repository to start a new Spectre-backed WordPress site.
Minimum changes for a new site:

1. Update `package.json` — set `name` and `version` for your project.
2. Update the theme header in `spectre-theme/style.css` — `Theme Name`,
   `Theme URI`, `Author`, `Author URI`, `Description`, and `Text Domain`.
3. Update `spectre-theme/readme.txt` to match.
4. Install dependencies: `npm install`.
5. Symlink the theme and set `WP_ENVIRONMENT_TYPE` in `wp-config.php` (see
   Quick start above).
6. Customize shell styles in `src/styles/main.css` using `var(--sp-*)` tokens
   only.
7. Register social icons via the `spectre_wordpress_themes_footer_social_icons`
   filter (see Extension points below).
8. Build for production: `npm run build && npm run check:assets`.

Do not rename PHP functions (`spectre_wordpress_themes_*`) without also updating
template calls and the text domain throughout. A project-wide search-and-replace
is the safest approach.

## Extension points

The theme exposes WordPress hooks for common customization needs.

### Footer social icons

Add site-specific social icon links without modifying the theme directly:

```php
// In your child theme's functions.php or a site plugin:
add_filter('spectre_wordpress_themes_footer_social_icons', function () {
    return [
        ['name' => 'github',   'size' => '20', 'url' => 'https://github.com/yourorg'],
        ['name' => 'linkedin', 'size' => '20', 'url' => 'https://linkedin.com/company/yourco'],
    ];
});
```

This requires the [spectre-icons](https://wordpress.org/plugins/spectre-icons/)
plugin to be active. If the plugin is not active the social row is not rendered
regardless of the filter output.

### Adding custom shell styles

Add site-specific structural styles in a child theme or an additional CSS file.
Always use `var(--sp-*)` tokens:

```css
/* child-theme/style.css or an additional import */
@layer components {
  .my-hero {
    background-color: var(--sp-surface-alternate);
    padding-block: var(--sp-space-64);
  }
}
```

## Deployment

**Development** — symlink `spectre-theme/` into WordPress and set
`WP_ENVIRONMENT_TYPE=development` (see Quick start). Assets stream from the
Vite dev server with HMR.

**Production** — run `npm run build`, then copy or ZIP the entire `spectre-theme/`
directory (including `dist/`) to `wp-content/themes/spectre-theme/` on the
server. The theme reads `dist/.vite/manifest.json` to enqueue hashed bundles.
Set `WP_ENVIRONMENT_TYPE` to anything other than `development` in `wp-config.php`.

```bash
# Build and verify before deploying
npm run check

# ZIP the deployable directory
zip -r spectre-theme.zip spectre-theme/
```

The `dist/` directory must be present in the ZIP. It is generated by the build
and is not committed to the repository.

## Repository structure

- `src/js/main.ts` — theme JavaScript entrypoint
- `src/styles/main.css` — theme CSS entrypoint
- `spectre-theme/` — deployable WordPress theme directory
- `spectre-theme/functions.php` — asset enqueueing, theme setup, env-aware loading
- `spectre-theme/dist/` — Vite build output (never edit directly)
- `vite.config.ts` — build and dev-server configuration

The deployable WordPress theme directory is `spectre-theme/`.
The package and repository name remain `spectre-wordpress-themes`.

## Development

CSS and JavaScript changes in `src/` are reflected instantly in the browser via
HMR without a page reload. The Vite dev server runs on
`http://localhost:5173` by default. To use a different port, define the server
URL in `wp-config.php`:

```php
define('VITE_DEV_SERVER', 'http://localhost:5174');
```

The production build writes hashed assets and `.vite/manifest.json` to
`spectre-theme/dist`, and the theme uses that manifest to enqueue the compiled
files.

## Validation and drift checks

Run the full validation suite before handing off changes:

```bash
npm run check
```

`npm run check` is an alias for `npm run validate`. Both run in order:
TypeScript check, Vite build, asset contract, ESLint, PHP lint, and drift scan.
CI runs the same command.

Step by step:

```bash
npm run build          # TypeScript check + Vite production build
npm run check:assets   # Validate Vite manifest and asset contract
npm run lint           # ESLint (TypeScript)
npm run lint:php       # PHP syntax validation
npm run check:drift    # Scan for hardcoded visual values (design-system drift)
```

`check:drift` expected output is empty or only token-backed references such as
`var(--sp-shadow-*)` and `theme.json` token presets. Any local visual value
must be removed or justified before merging.

## Spectre Icons integration

This theme is compatible with the
[spectre-icons](https://wordpress.org/plugins/spectre-icons/) WordPress plugin.
Once the plugin is installed and activated, the `[spectre-icon]` shortcode is
available in any template or content area.

The theme footer automatically renders icon links when the plugin is active:

```text
[spectre-icon name="github" size="20"]
[spectre-icon name="twitter" size="20"]
[spectre-icon name="linkedin" size="20"]
```

To check availability before rendering icons in a custom template:

```php
<?php if (spectre_wordpress_themes_has_icons()) : ?>
    <?php echo do_shortcode('[spectre-icon name="arrow-right" size="16"]'); ?>
<?php endif; ?>
```

This pattern works in both the classic editor and the block editor.

## Troubleshooting

**Theme loads a blank page in production**
The compiled manifest is missing or stale. Run `npm run build` then
`npm run check:assets` and confirm `spectre-theme/dist/.vite/manifest.json`
exists and lists `src/js/main.ts` as an entry.

**Styles are not updating in development**
Confirm `WP_ENVIRONMENT_TYPE` is set to `development` in `wp-config.php` and
the Vite dev server is running on the expected port (`npm run dev` defaults to
`http://localhost:5173`). Override with
`define('VITE_DEV_SERVER', 'http://localhost:5174');` if using a different port.

**`check:drift` reports unexpected matches**
Drift matches inside `theme.json` or `var(--sp-*)` references are acceptable.
Any raw hex, pixel, rem, or Tailwind presentation utility added directly to
`src/` or PHP templates must be replaced with a `var(--sp-*)` token or a
Spectre component.

**PHP lint fails with syntax error**
`npm run lint:php` runs `php -l` on every PHP file in `spectre-theme/`. Fix
the reported file, then rerun. PHP 8.2 is the CI target.

**`rg` not found when running `check:drift`**
Install [ripgrep](https://github.com/BurntSushi/ripgrep#installation)
(`brew install ripgrep` on macOS, `apt install ripgrep` on Ubuntu).

## Notes for implementers

- Keep PHP templates focused on structure, data access, and WordPress
  integration
- Keep theme styling and client behavior in `src/`
- Treat the theme as the owner of its compiled CSS and JS bundles, not the owner
  of Spectre token or component definitions
- Move reusable styling down into Spectre UI/components instead of expanding the
  WordPress shell

## AI and automation boundaries

Claude Code (`claude-sonnet-4-6`) is the primary development agent for this
repository. Codex handles releases and production stabilization. Jules handles
small automated fixes and dependency updates. GitHub Copilot provides
development support.

Claude Code, Codex, and Copilot do not create git commits by default. Jules may
commit only bounded automated maintenance when the `JULES.md` scope and
validation gates pass. Final commit, merge, release, tag, and publishing
authority remains with Bradley Potts.

**Protected from automated change:** the Golden Rule (CMS delivers, design
system defines — no local token overrides or hardcoded values), PHP template
structure, and the starter-theme boundary (no client-specific identities or
brand values in templates). See [AGENTS.md](AGENTS.md) for full agent
governance and boundary rules.

## License

MIT © PHCDevworks. See [LICENSE](LICENSE).
