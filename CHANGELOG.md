# Changelog

All notable changes to this project will be documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the versioning reflects package releases.

## [Unreleased]

## [v3.1.3] - 2026-09-05

**Release Title:** Reliable style precedence and validation

Contract change type: semantic change

### Fixed

- Established matching CSS layer order in the theme bundle and WordPress
  global styles so component heading sizes win in either stylesheet order,
  while default headings continue to inherit WordPress global styles.
- Made design-system drift fail validation, excluding generated output and
  binary files while allowing Spectre token references and component recipes.
- Made asset validation reject missing files, paths outside the build
  directory, non-file assets, and unresolved imported manifest entries.

### Added

- Added validator regression tests to the full check gate and browser-based
  computed heading precedence checks to the WordPress smoke workflow.

## [v3.1.2] - 2026-09-02

**Release Title:** WordPress smoke assertion compatibility

### Changed

- Relaxed content-flow smoke assertions to verify the required
  `wp-block-group` and alignment class tokens while allowing WordPress to add
  its own layout-support classes to rendered Group blocks.

## [v3.1.1] - 2026-09-02

**Release Title:** Release validation hygiene

### Changed

- Updated WordPress smoke-test response assertions to read captured HTML
  directly, preventing successful early matches from surfacing as pipeline
  failures when `grep -q` closes its input under `pipefail`.

### Removed

- Removed an obsolete one-off CommonJS screenshot helper that duplicated the
  supported TypeScript screenshot workflow, violated the repository's
  TypeScript-only tooling contract, and caused ESLint to fail in clean CI
  checkouts.

## [v3.1.0] - 2026-09-02

**Release Title:** Theme layout and cascade contracts

### Added

- Added `spectre_base_layer_global_styles()` in `functions.php`, hooked on
  `wp_enqueue_scripts` (priority 20) and `wp_footer` (priority 2), both with
  `accepted_args = 0`. It post-processes the already-enqueued `global-styles`
  inline stylesheet's queued CSS via the public `WP_Styles` data API and
  wraps it in `@layer wp-global-styles`, with a leading
  `@layer wp-global-styles, components, utilities;` order statement that
  slots the new layer immediately below `components`/`utilities` regardless
  of which stylesheet the browser parses first. Fixes "Heading size
  precedence": WordPress core's own `global-styles-inline-css` output for
  `theme.json`'s `styles.elements.h1`-`h6` compiles to plain, unlayered
  selectors, which the CSS cascade-layers spec always lets win over
  `spectre-ui`'s layered `@layer components`/`@layer utilities` size
  recipes — so an `<sp-text level="h1" size="*">` request was permanently
  overridden by the theme's default heading scale. WordPress core has no
  filter on `wp_get_global_stylesheet()`'s return value or on the printed
  inline style tag (verified against wordpress-develop trunk and a live
  WordPress 7.0 install), so this hooks the registration/print lifecycle
  instead of a nonexistent filter. Regression coverage added to
  `.github/workflows/wordpress-smoke.yml` ("Verify global-styles heading
  precedence layer wrap"), asserting the front page response contains both
  the order statement and the wrapping `@layer wp-global-styles {` block.
  Manually verified end-to-end against a live WordPress 7.0.2 instance
  running this theme (WP's WP 6.9+ "load block assets on demand" default
  routes classic-theme global styles through `wp_footer` and hoists them
  into `<head>`; confirmed the wrap survives that path) as well as directly
  against WordPress core's own `WP_Styles`/`_WP_Dependency` classes.

- Added a generic parent-theme content-flow contract for classic
  `the_content()` output (`page.php`, `front-page.php`, `single.php` now wrap
  it in `<div class="sp-prose sp-content-flow">`). New `.sp-content-flow`
  rules in `src/styles/main.css` give top-level blocks a token-backed prose
  measure by default, honor WordPress's own `alignwide`/`alignfull` classes
  for breakout, and space top-level block siblings by `--sp-space-16` --
  matching `theme.json`'s `styles.spacing.blockGap` -- since classic content
  has no block-theme `.is-layout-flow` wrapper to provide that automatically.
  Pairs with `spectre-ui@4.2.0`'s `.sp-prose` recipe for list/blockquote
  treatment. Extended `wordpress-smoke.yml` with a page carrying
  `alignwide`/`alignfull` group blocks to verify the wrapper renders and
  Core's own block markup survives untouched. Requested by a downstream
  child-theme integration on 2026-08-07; closes Phase 6.

### Changed

- Bumped the Spectre runtime contract ranges to
  `@phcdevworks/spectre-tokens` `^4.5.0`, `@phcdevworks/spectre-ui`
  `^4.3.0`, and `@phcdevworks/spectre-components` `^1.17.0`, per Phase 7
  (production layout dependency alignment). Rebuilt theme assets and
  confirmed `npm run check` passes clean.
- Updated `README.md` "Adding custom shell styles" to demonstrate
  `spectre-ui`'s generated `sp-*` layout utilities and the `inner-class`
  contract on Spectre web components as the preferred way to style child
  themes, ahead of hand-rolled descendant CSS. Added a matching hint to
  `scripts/create-child-theme.ts`'s generator output. Completes Phase 7.
- Removed the local `--sp-layout-container-max-width: 80rem` override from
  `src/styles/main.css`, now that `@phcdevworks/spectre-tokens@4.7.0` ships
  the additive `layout.container.maxWidthWide` token and
  `@phcdevworks/spectre-ui@5.0.0` ships the corresponding
  `.sp-container--max-width-wide` utility. The four multi-column archive
  templates that needed the wider measure (`archive.php`, `home.php`,
  `index.php`, `search.php`) now opt in per-instance via
  `<sp-container inner-class="sp-container--max-width-wide">` instead of a
  sitewide token redeclaration; `.sp-content-flow > .alignwide` in
  `src/styles/main.css` now reads
  `var(--sp-layout-container-max-width-wide)` directly. Resolves "Container
  — Remove Local Max-Width Token Override" and its duplicate
  "Container width scale" entry in `TODO.md`.
- Confirmed `spectre-ui@5.0.0` moved `.sp-section` into `@layer components`
  (so a consumer's `sp-py-*`/`sp-pt-*`/`sp-pb-*` utility now wins over the
  recipe's own padding by layer precedence) and added `sp-section`/`sp-stack`
  to the base-layer custom-element host `display: block` contract. This
  theme never carried a local `!important` or plain-`<section>` workaround
  for the prior defect, and has no `<sp-stack>` usage with a host-level
  `max-width`, so no template or CSS change was needed here beyond
  confirming the fix is present in `node_modules/@phcdevworks/spectre-ui/dist`.
  Resolves "Section — Spacing Utility Override Of Section Padding",
  "Section spacing and host display", and "`sp-stack` host display contract"
  in `TODO.md`.
- Confirmed `spectre-ui@5.0.0`'s `.sp-shadow-inset-sm`/`-md`/`-lg`/`-xl`/`-2xl`
  utilities are present and generated from the published
  `--sp-shadow-inset-*` tokens. This theme has no hand-composed `box-shadow`
  for inset shadows to replace; `theme.json`'s `shadow.presets` already
  reference outer-shadow tokens (`var(--sp-shadow-sm|md|lg)`) only. No
  adjacent inset-shadow banding exists in this theme's own templates, so
  there is no consumer-owned adjacency treatment to document here. Resolves
  "Inset shadow delivery" in `TODO.md`.
- Confirmed `spectre-tokens@4.7.0`'s `surface.inverse`/on-inverse role set is
  published and `spectre-ui@5.0.0`/`spectre-components@1.18.0` consume it
  (`.sp-text--on-inverse`/`-muted`, `.sp-link--on-inverse`,
  `.sp-badge--inverse`, `.sp-btn--inverse`, `.sp-surface--inverse`, and the
  matching `sp-text`/`sp-badge`/`sp-button` `variant` values). Audited this
  theme's own templates (`header.php`, `footer.php`, `front-page.php`, and
  every file under `template-parts/`) for hand-painted on-dark treatment
  (translucent badges/buttons, dark card body text/links, utility-bar or
  footer link color overrides) and found none present in the current
  source -- this theme carries no local on-dark styling to migrate. Resolves
  "On-dark/inverse surface role" in `TODO.md`.
- Confirmed the `component.card.padding.{sm,md,lg}` token scale
  (`spectre-tokens@4.7.0`), the corresponding `getCardClasses()` size
  support (`spectre-ui@5.0.0`), and `sp-card`'s `padded` prop accepting
  `'sm'`/`'md'`/`'lg'` (`spectre-components@1.18.0`) have all shipped --
  every `Requested by Downstream` entry for card padding is gone from both
  packages' `TODO.md`. Every `<sp-card>` in this theme (`front-page.php`,
  `404.php`, `comments.php`, `single.php`,
  `template-parts/content-card.php`, `template-parts/content-single.php`,
  and the widget wrapper in `functions.php`) uses the plain boolean
  `padded` attribute with no local padding override to simplify. The
  full-bleed media workaround referenced by this item lives in a separate
  downstream child theme, not this one, so evaluating it against the new size
  scale is out of scope here. Resolves "Card padding
  size scale" in `TODO.md`.

## [v3.0.1] - 2026-08-06

**Release Title:** Maintenance dependency alignment

This release spans no single ROADMAP phase; it is a maintenance release.

### Changed

- Updated the Spectre runtime contract ranges to
  `@phcdevworks/spectre-components` `^1.14.0`,
  `@phcdevworks/spectre-tokens` `^4.1.0`, and
  `@phcdevworks/spectre-ui` `^3.2.0`.
- Refreshed Vite, Playwright, ESLint, TypeScript ESLint, Globals, WordPress
  Playground, and related transitive development dependencies.
- Corrected the roadmap history for the 3.0.0 text-contract release and
  clarified repository commit-completion and human-authorship policy.

## [v3.0.0] - 2026-07-29

**Release Title:** Spectre text contracts

### Changed

- Replaced template headings, metadata, and supporting copy across the
  WordPress template hierarchy with the upstream `sp-text` component. This
  changes rendered markup and centralizes text hierarchy, sizing, and variants
  in the Spectre UI contract.
- Upgraded `@phcdevworks/spectre-tokens` from v3 to v4 and
  `@phcdevworks/spectre-ui` from v2 to v3, and raised
  `@phcdevworks/spectre-components` to `^1.11.0` for the `sp-text` primitive.
- Corrected block-editor stylesheet registration to use WordPress
  `editor-styles` support and `add_editor_style()`.
- Replaced the page template's presentation-oriented `sp-card` wrapper with a
  structural `div`.
- Standardized repository configuration, AI-agent governance, maintenance
  documentation, and upstream-update guidance.

## [v2.0.0] - 2026-07-24

**Release Title:** Spectre component migration

### Changed

- Reworked WordPress templates, header/footer, cards, comments, and sidebar
  markup to use Spectre web components (`sp-*`) and Spectre UI button classes
  instead of custom `spectre-*` chrome classes.
- Removed the local component-style layer from `src/styles/main.css` so
  styling is consumed from upstream Spectre packages, and updated `AGENTS.md`
  drift guidance to enforce that pattern.
- Added `@tailwindcss/vite` and wired the Tailwind CSS 4 Vite plugin into
  `vite.config.ts`, fixing `@theme`/`@tailwind` at-rules being minified
  unprocessed by lightningcss.

## [v1.1.0] - 2026-07-21

**Release Title:** Parent theme API and agency tooling

### Added

- Added `@phcdevworks/spectre-manifest` as a devDependency. `spectre.manifest.json`
  at the repo root declares this package's ecosystem role, layer, exports, and
  allowed dependency targets. `check:ecosystem` validates it in the check pipeline.
- Added the `spectre_base_*` PHP hook API for header, branding, footer,
  navigation, and sidebar extension points.
- Added `npm run create:child -- <client-name>` to scaffold token-driven child
  themes.
- Added local WordPress environment and Playwright screenshot tooling.
- Added theme translation loading, a `languages/` directory, and direct-access
  guards across PHP templates.
- Added version-sync validation and the unified `npm run check` release gate.

### Changed

- Bumped `@phcdevworks/spectre-tokens` to `^3.3.1`, `@phcdevworks/spectre-ui`
  to `^2.7.1`, and `@phcdevworks/spectre-components` to `^1.8.0` in
  `package.json`, closing dependency drift against the current published
  `project-design` packages.
- Added TypeScript 7 support alongside TypeScript 6: internal tooling
  (ESLint/typescript-eslint) runs against TypeScript 6 via an
  `npm:@typescript/typescript6` alias since `typescript-eslint` does not yet
  support TypeScript 7's programmatic API; TypeScript 7's native compiler is
  available via the `@typescript/native` devDependency alias and is now used
  by the `build`/`typecheck` scripts' `tsc` invocations.
- Renamed the package and theme from `spectre-wordpress-themes` to
  `spectre-base`, including the package name, PHP function prefix, text domain,
  asset handles, workspace, documentation, and theme metadata.
- Refactored the theme to consume Spectre tokens, UI recipes, and web
  components, and added token-backed `theme.json` editor settings.
- Added footer navigation, footer social-icon filtering, a sidebar widget
  area, block editor styles, and refreshed WordPress theme templates.
- Raised the minimum PHP version to 8.2 and aligned CI on Node.js 22/24 and
  PHP 8.2.
- Migrated repository scripts to TypeScript and standardized build, asset,
  lint, PHP, drift, version, and ecosystem checks.
- Updated Spectre runtime packages and the TypeScript, ESLint, Tailwind CSS,
  Vite, WordPress, Playwright, PostCSS, Prettier, and npm toolchains.
- Expanded release, security, contribution, roadmap, cross-repository, and
  AI-agent governance documentation.

### Removed

- Retired the proposed page-builder compatibility layer to keep the reusable
  base theme server-rendered and block-based.

## [v1.0.0] - 2026-04-26

**Release Title:** WordPress theme stabilization

### Added

- Added `ROADMAP.md` and `TODO.md` to define the theme delivery plan and
  implementation priorities.

### Changed

- Migrated ESLint from legacy configuration files to a TypeScript flat config
  using `typescript-eslint` and `globals`.
- Simplified the WordPress index template to use template parts and updated
  pagination, menu fallback, and production asset loading.
- Removed unused Vite starter files, the standalone Tailwind config, and
  production source maps.
- Updated Spectre package ranges and build/lint tooling.

## [v0.0.1] - 2026-04-24

**Release Title:** Initial theme foundation

### Added

- Added the initial Vite, TypeScript, and Tailwind CSS 4 WordPress theme
  foundation with development-server HMR and production manifest asset
  loading.
- Added the WordPress template hierarchy and reusable content template parts,
  including archive, search, error, page, post, comments, and search-form
  support.
- Added Spectre token and UI package consumption.
- Added ESLint, Prettier, EditorConfig, PHP linting, and repository formatting
  configuration.
- Added GitHub Actions build/lint validation, a production asset-contract
  check, and a WordPress installation smoke test.
- Added contributor, security, conduct, repository, and agent guidance.

### Changed

- Renamed the original Vite template to `spectre-shell-wordpress` and then
  `spectre-wordpress-themes`, updating package, repository, theme, and
  documentation metadata.
- Reworked theme enqueue logic around a single TypeScript entry and its emitted
  CSS, with safer Vite manifest validation.
- Removed the development-container configuration and refreshed Spectre,
  TypeScript, Tailwind CSS, Vite, lint, formatting, and transitive
  dependencies.

[unreleased]: https://github.com/phcdevworks/spectre-base/compare/v3.1.3...HEAD
[v3.1.3]: https://github.com/phcdevworks/spectre-base/compare/v3.1.2...v3.1.3
[v3.1.2]: https://github.com/phcdevworks/spectre-base/compare/v3.1.1...v3.1.2
[v3.1.1]: https://github.com/phcdevworks/spectre-base/compare/v3.1.0...v3.1.1
[v3.1.0]: https://github.com/phcdevworks/spectre-base/compare/v3.0.1...v3.1.0
[v3.0.1]: https://github.com/phcdevworks/spectre-base/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/phcdevworks/spectre-base/compare/v2.0.0...v3.0.0
[v2.0.0]: https://github.com/phcdevworks/spectre-base/compare/v1.1.0...v2.0.0
[v1.1.0]: https://github.com/phcdevworks/spectre-base/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/phcdevworks/spectre-base/compare/v0.0.1...v1.0.0
[v0.0.1]: https://github.com/phcdevworks/spectre-base/tree/v0.0.1
