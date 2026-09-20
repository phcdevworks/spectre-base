# Spectre Base Theme Execution Todo

Phases 1 through 11 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

The project is in maintenance mode: dependency upkeep, drift/regression
prevention, and CI health. New scope opens only when a concrete need
emerges — e.g. richer interactivity via editor blocks backed by Spectre
web components (static block markup, progressively enhanced via
`defineSpectreComponents()`), not a client-side router or SPA shell.

## Requested by Downstream

### 2026-09-16 — Isolate global heading styles from explicit Spectre text sizing

A production child theme has demonstrated that the CMS-generated global heading
rules can outrank Spectre's layered text recipes. When a Spectre text component
renders a native heading and explicitly requests a size, the native heading can
still inherit the shell's global `h1`–`h6` font size, line height, and weight.
The child theme then has to restate typography against the component's native
node, which is delivery-layer compensation that belongs here instead.

Implementation instructions:

- Reproduce the collision in the reusable theme with a Spectre text component
  that renders a native heading while requesting a size different from the
  shell's default level-based heading size.
- Preserve normal global heading defaults for ordinary CMS content. The fix must
  be scoped so semantic `h1`–`h6` elements outside an explicit Spectre text
  contract keep the theme's intended typography.
- Ensure an explicit Spectre text size/variant contract wins without requiring a
  child theme to target `[data-sp-text-native]`, `.sp-text`, or another rendered
  implementation detail.
- Prefer a shell-level cascade/integration fix over duplicating Spectre text
  recipe values locally. Do not redefine upstream design values or create local
  typography tokens.
- Add a regression check that fails if global theme output again overrides an
  explicitly sized Spectre heading. Extend the existing accessibility/style
  validation tooling if practical rather than adding an unrelated test stack.
- Update `CHANGELOG.md [Unreleased]` and any affected theme-contract docs when
  behavior changes.
- Run `npm run check` before removing this item. Remove the TODO only after all
  acceptance criteria and validation pass per `CLAUDE.md`.

Acceptance criteria:

- Explicit Spectre heading size/variant choices render consistently in the
  reusable theme without downstream typography overrides.
- Ordinary semantic headings that do not opt into a Spectre text contract retain
  the shell's level-based defaults.
- The solution consumes upstream Spectre contracts and introduces no local visual
  values.

## Explicitly Out of Scope

- Do not redefine token values or local design values — consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic (belongs in plugin repos like `spectre-icons`)
- Do not add e-commerce templates without proven product need
- Do not add page builder integration or compatibility work
- Do not add a client-side router or SPA shell — this theme is
  server-rendered WordPress; richer interactivity belongs in editor
  blocks backed by Spectre web components, not client-side routing
- Do not add client-specific branding, hardcoded visual values, or local
  token definitions
