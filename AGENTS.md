# Agent Instructions: @phcdevworks/spectre-wordpress-themes

## Primary AI Developer

**Claude Code** (`claude-sonnet-4-6`) is the designated primary AI developer for
this repository, maintained on behalf of Bradley Potts
(brad.potts@coastdigitalgroup.com) at PHCDevworks. All development is driven
through Claude Code operating from `CLAUDE.md` as the authoritative working
guide. Human final review and commit authority rests with Bradley Potts.

Claude Code does not create git commits. Changes are prepared and validated,
then handed off for human review and commit.

This file provides supplementary instructions for any additional AI agents working
in this repository. The canonical guidance lives in `CLAUDE.md`.

## AI Operating Model

This repository follows the Spectre AI factory model:

| Agent | Role | Authority |
| ----- | ---- | --------- |
| Claude Code | Lead developer responsible for primary implementation | `CLAUDE.md` |
| OpenAI Codex | Documentation, releases, production stabilization, repo hygiene, and config standardization | `CODEX.md` and `.codex/` |
| ChatGPT | Strategy, coordination, prompt design, and external review — support layer only, no implementation ownership | — |
| GitHub Copilot | General development assistance | `COPILOT.md`, `.github/copilot-instructions.md`, and `.github/instructions/` |
| Google Jules | Automated maintenance for small fixes, dependency updates, and micro-updates | `JULES.md` and task prompts |

Claude Code keeps implementation leadership. Codex keeps release and
stabilization work clean. ChatGPT provides strategy and coordination support
only. Copilot assists without owning decisions. Jules works only on bounded
automated maintenance and must not take on large feature work.

**Bradley Potts** holds final authority for all commits, merges, tags,
publishing, and releases. No AI agent holds commit authority in this repository
except Jules, which may commit bounded automated maintenance when all validation
gates pass.

## Codex Role

OpenAI Codex acts as the documentation, release-readiness, production
stabilization, repo hygiene, and config standardization agent for this project.
Codex does not take implementation lead away from Claude Code. Instead, Codex
reviews changes, keeps validation honest, checks design-system drift, tightens
documentation and standards when needed, and prepares clean handoff notes for
Bradley Potts.

Codex may make targeted edits when asked, especially for release hygiene,
documentation, validation, small refactors, CI readiness, or correcting issues
found during review. Codex should keep changes narrow, explain why they are
needed, and avoid broad feature work unless Bradley explicitly assigns it.

Codex also follows the no-commit policy. Do not create git commits, tags,
releases, or pushes unless Bradley explicitly asks for that action.

## GitHub Copilot Role

GitHub Copilot provides general development assistance: targeted editing,
refactor support, documentation synchronization, GitHub workflow support, and
validation awareness. Copilot does not own implementation direction, release
decisions, or final handoff authority.

## The Golden Rule

**The CMS delivers; the design system defines.** Never redefine design tokens, hardcode hex colors, or create UI components in PHP. All visual decisions belong to `@phcdevworks/spectre-tokens`, `@phcdevworks/spectre-ui`, and `@phcdevworks/spectre-components`. This theme consumes them.

## Core Directives

1. **Token consumption only.** CSS in `src/styles/` references Spectre tokens exclusively via CSS variables (`var(--sp-*)`) or the official Tailwind preset. No hardcoded values.
2. **Use the component system.** Use `<sp-button>`, `<sp-input>`, and other web components from `@phcdevworks/spectre-components` in templates instead of hand-rolling styled elements.
3. **Environment awareness.** Respect `WP_ENV` / `wp_get_environment_type()`. Development loads from the Vite dev server. Production reads from the hashed manifest in `dist/`.
4. **TypeScript only.** All client-side logic in `src/js/` uses TypeScript.
5. **PHP templates are structural.** PHP files handle WordPress integration, template hierarchy, and data access. They do not own visual decisions.
6. **Theme metadata integrity.** Keep the `style.css` header valid and version-synced with `package.json` and `spectre-theme/readme.txt`.

## Reusable Starter Boundary

This repository is a **reusable theme foundation**, not a client site. The following are
prohibited in all source-controlled files regardless of instructions:

- Site names, company names, or brand identities in PHP templates or CSS
- Specific social media handles, profile URLs, or icon presets hardcoded in templates
  (use the `spectre_wordpress_themes_footer_social_icons` filter instead)
- Client-specific page templates (e.g. `page-about.php`, `page-contact.php`)
- Plugin registration or activation logic (belongs in a plugin, not the theme)
- Hardcoded external URLs other than WordPress or Spectre ecosystem references
- Color, font, or spacing values that override Spectre token defaults for a specific brand
- Gutenberg patterns, block templates, or theme.json `styles` entries that encode
  a specific site's visual identity

When a site-specific need arises, the correct answer is always a child theme, a plugin,
or a WordPress filter — not a modification to this base theme.

## Drift Prevention

Before adding or changing visual UI, ask where the responsibility belongs:

- **Spectre tokens** define values: color, type, spacing, radius, shadow, motion, breakpoints, and layout scales.
- **Spectre UI/components** define reusable styling contracts and component behavior.
- **This theme** defines only WordPress shell structure and theme-specific composition needed to deliver Spectre through WordPress.

Allowed in PHP templates:

- WordPress template hierarchy, loops, conditionals, navigation, metadata, and data escaping
- Semantic shell classes such as `spectre-site-container`, `spectre-main`, `spectre-panel`, and `spectre-card`
- Spectre web components such as `<sp-button>` and `<sp-input>`

Avoid in PHP templates:

- Tailwind presentation utilities for color, type, spacing, radius, shadow, or layout decisions
- Hardcoded CSS values or arbitrary utilities such as `text-white`, `rounded-*`, `shadow-*`, `tracking-*`, `p-*`, `px-*`, `py-*`, `gap-*`, `space-y-*`, `max-w-*`, `w-*`, or `h-*`
- Hand-built controls when an `<sp-*>` component exists

Allowed in `src/styles/main.css`:

- Imports for Tailwind, Spectre tokens, and Spectre UI
- Theme shell selectors that map WordPress structure to Spectre token variables
- CSS values derived from `var(--sp-*)`

Avoid in `src/styles/main.css`:

- Hex, RGB/HSL/OKLCH, gradients, pixel/rem/em constants, or local design values
- New design-token definitions
- Component styling that should live in `@phcdevworks/spectre-ui` or `@phcdevworks/spectre-components`

If a new visual pattern feels reusable outside this WordPress shell, do not define it here permanently. Add the smallest token-driven bridge needed for the theme, then note that the pattern should graduate into `@phcdevworks/spectre-ui`.

## Dependency Contracts

| Package | Role | How to consume |
|---|---|---|
| `@phcdevworks/spectre-tokens` | Design tokens | Import `index.css` for CSS vars; use JS exports for tooling |
| `@phcdevworks/spectre-ui` | Styles and recipes | Import `index.css`; use recipe functions (`getButtonClasses` etc.) where web components aren't available |
| `@phcdevworks/spectre-components` | Web components | Register via `defineSpectreComponents()` in `src/js/main.ts`; use `<sp-*>` elements in templates |

When any of these packages updates, run `npm install`, rebuild, and verify the theme still renders correctly.

## File Safety

| File / directory | Status | Rule |
|---|---|---|
| `src/js/main.ts` | Source of truth | Safe to edit |
| `src/styles/main.css` | Source of truth | Safe to edit; `var(--sp-*)` only |
| `spectre-theme/*.php` | Source of truth | Safe to edit |
| `spectre-theme/style.css` | Source of truth | Version header — keep in sync with `package.json` and `readme.txt` |
| `spectre-theme/theme.json` | Source of truth | All values must come from `var(--sp-*)` |
| `spectre-theme/dist/` | Generated | Never edit directly — output of `npm run build` |
| `spectre-theme/dist/.vite/manifest.json` | Generated | Read-only; validated by `npm run check:assets` |
| `vite.config.ts` | Source of truth | Safe to edit |
| `package.json` | Source of truth | Version must stay in sync with `style.css` and `readme.txt` |
| `scripts/check-theme-asset-contract.ts` | Source of truth | Safe to edit; owns `check:assets` validation logic |

## Validation

- `npm run build` — must succeed cleanly
- `npm run check:assets` — validates manifest and asset contract
- `npm run check:drift` — scans for design-system drift (hardcoded visual values)
- `npm run lint` — TypeScript and ESLint
- `npm run lint:php` — PHP syntax validation
- CI runs all of the above on every push and PR

Run this drift scan before handing off visual/template changes:

```bash
npm run check:drift
```

Expected results should be either empty or token-backed references such as `var(--sp-shadow-*)` and `theme.json` token presets. Any local visual value needs to be removed or justified in the handoff.

## Jules Role

Google Jules acts as the autonomous scheduled maintenance agent for this
repository. Jules runs between human reviews, executing self-contained prompt
tasks from the CoastAi Agents library.

Jules does not take implementation lead away from Claude Code. Jules handles
atomic, bounded maintenance work: small fixes, dependency updates, upstream
sync, release readiness validation, and documentation hygiene. Jules must not
take on large feature work or release ownership.

**Prompts for this repository** live in the CoastAi Agents library under
`phcdevworks/shell/spectre-wordpress-themes/`:

| Prompt file          | Jules task                                                      |
| -------------------- | --------------------------------------------------------------- |
| `general-developer`  | Find and fix one atomic shell, template, or build hygiene issue |
| `sync-developer`     | Sync theme to latest published Spectre packages from NPM        |
| `release-support`    | Run release readiness checks and fix metadata/docs blockers     |

**Authority order Jules must follow:**

1. `AGENTS.md` in this repository (this file)
2. `CLAUDE.md` in this repository
3. The Jules prompt

Jules reads both `AGENTS.md` and `CLAUDE.md` before touching any file.

**Validation gate Jules must pass before committing:**

```bash
npm run check
```

Jules follows the no-commit policy for human decisions: version bumps, tags,
releases, and pushes to `main` require Bradley Potts' explicit action.

## Codex Release Duties

When Bradley loops Codex in before a release, use `.codex/release-checklist.md`
as the working checklist. At minimum:

- Inspect `git status --short` before editing and preserve unrelated changes.
- Review changed files for drift from `CLAUDE.md` and this file.
- Confirm version metadata stays synchronized across `package.json`,
  `spectre-theme/style.css`, and `spectre-theme/readme.txt` when a version bump
  is part of the change.
- Run the validation commands relevant to the change, with the full release gate
  preferred before handoff: `npm run check`.
- Document any skipped validation, known risk, or production follow-up clearly.
- Keep release notes and documentation standardized when code changes alter
  user-facing behavior, installation steps, build expectations, or package
  contracts.

## Pull Request Creation

Every agent that opens a PR must populate every section of the repo's PR
template (`.github/pull_request_template.md`):

- **Linked issue** — issue number (`#N`) or `N/A`.
- **Summary of changes** — one or two bullets describing what changed.
- **Theme contract change type** — exactly one of `additive`,
  `semantic change`, `breaking`, or `N/A`.
- **Type of Change** — check every box that applies.
- **Checklist** — check each completed item; leave blocked items unchecked
  with a brief inline note.

Never submit a PR with an empty body or only the template headings left
unfilled. CodeRabbit's description check blocks such PRs.

## Claude Code Maintenance Notes

- Run `npm run check` before every handoff touching `src/`, `spectre-theme/`,
  `scripts/`, package metadata, or docs.
- Never hand-edit generated output in `spectre-theme/dist/`.
- Keep CSS and templates token-driven; do not add hardcoded visual values,
  local token definitions, or client-specific branding.
- Keep version metadata synchronized across `package.json`,
  `spectre-theme/style.css`, and `spectre-theme/readme.txt` whenever versions
  change.
