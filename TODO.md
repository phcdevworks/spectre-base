# Spectre Base Theme Execution Todo

Phases 1 through 7 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

The project is in maintenance mode: dependency upkeep, drift/regression
prevention, and CI health. New scope opens only when a concrete need
emerges — e.g. richer interactivity via editor blocks backed by Spectre
web components (static block markup, progressively enhanced via
`defineSpectreComponents()`), not a client-side router or SPA shell.

## Upstream Requests

### Section — Spacing Utility Override Of Section Padding

Move `.sp-section` from `@layer utilities` to `@layer components` in
`@phcdevworks/spectre-ui`, so the standalone `sp-py-*` / `sp-pt-*` / `sp-pb-*`
utility scale wins by layer precedence and `<sp-section inner-class="sp-py-64">`
does what it reads as.

This is the same fix `spectre-ui@4.2.0` already applied to `.sp-stack`,
`.sp-hstack` and `.sp-grid--gap-*` under "Layout — Spacing Utility Override Of
Layout Primitives". `.sp-section` was not included, and it has the identical
defect for the identical reason.

**Current behaviour.** In `dist/index.css` both rules land in `@layer
utilities` at equal specificity, and `.sp-section` is emitted later:

| Selector | Index | Layer |
|---|---|---|
| `.sp-py-64` | 158673 | `utilities` |
| `.sp-stack` | 418283 | `components` (already moved) |
| `.sp-section` | 421840 | `utilities` |

So source order decides and `.sp-section`'s
`--sp-layout-section-padding-md` always wins. `inner-class="sp-py-64"` is
accepted by the element, survives `sanitizeUtilityClasses`, renders onto the
native `<section>` — and then silently does nothing. The escape hatch is
unreachable on the one layout primitive whose only styling *is* the padding.

**Why there is no workaround at the consuming end.** `getSectionClasses()`
takes an empty `SectionRecipeOptions`, `<sp-section>` authors only
`innerClass`, and a consumer cannot redeclare `--sp-layout-section-padding-md`
— the token namespace is a read-only upstream contract, and both this repo and
its child themes fail their validation gate on any `--sp-*` declaration. The
remaining options are all worse than the bug: abandon the component for a plain
`<section>` plus utilities, or ship an `!important` override. A downstream child
theme reached for both before this was diagnosed.

**Acceptance.** `<sp-section inner-class="sp-py-64">` renders 4rem of block
padding with no `!important` and no local CSS; sections with no `inner-class`
are unchanged at `--sp-layout-section-padding-md`.

**Secondary, same area.** `sp-section` is also absent from the host
`display: block` list `spectre-ui@4.2.0` added under "Host — Custom Element
Display Contract". That list is scoped to elements with a reflected
`full-width`/`full-height` attribute, and `sp-section` has neither, so its host
still falls back to the UA default `inline` — a background or box-shadow set on
the host paints around line boxes instead of filling the block. Any consumer
putting a banded header on an `<sp-section>` hits it.

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
