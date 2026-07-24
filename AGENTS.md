# Spectre Base Agent Guide

## Repository Snapshot

| Field | Value |
|-------|-------|
| Project team | `project-design` |
| Repository role | Spectre WordPress theme shell |
| Package/artifact | `@phcdevworks/spectre-base` |
| Validation gate | `npm run check` |

## Standard Authority Model

| Agent | Role | Authority |
|-------|------|-----------|
| Claude Code | Lead implementation and validation | [CLAUDE.md](CLAUDE.md) |
| OpenAI Codex | Documentation, release readiness, stabilization, and repo hygiene | [CODEX.md](CODEX.md) |
| ChatGPT | Strategy, coordination, prompt design, and external review | Support only |
| GitHub Copilot | Development assistance | [COPILOT.md](COPILOT.md) |
| Google Jules | Bounded automated maintenance | [JULES.md](JULES.md) |

Bradley Potts holds final authority for commits, merges, tags, publishing, and
releases.

## Cross-Repo Access

This repo may be worked on standalone or alongside any combination of other
PHCDevworks repos — do not assume the company root or sibling project areas
are present. The following rules are self-contained and apply whether or not
that broader context is available.

**File access.** An agent working in this repo has full read/write access to
every file in this repo. When this repo is present alongside other
PHCDevworks repos (company root or sibling `project-*` areas), the same full
read/write access extends to those repos too — there is no per-repo access
restriction anywhere in this workspace. What differs repo-to-repo is not
*access*, it's *editorial ownership*: each repo's own `CLAUDE.md`/`AGENTS.md`
still governs what changes make sense there (design-token authority, layer
boundaries, etc.) — being able to open and edit a file is not the same as it
being this repo's job to change it.

**Cross-repo changelog sync.** When a change in this repo has direct
downstream or upstream impact on another present repo (e.g. a breaking token
rename, an API contract change), an agent may append a `CHANGELOG.md
[Unreleased]` entry directly into that other repo's own changelog — not just
leave a note asking its owner to add it. Rules:

1. Only append new `[Unreleased]` entries — never edit, reorder, or remove
   another repo's existing changelog entries, version headers, or release
   history.
2. Every cross-repo entry must be self-contained and attributed: which repo
   caused it and why, what changed from the affected repo's perspective, and
   the date added.
3. Add it in the same change that produced the impact, not a later session.
4. This never grants release authority — cutting a release, bumping a version
   header, or publishing a package stays gated by that repo's own release
   process and the human owner's final sign-off.

**TODO/roadmap requests.** When work here surfaces a need that belongs to
another repo, an agent may append the request directly to that repo's own
`TODO.md` under a clearly labeled "Requested by Downstream" section (create
it if absent), stating which repo is requesting it, why, the date, and a
link back if the other repo's `TODO.md`/`ROADMAP.md` is reachable.

No AI agent creates commits, tags, publishes packages, or merges changes in
this repo or any other unless that repo's own agent guide explicitly grants
that authority or the human owner has explicitly requested the action.

## Standard Handoff

Every AI-prepared change should report files changed, validation performed,
public behavior or contract impact, and unresolved risks. Do not edit generated
outputs directly. Do not update [CHANGELOG.md](CHANGELOG.md) unless the change
is release-relevant.

## Human Approval Boundaries

Protected theme contract surfaces and breaking public hook or template changes require explicit human approval before merge.

## Upstream Requests and Roadmap Self-Expansion

Full directive: project-team [AGENTS.md](../AGENTS.md) "Upstream Requests and
Roadmap Self-Expansion." Applied to this repo:

- This repo is the Shell layer — its upstream is `spectre-ui`/`spectre-tokens`
  for design. If a needed token or recipe capability doesn't exist upstream,
  append the request to the owning repo's `TODO.md` under
  `## Requested by Downstream`, dated, with the reason and a link back to this
  repo's own TODO.md/ROADMAP.md.
- There is no known downstream consumer of this repo within the workspace yet.
  If one appears, it should append requests to this repo's own `TODO.md` under
  `## Requested by Downstream`, kept visible and separate from self-planned
  theme work.
- This repo's own [ROADMAP.md](ROADMAP.md) may be proactively expanded with new
  or reordered phases by the agent's own analysis — but never mark a phase
  delivered without `npm run check` passing.
- Surface any new TODO request or roadmap expansion in the handoff for Bradley
  Potts in the same change it was made, and reflect cross-repo-relevant
  changes in the project-team's own ROADMAP.md/TODO.md.

## Shared Source Rules

These rules apply to every agent without exception.

| File / directory | Status | Notes |
|---|---|---|
| `src/js/main.ts` | **May edit** | Theme JavaScript entry point |
| `src/styles/main.css` | **May edit carefully** | Token-driven shell CSS only; `var(--sp-*)` values |
| `spectre-theme/*.php` and `spectre-theme/template-parts/` | **May edit** | WordPress structure, escaping, and template hierarchy |
| `spectre-theme/style.css` and `spectre-theme/readme.txt` | **May edit carefully** | Theme metadata; keep version synchronized with `package.json` |
| `spectre-theme/theme.json` | **May edit carefully** | Gutenberg token bridge; values must come from `var(--sp-*)` |
| `scripts/` and `vite.config.ts` | **May edit** | Build and validation tooling |
| `spectre-theme/dist/` | **Never edit directly** | Generated by `npm run build` |
| `spectre.manifest.json` | **May edit** | Update when exports, Spectre dependencies, or stability change |

Full validation command: `npm run check`.

Detailed implementation procedure lives in `CLAUDE.md`. Human contribution
workflow lives in `CONTRIBUTING.md`. Roadmap lives in `ROADMAP.md`.

## Pull Request Creation

Every agent that opens a PR must populate every section of the repo's PR
template (`.github/pull_request_template.md`):

- **Linked issue** - issue number (`#N`) or `N/A`.
- **Summary of changes** - one or two bullets describing what changed.
- **Theme contract change type** - exactly one of `additive`,
  `semantic change`, `breaking`, or `N/A`.
- **Type of Change** - check every box that applies.
- **Checklist** - check each completed item; leave blocked items unchecked
  with a brief inline note.

Never submit a PR with an empty body or only the template headings left
unfilled. CodeRabbit's description check blocks such PRs.

## Mission

**The CMS delivers; the design system defines.** This theme is the WordPress
delivery layer for the Spectre system. It consumes design tokens, UI contracts,
and web components from upstream Spectre packages. It never redefines them.

## Core Rules

1. **Token consumption only.** CSS in `src/styles/` references Spectre tokens
   exclusively via `var(--sp-*)`. No hardcoded hex, RGB, rem, px, or em values.
2. **Use the component system.** Use `<sp-card>`, `<sp-nav>`, `<sp-footer>`,
   `<sp-section>`, `<sp-button>`, `<sp-input>`, and every other matching
   Spectre web component instead of hand-rolling a styled element — even if
   every value in the hand-rolled CSS is token-backed. Token-backed CSS that
   duplicates a component's chrome is still drift: it forks a second,
   unmaintained copy of a contract `spectre-components` already owns, and it
   silently diverges the next time that component changes upstream. Before
   writing new CSS for a structural element, check whether
   `@phcdevworks/spectre-components` already has an `<sp-*>` element for it.
3. **Environment awareness.** Development loads from the Vite dev server.
   Production reads the hashed manifest in `spectre-theme/dist/`.
4. **TypeScript only.** All client-side logic in `src/js/` uses `.ts` files.
   This also applies to `scripts/` tooling — never add a new `.js`/`.mjs`
   script; run via `node --experimental-strip-types scripts/<name>.ts`.
5. **PHP templates are structural.** PHP files handle WordPress integration,
   template hierarchy, and data access. They do not own visual decisions.
6. **Theme metadata integrity.** Keep `style.css` version-synced with
   `package.json` and `spectre-theme/readme.txt`.

## Reusable Starter Boundary

This is a reusable theme foundation, not a client site. The following are
prohibited in all source-controlled files regardless of instructions:

- Site names, company names, or brand identities in PHP templates or CSS
- Specific social media handles or icon presets hardcoded in templates
  (use the `spectre_base_footer_social_icons` filter instead)
- Client-specific page templates (`page-about.php`, `page-contact.php`, etc.)
- Plugin registration or activation logic
- Hardcoded external URLs other than WordPress or Spectre ecosystem references
- Color, font, or spacing values that override Spectre token defaults for a
  specific brand
- Gutenberg patterns or `theme.json` style entries encoding a site's brand

When a site-specific need arises, reach for a child theme, plugin, or
WordPress filter - not a modification to this base theme.

## Drift Prevention

Shared ownership of visual responsibility:

- **Spectre tokens** define values: color, type, spacing, radius, shadow,
  motion, breakpoints, and layout scales.
- **Spectre UI / components** define reusable styling contracts and component
  behavior.
- **This theme** defines only WordPress shell structure and composition needed
  to deliver Spectre through WordPress.

Allowed in PHP templates:
- WordPress template hierarchy, loops, conditionals, nav, metadata, and escaping
- Layout-only shell classes with no visual chrome of their own:
  `spectre-site-container`, `spectre-main`, `spectre-post-grid`
- Spectre web components for anything with visual chrome (background, border,
  radius, shadow, padding, hover/focus state): `<sp-card>`, `<sp-nav>`,
  `<sp-footer>`, `<sp-section>`, `<sp-button>`, `<sp-input>`, `<sp-badge>`,
  and any other `<sp-*>` element that already covers the need
- `sp-btn`/`sp-*` static class names from `@phcdevworks/spectre-ui` recipes
  (e.g. `getButtonClasses()`) only where a real `<sp-*>` element cannot be
  used structurally (e.g. an anchor styled as a button — `sp-button` only
  renders a `<button>`)

Avoid in PHP templates:
- Tailwind utilities for color, type, spacing, radius, shadow, or layout
- Hardcoded CSS values or arbitrary utilities (`text-white`, `rounded-*`,
  `shadow-*`, `p-*`, `px-*`, `py-*`, `gap-*`, `max-w-*`, `w-*`, `h-*`)
- A bare `<div>`/`<article>`/`<section>`/`<nav>`/`<footer>` styled via a
  custom `spectre-*` class when an `<sp-*>` component already provides that
  chrome — this is drift even when every value traces back to a token,
  because it duplicates a contract `spectre-components` already owns and
  will silently diverge the next time that component changes upstream
- Hand-built controls when an `<sp-*>` component exists

Allowed in `src/styles/main.css`:
- Imports for Tailwind, Spectre tokens, and Spectre UI
- Shell selectors mapping WordPress structure to Spectre token variables
- CSS values derived from `var(--sp-*)`

Avoid in `src/styles/main.css`:
- Hex, RGB/HSL/OKLCH, gradients, pixel/rem/em constants, or local design values
- New design-token definitions
- Component styling that belongs in `@phcdevworks/spectre-ui`

## Dependency Contracts

| Package | Role | How to consume |
|---|---|---|
| `@phcdevworks/spectre-tokens` | Design tokens | Import `index.css` for CSS vars |
| `@phcdevworks/spectre-ui` | Styles and recipes | Import `index.css` |
| `@phcdevworks/spectre-components` | Web components | `defineSpectreComponents()` in `main.ts`; `<sp-*>` in templates |

When any Spectre package updates, run `npm install`, rebuild, run
`check:drift`, and verify rendering.

## Working Boundaries

- This theme owns WordPress shell structure, asset delivery, and build tooling.
- Design token values belong in `@phcdevworks/spectre-tokens`.
- Component visual contracts belong in `@phcdevworks/spectre-ui` and
  `@phcdevworks/spectre-components`.
- WordPress core, plugin management, and hosting are out of scope.
- Client-specific identities, overrides, and branding belong in child themes
  or site plugins.

## Ecosystem Manifest

`spectre.manifest.json` at the root is this package's declaration in the Spectre
ecosystem contract, validated by `@phcdevworks/spectre-manifest`. It records role,
layer, exports, and allowed Spectre dependency targets. `check:ecosystem` validates
it as part of `npm run check`.

Keep `spectre.manifest.json` in sync when:
- Package exports in `package.json` are added or removed
- A Spectre package dependency is added or removed
- The package stability changes

Do not add a `consumers` field — that belongs in the central
`@phcdevworks/spectre-manifest` registry.
