# Spectre Base Theme Execution Todo

Phases 1 through 5 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

## Phase 6: WordPress Content-Flow Contract

Requested by the downstream integration child theme on 2026-08-07. Classic
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
