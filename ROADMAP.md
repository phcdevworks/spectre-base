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
| 6 | WordPress content-flow contract — generic `sp-content-flow` wrapper around classic `the_content()` output; token-backed prose measure, `alignwide`/`alignfull` breakout, and `blockGap`-matching block spacing | Delivered (3.1.0) |
| 7 | Production layout dependency alignment — dependency ranges bumped to `spectre-tokens` `^4.5.0`, `spectre-ui` `^4.3.0`, `spectre-components` `^1.17.0`; child-theme docs updated to demonstrate generated layout utilities and the `inner-class` contract | Delivered (3.1.0) |
| 8 | Upstream contract corrections — dependency ranges confirmed at `spectre-tokens` `^4.7.0`, `spectre-ui` `^5.0.0`, `spectre-components` `^1.18.0`; removed the local container max-width override for the new `.sp-container--max-width-wide` utility; verified section-spacing layer precedence, inset-shadow utilities, on-dark/inverse role consumption, `sp-stack`/`sp-section` host display fixes, and the `sp-card` padding size scale all ship upstream with no local workaround left to remove | Delivered (3.1.0) |
| 9 | Heading size precedence — `functions.php` now post-processes the enqueued `global-styles` inline stylesheet to wrap it in `@layer wp-global-styles`, ordered below `components`/`utilities`, so an explicit `<sp-text level="h*" size="*">` recipe wins over the theme's unlayered `theme.json` heading defaults while raw editor-content headings keep the default scale | Delivered (3.1.0) |
| 10 | `sp-grid` ARIA row/cell semantics — resolution landed in `spectre-components@1.18.0`: `sp-grid` projects light-DOM children by moving the original nodes, so plain `role="row"`/`role="cell"` attributes on a consumer's own child markup already work with no component change; `spectre-components` added regression coverage confirming this rather than new API surface. Verifying whether a downstream comparison matrix can move onto `sp-grid` with this pattern remains that integration's own follow-up. | Closed (3.1.0), no theme change needed |
| 11 | Keyboard bypass navigation — every public template exposes one native main landmark, while the shared header provides a token-backed skip link verified for visibility and focus transfer in a live WordPress browser test | Delivered (3.2.0) |

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
