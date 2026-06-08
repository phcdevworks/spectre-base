# Spectre Base Theme Execution Todo

This todo list is aligned to the current repository and the roadmap in
`ROADMAP.md`. It is scoped to base theme / agency DX, WordPress.org submission
readiness, block editor support, Spectre ecosystem integration, and CI quality.

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
- [x] Spectre Icons plugin integration (`spectre_base_has_icons()`
      helper, footer social icons, documented in README)

### P2: CI, Docs, and Governance

- [x] GitHub Actions CI pipeline (build, asset check, lint, PHP lint, drift
      check; Node matrix 22/24; PHP 8.2)
- [x] WordPress smoke test workflow (real WP install, core route verification)
- [x] `CLAUDE.md` establishes Claude Code as the primary AI developer
- [x] Versions synced across `package.json`, `style.css`, `readme.txt`
- [x] Drift-prevention documentation in `AGENTS.md` and `README.md`

---

## Phase 2 - Base Theme and Agency DX: In Progress

This phase reframes the project as `@phcdevworks/spectre-base` — a parent theme
agencies use as a foundation for client child themes. The child theme generator
and rename shipped in this phase. Remaining work focuses on hardening the parent
theme API, WordPress.org submission, and page builder compatibility.

### P0: Project Rename and Reframe: Completed

- [x] Renamed package to `@phcdevworks/spectre-base`
- [x] Renamed theme to `Spectre Base Theme` across all code, PHP, and docs
      - `package.json`, `style.css`, `readme.txt`, all PHP text domains and
        function prefixes (`spectre_base_*`), all WP handle names, all agent
        and governance docs
      - GitHub repo rename and URL updates deferred until repo is renamed on
        GitHub
- [x] ESLint clean — zero warnings (added `scripts/` override for `no-console`)
- [x] `style.css` Tags updated to WordPress.org standardized tag list
- [x] `readme.txt` `== Screenshots ==` section added

### P1: Child Theme Generator: Completed

- [x] `npm run create:child -- <client-name>` scaffolds a child theme directory
      - `style.css` — WordPress child theme header, `Template: spectre-theme`,
        GPL license, WP.org standard tags
      - `functions.php` — enqueues child stylesheet with `spectre-base-style`
        dependency
      - `theme.json` — minimal, inherits full parent token set, ready for
        client brand overrides
- [x] `spectre-child-*/` added to `.gitignore`

### P2: WordPress.org Submission Readiness

- [x] Replace `screenshot.png` with a 1200x900px render of the theme's default
      appearance
  - `spectre-theme/screenshot.png` — 1200x900 PNG, captured via Playwright
    against a live WordPress install running Spectre Base Theme
  - Tooling: `.wp-env.json`, `scripts/capture-screenshot.ts`, `npm run screenshot`

- [x] Run a full WordPress.org theme review compliance pass
  - Code audit complete: no obfuscated code, GPL license declared throughout,
    no external resource calls, no TGM Plugin Activation
  - Added `load_theme_textdomain('spectre-base')` to `spectre_base_setup()`
  - Added `ABSPATH` guards to all 17 template and template-part PHP files
  - Created `spectre-theme/languages/` directory
  - Final verification with Theme Check plugin requires a live WordPress install

- [x] Update GitHub URLs throughout after repo is renamed on GitHub
  - Updated `package.json`, `spectre-theme/style.css`, `README.md`, `CHANGELOG.md`
    from `spectre-wordpress-themes` → `spectre-base` (matches git remote)

### P3: Parent Theme API

The base theme needs documented, stable extension points before agencies rely
on it in production. These make it a proper parent theme rather than just a
starter.

- [x] Define and document the PHP hook API
  - Added action hooks (`spectre_base_before_header`,
    `spectre_base_before_site_branding`, `spectre_base_after_site_branding`,
    `spectre_base_after_header`, `spectre_base_before_footer`,
    `spectre_base_after_footer`, `spectre_base_before_sidebar`,
    `spectre_base_after_sidebar`) and filter hooks
    (`spectre_base_primary_nav_args`, `spectre_base_footer_nav_args`,
    `spectre_base_sidebar_id`) to `header.php`, `footer.php`, `sidebar.php`
  - Documented all hooks, plus the existing `spectre_base_footer_social_icons`
    filter and core WP template-loading hooks, in `README.md` under
    "PHP hook API"
  - Acceptance met: agency can swap header, footer, and sidebar content via
    hooks, or override the files entirely via standard child theme lookup,
    without touching parent files

- [x] Lock the CSS custom property namespace
  - Documented in `README.md` under "CSS custom property namespace": the
    entire `--sp-*` namespace is owned by `@phcdevworks/spectre-tokens` /
    `@phcdevworks/spectre-ui`; the theme only consumes via `var()` and never
    declares `--sp-*` properties; child themes must not redeclare them and
    should override token values through `theme.json` presets instead

- [x] Add a `Child Themes` section to `README.md`
  - Covers `create:child` usage, the `Template: spectre-theme` header,
    token overrides via `theme.json`, and PHP hook / template override points

### P4: Page Builder Compatibility

- [ ] Document Elementor integration
  - Acceptance: Spectre UI CSS confirmed working in Elementor widget contexts;
    `spectre-icons` icon picker confirmed working; documented in README
  - Status: Depends on Elementor environment for testing

- [ ] Add Elementor support hooks if needed (CSS scope compatibility)

- [ ] Evaluate Beaver Builder support
  - Align with `spectre-icons` Beaver Builder roadmap timeline

### P5: Later / Controlled Improvement

- [ ] Evaluate Spectre Shell router (hash mode) for WordPress SPA pages
  - Implement only if a concrete use case is proven

## Recommended Execution Order

1. Phase 1 — done.
2. Phase 2 — done.
3. Phase 3 P0 — PHP hook API; unblocks safe child theme development.
4. Phase 3 P1 — CSS custom property namespace documentation.
5. Phase 3 P2 — Child Themes README section.
6. Phase 4 P0 — Elementor integration; depends on Elementor environment.
7. Phase 4 P1 — Beaver Builder support; aligned to spectre-icons roadmap.
8. Phase 4 P2 — Spectre Shell router evaluation; lowest urgency.

## Explicitly Out of Scope

- Do not redefine token values or local design values -- consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic (belongs in plugin repos like `spectre-icons`)
- Do not add WooCommerce templates without proven product need
- Do not add client-specific branding, hardcoded visual values, or local token
  definitions
