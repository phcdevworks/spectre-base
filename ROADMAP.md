# Spectre Base Theme Roadmap

`@phcdevworks/spectre-base` is the WordPress theme delivery layer of the
Spectre system. It provides a Vite-powered build pipeline, TypeScript entry
point, Tailwind CSS 4 integration, and a standard `spectre-theme` WordPress
directory that consumes `@phcdevworks/spectre-tokens` and
`@phcdevworks/spectre-ui` as upstream contracts.

Its job is to deliver Spectre through WordPress cleanly and without redefining
design-system contracts locally — not to own component structure, token
values, or plugin behavior.

This document tracks what's next. For what already shipped and why, see
[CHANGELOG.md](CHANGELOG.md) (release-by-release detail) and git history —
this file does not restate delivered work.

---

## Delivered Phases

| Phase | Summary | Status |
| --- | --- | --- |
| 1 | Foundation — Vite/TypeScript/Tailwind build pipeline, token/UI consumption, asset contract and drift checks, full WP template hierarchy, block editor support, Spectre Icons integration, CI | Delivered |
| 2 | WordPress.org readiness and agency DX — renamed to `spectre-base`, child theme generator, submission compliance pass, screenshot tooling | Delivered |
| 3 | Parent theme API — PHP hook API, `--sp-*` CSS namespace documentation, Child Themes README section | Delivered |
| 4 | Shell compatibility — Spectre Shell router evaluated for WordPress SPA-style navigation | Closed, not adopted |
| 5 | Spectre text contracts — templates converted onto `sp-text`; tokens bumped to `^4.0.0`, `spectre-ui` to `^3.0.0`, `spectre-components` to `^1.11.0` | Delivered (3.0.0) |
| 6 | WordPress content-flow contract — generic `sp-content-flow` wrapper around classic `the_content()` output; token-backed prose measure, `alignwide`/`alignfull` breakout, and `blockGap`-matching block spacing | Delivered |
| 7 | Production layout dependency alignment — dependency ranges bumped to `spectre-tokens` `^4.5.0`, `spectre-ui` `^4.3.0`, `spectre-components` `^1.17.0`; child-theme docs updated to demonstrate generated layout utilities and the `inner-class` contract | Delivered |

---

## What's Next

Nothing planned — the project is in maintenance mode. New scope opens only
when a concrete need emerges; see [TODO.md](TODO.md).

---

## Explicitly Out of Scope

- Token values or CSS redefined locally — consume from
  `@phcdevworks/spectre-tokens` and `@phcdevworks/spectre-ui`.
- PHP plugin logic — belongs in separate plugin repositories like
  `spectre-icons`.
- WooCommerce templates unless a proven product need emerges.
- Component structure or composition — belongs in `@phcdevworks/spectre-ui`.
- Client-specific identities, overrides, or branding in the base theme.
- A client-side router or SPA shell — this theme is server-rendered
  WordPress; richer interactivity belongs in Gutenberg blocks backed by
  Spectre Lit components, not client-side routing.
