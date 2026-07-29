# Spectre Base Theme Execution Todo

No active work is currently open in this package. Phases 1 through 3 are
delivered and Phase 4 (Spectre Shell router evaluation) is closed, not
adopted — see [ROADMAP.md](ROADMAP.md) for the full delivery history and
[CHANGELOG.md](CHANGELOG.md) for release-by-release detail.

The project is in maintenance mode: dependency upkeep, drift/regression
prevention, and CI health. New scope opens only when a concrete need
emerges — e.g. richer interactivity via Gutenberg blocks backed by Spectre
Lit components (static block markup, progressively enhanced via
`defineSpectreComponents()`), not a client-side router or SPA shell.

When a new phase opens, add it here with the same P0/P1/P2 structure the
completed phases used in `ROADMAP.md`.

## Explicitly Out of Scope

- Do not redefine token values or local design values — consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`
- Do not add PHP plugin logic (belongs in plugin repos like `spectre-icons`)
- Do not add WooCommerce templates without proven product need
- Do not add page builder (Elementor, Beaver Builder, etc.) integration or
  compatibility work
- Do not add a client-side router or SPA shell — this theme is
  server-rendered WordPress; richer interactivity belongs in Gutenberg
  blocks backed by Spectre Lit components, not client-side routing
- Do not add client-specific branding, hardcoded visual values, or local
  token definitions
