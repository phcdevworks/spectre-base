# Changelog

All notable changes to this project will be documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the versioning reflects package releases.

## [Unreleased]

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

[unreleased]: https://github.com/phcdevworks/spectre-base/compare/v3.0.1...HEAD
[v3.0.1]: https://github.com/phcdevworks/spectre-base/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/phcdevworks/spectre-base/compare/v2.0.0...v3.0.0
[v2.0.0]: https://github.com/phcdevworks/spectre-base/compare/v1.1.0...v2.0.0
[v1.1.0]: https://github.com/phcdevworks/spectre-base/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/phcdevworks/spectre-base/compare/v0.0.1...v1.0.0
[v0.0.1]: https://github.com/phcdevworks/spectre-base/tree/v0.0.1
