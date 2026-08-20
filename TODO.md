# Spectre Base Theme Execution Todo

Phases 1 through 5 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

The project is in maintenance mode: dependency upkeep, drift/regression
prevention, and CI health. New scope opens only when a concrete need
emerges — e.g. richer interactivity via editor blocks backed by Spectre
web components (static block markup, progressively enhanced via
`defineSpectreComponents()`), not a client-side router or SPA shell.

## Phase 6: WordPress Content-Flow Contract

Requested by a downstream child-theme integration on 2026-08-07. Classic
`the_content()` output currently lacks a reusable top-level flow/alignment
contract, forcing the child theme to rebuild container width, inline padding,
block spacing, and `.alignfull` behavior.

- [ ] Add a generic parent-theme content wrapper around page/front-page/single
      `the_content()` output without introducing client-specific markup.
- [ ] Provide token-backed default content measure and inline padding, a
      full-width breakout contract for `.alignfull`, and top-level block flow
      spacing equivalent to `theme.json` `blockGap` when WordPress does not emit
      `.is-layout-flow` on the classic wrapper.
- [ ] Add PHP/template and drift-check coverage for normal, wide, and full
      alignment behavior; confirm nested Gutenberg layout blocks retain Core's
      own spacing rules.
- [ ] Update public child-theme documentation and `CHANGELOG.md`, then run
      `npm run check`.

## Phase 7: Production Layout Dependency Alignment

Gated on npm publication of `spectre-ui` 3.3.0 and the subsequent
`spectre-components` layout-forwarding release.

- [ ] Bump and lock the published Spectre dependency ranges, rebuild assets,
      and run the full drift/WordPress validation gate.
- [ ] Update the child-theme generator/documentation to demonstrate the new
      layout utilities and inner-element class contract instead of descendant
      CSS overrides.

## Requested by Downstream

Kept visible and separate from the self-planned phases above, per this
repo's own `AGENTS.md` § "Upstream Requests and Roadmap Self-Expansion."
None of these are this repo's own work — each was filed upstream, under the
same `## Requested by Downstream` heading, in the repo that owns the fix.
This is an index only; remove a row once its owning repo marks the item
closed.

Audited 2026-08-07, re-audited 2026-08-19, against a downstream child-theme
integration's local CSS/component overrides.

- Layout — Spacing Utility Override Of Layout Primitives —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Grid — Responsive Explicit Template Variants —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Host — Custom Element Display Contract —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Shell — Nav And Footer Container Seam —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Grid — Fluid Plus Equal Fixed Tracks Template —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Grid — Cell Alignment And Column Start —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Prose — Editor Content Recipe —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Grid — Component Gap Ceiling —
  [spectre-ui/TODO.md](../spectre-ui/TODO.md)
- Stack — Gap Prop Parity With Grid —
  [spectre-components/TODO.md](../spectre-components/TODO.md)
- Inner Class — Recipe Class Acceptance —
  [spectre-components/TODO.md](../spectre-components/TODO.md)
- Inner Class — Button And Card Coverage —
  [spectre-components/TODO.md](../spectre-components/TODO.md)
- Card — Unpadded Media Slot —
  [spectre-components/TODO.md](../spectre-components/TODO.md)

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
