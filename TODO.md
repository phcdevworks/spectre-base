# Spectre Base Theme Execution Todo

Phases 1 through 17 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

## Requested by Downstream

- **Prose blockquote rule leaks into `sp-quote`.** `.sp-prose blockquote`
  (utilities layer) matches the `blockquote.sp-quote__text` that `sp-quote`
  renders, so an `sp-quote` inside prose gets the prose rail and text colour
  on top of its own accent (two bars). Scope the prose rule to skip it, e.g.
  `.sp-prose blockquote:not(.sp-quote__text)`. The child theme carries a
  temporary `.sp-prose .sp-quote__text` reset until this ships.
- **Browser autofill hides `sp-input` states.** When Chrome autofills a
  field (`:autofill` / `:-webkit-autofill`), its own light-blue fill paints
  over `.sp-input--success` and `.sp-input--error`, so on a lead form the
  autofilled name fields stay blue while every typed field turns green. The
  state classes resolve correctly; only the paint is lost. `sp-input` needs
  autofill handling that keeps the state colours, e.g. an inset box-shadow
  in `--sp-component-input-role-bg-*` plus a long `background-color`
  transition on `:autofill`, and the matching border colour. A downstream
  child theme carries no workaround (it would mean styling component
  internals).
