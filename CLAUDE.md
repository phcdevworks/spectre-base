# CLAUDE.md - Spectre Base Theme

## Verified TODO Completion Workflow

For every TODO item Claude Code completes, use this sequence in the same change:

1. Keep the item in `TODO.md` while implementation or verification is in progress.
2. Confirm every acceptance criterion is met and the repository's required tests and validation gate pass.
3. Only after verification passes, remove the completed item from `TODO.md` immediately; do not leave it active or checked off.
4. Update `CHANGELOG.md` under `[Unreleased]` as usual, update the applicable `ROADMAP.md` shipped/status table and phase text, and update every other affected status or dependency reference.

If implementation is incomplete or any required check fails, keep the TODO item open and do not describe the work as shipped.

## Project Identity

**Package:** `@phcdevworks/spectre-base`
**Layer:** WordPress Shell -- delivers the Spectre design system through WordPress
**Human owner:** Bradley Potts
**Primary AI developer:** Claude Code (claude-sonnet-4-6)

**Repository:** `spectre-base`
**Deployable theme directory:** `spectre-theme/`

`@phcdevworks/spectre-base` is the WordPress theme delivery layer
of the Spectre system. It provides a Vite-powered build pipeline, TypeScript
entry point, Tailwind CSS 4 integration, and a standard `spectre-theme`
WordPress directory that consumes `@phcdevworks/spectre-tokens` and
`@phcdevworks/spectre-ui` as upstream contracts.

This file is the authoritative guide for Claude Code operating in this
repository. Read it before touching any source file.

## Multi-Agent Team

Full roster, authority table, and PR requirements: [AGENTS.md](AGENTS.md).
Claude Code is the lead implementation authority for templates, build
tooling, CSS, and TypeScript. Resolve conflicts by referencing this file and
`AGENTS.md`.

Release workflow artifacts live in `.codex/`:
- `.codex/release-checklist.md` -- Codex release gate steps
- `.codex/handoff-template.md` -- standard handoff summary format

## Git Access — Denied

**Claude Code has zero git access in this repo, as part of a companywide
policy.** Claude Code must not run `git commit`, `git push`, `git tag`, or
any other git command — read-only or mutating — here. This supersedes the
prior commit/push/tag grant described in this repo's `AGENTS.md`. OpenAI
Codex now executes all git operations for this repo; see `AGENTS.md` and
`CODEX.md`.

When work is ready, Claude Code stops short of any git command and hands off
to Codex (or Bradley Potts) with a summary of files changed and validation
performed (`npm run check`).

## The One Rule That Overrides Everything

**The CMS delivers; the design system defines.** Never redefine design tokens,
hardcode hex colors, or hand-roll visual components in PHP. All visual decisions
belong to `@phcdevworks/spectre-tokens`, `@phcdevworks/spectre-ui`, and
`@phcdevworks/spectre-components`. This theme consumes them.

Full shared rules -- edit boundaries, core directives, reusable starter
boundary, drift prevention, and dependency contracts -- live in `AGENTS.md`.

## Commands

```bash
npm install          # Install dependencies
npm run dev          # Start Vite dev server (http://localhost:5173)
npm run build        # TypeScript check + Vite production build
npm run check:assets       # Validate Vite manifest and asset contract
npm run check:version-sync # Confirm README version matches package.json
npm run check:drift        # Scan for design-system drift (hardcoded values)
npm run check:ecosystem    # Validate spectre.manifest.json against the ecosystem contract
npm run lint               # ESLint (TypeScript)
npm run lint:fix           # ESLint with auto-fix
npm run lint:php           # PHP syntax validation (all .php in spectre-theme/)
npm run validate           # Build, asset contract, version sync, lint, PHP lint, drift scan
npm run check              # Full gate: validate + check:ecosystem
npm run format             # Prettier formatting
```

Run this sequence before any PR or handoff:

```bash
npm run check
```

## Edit Permissions

Follow the shared edit-permission table in `AGENTS.md`. The important
operational rule for Claude Code: never hand-edit `spectre-theme/dist/`; always
regenerate it with `npm run build`.

## Architecture

```
spectre-base/
├── src/
│   ├── js/main.ts          # Theme JS entrypoint -- registers Spectre web components
│   └── styles/main.css     # Theme CSS entrypoint -- imports tokens, UI, shell styles
├── spectre-theme/          # Deployable WordPress theme directory
│   ├── functions.php       # Asset enqueueing, theme setup, env-aware loading
│   ├── style.css           # WordPress theme header (no styles -- just metadata)
│   ├── theme.json          # Gutenberg editor tokens (all values from var(--sp-*))
│   ├── dist/               # Vite build output (never edit directly)
│   ├── template-parts/     # Reusable PHP template fragments
│   └── *.php               # WordPress template hierarchy
├── scripts/
│   └── check-theme-asset-contract.ts  # Asset contract validator
├── .codex/                 # Codex release artifacts (checklist, handoff template)
├── AGENTS.md               # Multi-agent coordination guide (all AI agents read this)
├── vite.config.ts          # Build configuration
└── eslint.config.ts        # ESLint configuration
```

## Key Scripts Reference

| Script | Purpose |
|---|---|
| `npm run check` | Full gate: `validate` + `check:ecosystem` |
| `npm run validate` | Build, asset contract, version sync, lint, PHP lint, drift scan |
| `npm run build` | TypeScript check and Vite production build |
| `npm run check:assets` | Validate Vite manifest and theme asset contract |
| `npm run check:version-sync` | Confirm README version reference matches `package.json` |
| `npm run lint` | ESLint for TypeScript |
| `npm run lint:php` | PHP syntax validation |
| `npm run check:drift` | Scan for hardcoded visual values and drift |
| `npm run check:ecosystem` | Validate `spectre.manifest.json` against the Spectre ecosystem contract |

## Drift Check

Run after any visual or template change:

```bash
npm run check:drift
```

This runs `scripts/check-drift.ts` over maintained TypeScript, CSS, PHP, and
JSON source. Generated output and binary files are excluded. Spectre token
references and `sp-*` recipes are allowed. Violations and scan errors exit
nonzero, so drift fails the validation gate.

## Environment Setup

### Local WordPress + Vite HMR

1. Build or start dev server: `npm run dev`
2. Symlink the theme into WordPress:
   ```bash
   ln -s /path/to/spectre-base/spectre-theme /path/to/wordpress/wp-content/themes/spectre-theme
   ```
3. Set environment in `wp-config.php`:
   ```php
   define('WP_ENVIRONMENT_TYPE', 'development');
   ```
4. To change the Vite server port:
   ```php
   define('VITE_DEV_SERVER', 'http://localhost:5174');
   ```

### Production Build

```bash
npm run build        # Writes hashed assets + manifest to spectre-theme/dist/
npm run check:assets # Confirm manifest is valid and entry files exist
```

## Version Sync Checklist

When bumping the version, update all three locations:
- `package.json` -> `"version"`
- `spectre-theme/style.css` -> `Version:` header
- `spectre-theme/readme.txt` -> `Stable tag:`

## CI

GitHub Actions runs on every push and PR to `main`:
- `npm run check`

Node matrix: 22.12.0 and 24.x. PHP: 8.2.

WordPress smoke test runs separately and installs a real WordPress instance to
verify core routes.

## What This Repo Owns

- `vite.config.ts` -- build and dev server configuration
- `src/js/main.ts` -- theme JavaScript entrypoint
- `src/styles/main.css` -- theme CSS entrypoint
- `spectre-theme/` -- WordPress theme files
- `spectre-theme/dist/` -- compiled build output (never edit directly)

## What This Repo Does Not Own

Shared ownership boundaries live in `AGENTS.md`.

- Design token values (`@phcdevworks/spectre-tokens`)
- Component visual contracts (`@phcdevworks/spectre-ui`,
  `@phcdevworks/spectre-components`)
- WordPress core, plugin management, or hosting

## Code Style

- ES modules throughout, strict TypeScript, no `any`.
- Prettier config: single quotes, no semicolons, trailing commas off, 80-char
  print width.
- No comments unless the why is non-obvious. Never comment what the code does.
- PHP templates must stay structural, escaped, and WordPress-native.
- CSS must consume Spectre variables or official upstream CSS only.
