# TODO.md

# Spectre WordPress Themes Execution Todo

This todo list is aligned to the current repository and the roadmap in
`ROADMAP.md`. It is scoped to WordPress.org submission readiness, block editor
support, Spectre ecosystem integration, CI quality, and developer experience.

## Phase 1 - Foundation: Completed

All Phase 1 items were delivered during initial development. The following were
completed before the stable baseline.

### P0: Core Shell and Build

- [x] Vite build pipeline compiles TypeScript and CSS correctly
- [x] Tailwind CSS 4 integrated
- [x] Theme consumes `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
      without redefining their contracts locally
- [x] Asset contract validation (`check:assets`) in place and running in CI
- [x] Design-system drift scan (`check:drift`) in place and running in CI
- [x] Restored design-system alignment across the WordPress shell
      - `src/styles/main.css` uses only `var(--sp-*)` tokens
      - PHP templates use semantic shell classes and WordPress APIs
      - `theme.json` layout settings consume Spectre layout tokens
- [x] Merged duplicate `.spectre-post-navigation` CSS rule in `main.css`

### P1: WordPress Template Coverage and Block Editor

- [x] Full standard WordPress template hierarchy in place
      (`index.php`, `single.php`, `page.php`, `archive.php`, `search.php`,
      `404.php`, `front-page.php`, `home.php`, `header.php`, `footer.php`,
      `sidebar.php`, `searchform.php`, `comments.php`)
- [x] Template parts: `content-card.php`, `content-single.php`,
      `content-page.php`, `content-none.php`
- [x] Gutenberg block editor support
      (`add_theme_support('block-editor-styles')`, `enqueue_block_editor_assets`,
      `theme.json`)
- [x] Spectre Icons plugin integration (`spectre_wordpress_themes_has_icons()`
      helper, footer social icons, documented in README)

### P2: CI, Docs, and Governance

- [x] GitHub Actions CI pipeline (build, asset check, lint, PHP lint, drift
      check; Node matrix 22/24; PHP 8.2)
- [x] WordPress smoke test workflow (real WP install, core route verification)
- [x] `CLAUDE.md` establishes Claude Code as the primary AI developer
- [x] Versions synced across `package.json`, `style.css`, `readme.txt`
- [x] Drift-prevention documentation in `AGENTS.md` and `README.md`

---

## Phase 2 - Mature Operations

All items below are forward-looking. This phase starts from the stable
foundation and focuses on WordPress.org submission, ecosystem integration, and
controlled improvement.

### P0: WordPress.org Submission Readiness

- [ ] Verify `readme.txt` passes WordPress.org theme review requirements
  - File targets: `spectre-theme/readme.txt`
  - Acceptance: All required sections present, no TGM Plugin Activation,
    license consistent with GPL throughout

- [ ] Replace `screenshot.png` with a 1200x900px render of the theme's default
      appearance
  - File targets: `spectre-theme/screenshot.png`
  - Acceptance: Correct dimensions, reflects current default layout

- [ ] Run a full WordPress.org theme review compliance pass
  - Use the Theme Check plugin against a live WordPress install
  - Check for: no obfuscated code, correct license throughout, no calls to
    external resources without user consent, no TGM Plugin Activation

### P1: Developer Experience and Ecosystem Integration

- [ ] Document Elementor integration
  - File targets: `spectre-theme/functions.php`, `README.md`
  - Acceptance: Spectre UI CSS confirmed working in Elementor widget contexts;
    `spectre-icons` icon picker confirmed working; documented in README
  - Status: Depends on Elementor environment for testing

- [ ] Add Elementor support hooks if needed (CSS scope compatibility)

### P2: Later / Controlled Improvement

- [ ] Evaluate Spectre Shell router (hash mode) for WordPress SPA pages
  - Document whether hash-based routing from `spectre-shell-router` is viable
    inside a WordPress page or custom template
  - Implement only if a concrete use case is proven

- [ ] Add child theme starter template
  - `spectre-init` can scaffold a child theme of this base

- [ ] Evaluate Beaver Builder support
  - Align with `spectre-icons` Beaver Builder roadmap timeline

## Recommended Execution Order

1. WordPress.org `readme.txt` compliance verification
2. `screenshot.png` replacement (1200x900px)
3. WordPress.org full review pass
4. Elementor integration
5. Spectre Shell router evaluation
6. Child theme starter
7. Beaver Builder support (aligned to spectre-icons roadmap)

## Explicitly Out of Scope

- Do not redefine token values or local design values -- consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic (belongs in plugin repos like `spectre-icons`)
- Do not add WooCommerce templates without proven product need
- Do not add client-specific branding, hardcoded visual values, or local token
  definitions
