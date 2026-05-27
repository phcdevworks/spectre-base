# Spectre Base Theme Roadmap

This roadmap is grounded in the current repository shape and public context of
`@phcdevworks/spectre-base` as it exists today.

`@phcdevworks/spectre-base` is the WordPress theme foundation for
the Spectre suite. It provides a Vite-powered build pipeline, TypeScript entry
point, Tailwind CSS 4 integration, and a standard `spectre-theme` WordPress
directory that consumes `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
as upstream contracts.

The WordPress push is real and active. `spectre-icons` is already live on
WordPress.org with 100+ active installs. This theme is the next major surface in
that ecosystem push.

## 1. Current Repo Assessment

### Current strengths

- Vite build pipeline is in place and compiles TypeScript and CSS correctly.
- Tailwind CSS 4 is integrated.
- The theme consumes `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
  without redefining their contracts locally.
- Asset contract validation (`check:assets`) is in place.
- Design-system drift scan (`check:drift`) is in place and runs in CI.
- Full standard WordPress template hierarchy is covered.
- Gutenberg block editor support is in place via `theme.json` and `enqueue_block_editor_assets`.
- `spectre-icons` plugin integration is wired into templates and documented.
- GitHub Actions CI pipeline runs on every push and PR.
- WordPress smoke test verifies a real WordPress install.
- `CLAUDE.md` establishes Claude Code as the primary AI developer.
- All versions synced: `package.json`, `spectre-theme/style.css`, `spectre-theme/readme.txt`.

### Remaining gaps

- `screenshot.png` is a placeholder — needs a 1200×900px render of the theme's
  actual default appearance for WordPress.org submission.
- No Elementor integration beyond what the `spectre-icons` plugin provides.
- No Spectre Shell integration — there is no mechanism for SPA-style navigation
  within the theme.
- No child theme scaffold.
- WordPress.org full compliance review has not been run.

## 2. Roadmap

## P0: WordPress.org Submission Readiness

### P0.1 Screenshot Replacement ✓ (partial)

`screenshot.png` exists but is a placeholder. Replace with a 1200×900px render
of the theme's default appearance.

### P0.2 WordPress.org Compliance Review

Run the Theme Check plugin against a live WordPress install and resolve any
reported issues. Check for: no TGM Plugin Activation, no obfuscated code,
correct GPL license throughout, no external resource calls without consent.

**Status:** Not yet run.

### P0.3 Gutenberg Block Editor Support ✓ Done

- `add_theme_support('block-editor-styles')` registered in `functions.php`
- `enqueue_block_editor_assets` enqueues compiled CSS to the block editor
- `theme.json` defines typography, colors, spacing, and shadows from Spectre tokens
- Core blocks render within the theme shell

### P0.4 Spectre Icons Plugin Integration ✓ Done

- `spectre_base_has_icons()` helper checks for plugin presence
- Footer renders social icon links when plugin is active
- README documents the integration and conditional usage pattern

### P0.5 PHP Template Coverage ✓ Done

Full standard WordPress template hierarchy:
`index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`,
`front-page.php`, `home.php`, `header.php`, `footer.php`, `sidebar.php`,
`searchform.php`, `comments.php`, plus all template parts.

### P0.6 CI Pipeline ✓ Done

- GitHub Actions runs `build`, `check:assets`, `lint`, `lint:php`, `check:drift`
- Node matrix: 22.12.0 and 24.x
- PHP 8.2
- Separate WordPress smoke test workflow

### P0.7 Design-System Drift Prevention ✓ Done

- `check:drift` npm script runs `rg` scan for hardcoded values
- CI runs drift check on every push and PR
- AGENTS.md and README document the boundary and scan command

## P1: Developer Experience and Ecosystem Integration

### P1.1 Document WordPress Dev Workflow ✓ Done

Step-by-step local setup in README including symlink workflow, `wp-config.php`
environment settings, and Vite dev vs. production build distinction.

### P1.2 Elementor Integration

Provide official Elementor support. Confirm Spectre UI CSS works in Elementor
widget contexts. Confirm `spectre-icons` icon picker works within Elementor.
Document in README.

**Status:** Not started. Depends on Elementor environment for testing.

### P1.3 Spectre Shell Integration (Optional)

Evaluate whether the Spectre shell router can be used for SPA-style navigation
within the WordPress theme. Hash-based routing is the likely mechanism.
Implement only if a proven use case emerges.

**Status:** Evaluation deferred. No concrete use case yet.

## P2: Later / Controlled Improvement

### P2.1 Child Theme Framework

Document and scaffold child theme creation from this base theme. Candidate for
the `spectre-init` toolchain.

### P2.2 Beaver Builder Support

Implement when `spectre-icons` ships Beaver Builder support.

## 3. Explicitly Out of Scope

- Do not redefine token values or CSS locally — consume from `@phcdevworks/spectre-tokens`
  and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic here — plugin behavior belongs in separate plugin
  repositories like `spectre-icons`
- Do not add WooCommerce templates unless a proven product need emerges

## 4. Recommended Execution Order

1. Replace `screenshot.png` (1200×900px)
2. WordPress.org full compliance review pass
3. Elementor integration
4. Evaluate Spectre Shell routing for WordPress
5. Child theme framework
6. Beaver Builder support (aligned to spectre-icons roadmap)
