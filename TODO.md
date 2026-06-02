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

- [ ] Run a full WordPress.org theme review compliance pass
  - Use the Theme Check plugin against a live WordPress install
  - Check for: no obfuscated code, correct license throughout, no calls to
    external resources without user consent, no TGM Plugin Activation

- [x] Update GitHub URLs throughout after repo is renamed on GitHub
  - Updated `package.json`, `spectre-theme/style.css`, `README.md`, `CHANGELOG.md`
    from `spectre-wordpress-themes` → `spectre-base` (matches git remote)

### P3: Parent Theme API

The base theme needs documented, stable extension points before agencies rely
on it in production. These make it a proper parent theme rather than just a
starter.

- [ ] Define and document the PHP hook API
  - Audit all `add_action` / `add_filter` calls in `functions.php` and
    templates; expose the ones agencies need to override
  - Document override points in `README.md` and inline in `functions.php`
  - Acceptance: agency can swap header, footer, and sidebar via hooks without
    touching parent files

- [ ] Lock the CSS custom property namespace
  - Document which `--sp-*` variables are safe for child themes to override
    and which are internal
  - Acceptance: documented in `README.md` under a Child Theme section

- [ ] Add a `Child Themes` section to `README.md`
  - Cover: `create:child` usage, `Template:` header, token override via
    `theme.json`, PHP hook points

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

1. `screenshot.png` replacement (1200x900px, requires live WP)
2. WordPress.org full review pass (Theme Check plugin)
3. GitHub URLs updated after repo rename
4. Parent theme hook API documented and locked
5. CSS custom property namespace documented
6. `README.md` Child Themes section
7. Elementor integration
8. Beaver Builder (aligned to spectre-icons roadmap)
9. Spectre Shell router evaluation

## Explicitly Out of Scope

- Do not redefine token values or local design values -- consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic (belongs in plugin repos like `spectre-icons`)
- Do not add WooCommerce templates without proven product need
- Do not add client-specific branding, hardcoded visual values, or local token
  definitions
