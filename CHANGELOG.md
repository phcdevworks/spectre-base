# Changelog

All notable changes to this project will be documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the versioning reflects package releases.

## [Unreleased]

### Changed

- Bumped `@phcdevworks/spectre-tokens` to `^3.2.0` and `@phcdevworks/spectre-ui`
  to `^2.5.0` in `package.json`, closing dependency drift against the current
  published `project-design` packages. Both upstream releases were additive
  only. `npm install` / `npm run check` not yet re-run for this bump — this
  repo had a pre-existing, unrelated `@typescript-eslint` peer-dependency
  conflict in `node_modules` that needs a clean reinstall first.

### Added

- Added `@phcdevworks/spectre-manifest` as a devDependency. `spectre.manifest.json`
  at the repo root declares this package's ecosystem role, layer, exports, and
  allowed dependency targets. `check:ecosystem` validates it in the check pipeline.

## [1.0.0] - 2026-05-14

### Added

- CLAUDE.md — primary Claude Code project guide establishing Claude Code as the main AI developer
- AGENTS.md — AI agent instructions with drift-prevention rules and boundary definitions
- `check:drift` npm script — scans for design-system drift (hardcoded visual values) in `src/`, `spectre-theme/`, and `package.json`
- CI drift check step in `.github/workflows/ci.yml`
- Full WordPress standard template hierarchy: `index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`, `front-page.php`, `home.php`, `header.php`, `footer.php`, `sidebar.php`, `searchform.php`, `comments.php`
- Template parts: `content-card.php`, `content-single.php`, `content-page.php`, `content-none.php`
- Gutenberg block editor support via `add_theme_support('block-editor-styles')` and `enqueue_block_editor_assets`
- `theme.json` with typography, colors, spacing, and shadows derived from `@phcdevworks/spectre-tokens`
- Spectre Icons plugin integration (`spectre_base_has_icons()` helper, footer social links, search form shortcode pattern)
- Shell CSS layer in `src/styles/main.css` — all values backed by `var(--sp-*)` tokens
- `check-theme-asset-contract.ts` script for Vite manifest validation
- GitHub Actions CI pipeline (`ci.yml`) with Node matrix (22.x, 24.x), PHP 8.2, build, asset check, lint, PHP lint, drift check
- WordPress smoke test workflow (`wordpress-smoke.yml`) verifying real WordPress install and core routes
- Sidebar widget area registration
- Primary menu fallback registration
- Primary and footer nav menu locations
- `spectre_base_primary_menu_fallback` function
- `.editorconfig`, `.prettierrc`, `.gitattributes`, `.npmignore`
- VSCode workspace config and extensions recommendation
- `CONTRIBUTING.md`, `SECURITY.md`, `CODE_OF_CONDUCT.md`, `ROADMAP.md`, `TODO.md`
- `dependabot.yml` for automated npm dependency updates

### Changed

- Merged duplicate `.spectre-post-navigation` CSS rule in `src/styles/main.css`
- Synced version across `package.json`, `spectre-theme/style.css`, and `spectre-theme/readme.txt` (all now `1.0.0`)
- `README.md` npm version reference corrected to `11.14.1`
- Design-system boundary documented in README with drift-scan command

## [0.0.1] - 2026-01-31

### Added

- Initial commit: Vite WordPress theme template
- Rename project to spectre-base

[unreleased]: https://github.com/phcdevworks/spectre-base/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/phcdevworks/spectre-base/compare/v0.0.1...v1.0.0
[0.0.1]: https://github.com/phcdevworks/spectre-base/tree/v0.0.1
