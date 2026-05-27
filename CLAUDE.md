# CLAUDE.md - Spectre Base Theme

## Project Identity

**Package:** `@phcdevworks/spectre-base`
**Layer:** WordPress Shell -- delivers the Spectre design system through WordPress
**Human owner:** Bradley Potts (brad.potts@coastdigitalgroup.com)
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

`AGENTS.md` is the shared guide for agent roles, edit boundaries, PR
requirements, core rules, starter boundary, drift prevention, and dependency
contracts. Claude Code is the lead implementation authority for templates,
build tooling, CSS, and TypeScript. Resolve conflicts by referencing this file
and `AGENTS.md`.

Release workflow artifacts live in `.codex/`:
- `.codex/release-checklist.md` -- Codex release gate steps
- `.codex/handoff-template.md` -- standard handoff summary format

## Commit Policy

Claude Code does not create git commits in this repository. Prepare changes,
run all validation, and leave staging, committing, tagging, and pushing to
human review.

## Pull Request Creation

Follow the shared PR requirements in `AGENTS.md`. Claude Code prepares
validated changes for human review; Bradley Potts handles final commit, merge,
tag, and release authority.

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
npm run check:assets # Validate Vite manifest and asset contract
npm run check:drift  # Scan for design-system drift (hardcoded values)
npm run lint         # ESLint (TypeScript)
npm run lint:fix     # ESLint with auto-fix
npm run lint:php     # PHP syntax validation (all .php in spectre-theme/)
npm run check        # Full validation gate (alias for npm run validate)
npm run format       # Prettier formatting
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
| `npm run check` | Full gate, alias for `npm run validate` |
| `npm run validate` | Build, asset contract, lint, PHP lint, drift scan |
| `npm run build` | TypeScript check and Vite production build |
| `npm run check:assets` | Validate Vite manifest and theme asset contract |
| `npm run lint` | ESLint for TypeScript |
| `npm run lint:php` | PHP syntax validation |
| `npm run check:drift` | Scan for hardcoded visual values and drift |

## Drift Check

Run after any visual or template change:

```bash
npm run check:drift
```

This executes:

```bash
rg -n "#[0-9a-fA-F]{3,8}|rgb\(|rgba\(|hsl\(|hsla\(|oklch\(|linear-gradient|\btext-white\b|rounded-|shadow-|tracking-|\bprose\b|\btext-[0-9]|\bp-[0-9]|\bpx-[0-9]|\bpy-[0-9]|\bgap-[0-9]|\bspace-y-|\bmax-w-|\bw-[0-9]|\bh-[0-9]|min-width: [0-9]|[0-9]+px|[0-9]+rem|[0-9]+em" src spectre-theme package.json
```

Expected output is empty or only token-backed references like
`var(--sp-shadow-*)` and `theme.json` token presets. Any local visual value
must be removed or justified before merging.

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
