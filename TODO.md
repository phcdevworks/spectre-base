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

Filed from a downstream child theme on 2026-08-07 while building
`single-press_release.php`. Each is a gap a site layer currently has to fill
with its own CSS, which is the drift this ecosystem exists to prevent.

Re-audited 2026-08-19 against `spectre-tokens@4.4.0` / `spectre-ui@4.1.1` /
`spectre-components@1.16.0`. Verification commands ran against the built
bundle at `spectre-theme/dist/css/*.css` and against the installed
`node_modules/@phcdevworks/*/dist/` type declarations.

This pass is intended to be exhaustive: every local visual rule the
downstream child currently carries was checked against the three upstream
packages, so the list below should not need topping up one item at a time.

Every item carries a **name** in `Area — Title Case Name` form, and the name is
the stable handle. Upstream repos cite these by name in source comments,
changelog entries, and their own `TODO.md` files — `spectre-ui`'s
`recipes/grid.ts` cites *Grid — Explicit Asymmetric Column Templates* that way.
Rename an item and you break every reference to it, so treat the name as the
contract and revise the body underneath it instead.

### Closed

- **Build — Utility Layer Purge In Shipped CSS.** P0. NOT REPRODUCIBLE; closed.
  The original measurement grepped
  `spectre-theme/dist/assets/*.css`, but `vite.config.ts` routes stylesheets to
  `dist/css/` via `assetFileNames` — so the reported `grep -c col-span` of
  **0** was a hit count against a path that holds no CSS, not evidence of a
  purge. Current counts in the real bundle: `col-span` 39, `col-offset` 33,
  `row-span` 39, `sp-order-` 15, `sp-text--uppercase` 1. The build is not
  tree-shaking `spectre-ui`'s `@layer utilities`. Any downstream workaround
  that writes out `grid-column` because "the utility does not ship" can be
  replaced with the utility class — but verify against `dist/css/`, not
  `dist/assets/`.
- **Footer — Independent Surface Token.** Closed by `spectre-tokens@4.3.0`.
  Phase 11 publishes an independent `--sp-footer-*` contract (`bg`, `text`, `heading`,
  `muted`, `link`, `link-hover`, `border`, `divider`, `chip-bg`, with dark-mode
  values), and `spectre-ui@4.0.0` rebinds every `--sp-component-footer-*` role
  onto it instead of onto the Nav roles. `--sp-component-footer-bg` now
  resolves to `--sp-footer-bg` (`#141b24`), not to white `--sp-nav-bg`, so a
  dark footer no longer needs a local surface value. `spectre-ui@4.0.0` also
  adds the footer anatomy recipes — `.sp-footer__heading`, `__text`,
  `__muted`, `__links`, `__link` (+ `--active` / `--hover` / `--focus` /
  `--disabled`), `__divider`, `__chip` — so the heading, link, chip and
  divider vocabulary a site layer used to rebuild is now upstream. The chip
  sizes its hit area from `--sp-min-touch-target`, which is stricter than the
  2rem the child theme was using.
- **Grid — Explicit Asymmetric Column Templates.** Closed by
  `spectre-ui@4.1.0` + `spectre-components@1.16.0`. `getGridClasses` gained
  `explicitTemplate`, and `<sp-grid>` now exposes it as `explicit-template`
  (a JSON object attribute, `{"template":"…","weight":…}`).
  `.sp-grid-template--edge-fluid-edge` is `auto 1fr auto`, which is exactly the
  logo/nav/CTA bar the child had written locally as its own nav-grid rule.
  `getGridClasses` suppresses `sp-grid-cols-*` when a template is set
  (`!templateClass && \`sp-grid-cols-${columns}\``), so `columns` comes off the
  host at the same time. See the responsive caveat under *Still open*.
- **Grid — Offset Order And Row Control On The Component.** Closed by
  `spectre-components@1.16.0`. The component went from
  `columns`/`gap`/`span`/`inner-class` to also expose `offset`, `order`,
  `row-span`, `row-offset`, `column-gap`, `row-gap`, `fixed-tracks`, and
  `leading-tracks`. The recipe layer already had all of these; only the
  component binding was missing.
- **Button — Sub Touch Target Compact Variant.** Closed by `sp-button`'s
  `compact` prop. `.sp-btn`'s `min-height:
  var(--sp-min-touch-target)` (44px) has an upstream escape after all:
  `.sp-btn--compact` sets `min-height: 0` and restores the 44px hit area
  through an `::after` overlay. That is strictly better than the local
  override the child uses for the utility-bar chip, which shrinks the real box
  to 28px and trades WCAG 2.5.5 (AAA) for 2.5.8 (AA). The child can adopt
  `compact` and keep both.
- **Utilities — Standalone Weight Tracking Object Fit And Aspect Ratio.**
  Closed by `spectre-ui@4.1.0` and `spectre-tokens@4.4.0`. `sp-font-{400..900}`,
  `sp-tracking-{tightest..widest}` (backed by the new `--sp-tracking-*` scale),
  `sp-object-{contain,cover,fill,none,scale-down}` plus positions, and
  `sp-aspect-{auto,square,video,portrait,landscape,classic,hero,ultrawide}`
  are all present in the built bundle. These close three local rules in the
  child: a square portrait crop, and two `font-weight` overrides read off the
  type scale.

### Still open

- **Layout — Spacing Utility Override Of Layout Primitives.** P0. In the
  shipped `spectre-ui/dist/index.css` the utilities are emitted across two
  separate `@layer utilities` blocks: as of 4.1.1 `.sp-gap-40` lands at 6161
  and `.sp-stack` / `.sp-grid--gap-*` at 20889 / 20940. Same layer, same `(0,1,0)`
  specificity, so the primitive wins on source order and
  `class="sp-stack sp-gap-40"` silently does nothing. The escape hatch the
  utility scale exists to provide is unreachable on exactly the two components
  that most need it. Consumers are then forced into either local
  `> .sp-stack { gap }` CSS — the downstream child does this in nine places —
  or into dropping the web component for bare recipe classes, because
  `.sp-grid` alone declares no gap while `<sp-grid>` always injects
  `.sp-grid--gap-*`. Emitting the layout primitives before the spacing
  utilities, or moving the primitives into `@layer components`, fixes every one
  of those.
- **Stack — Gap Prop Parity With Grid.** Related to the above but separate: even with
  the ordering fixed, `sp-stack` is hardcoded to `--sp-layout-stack-gap-md` and
  the only way to change it is a class the component does not accept. The
  tokens already exist; a `gap` prop mirroring `sp-grid`'s would close it.
  Re-verified against `1.16.0`: the props are still `align`, `basis`,
  `direction`, `innerClass`. `sp-grid` gained eight new options in that release
  and `sp-stack` gained none, so the two layout primitives have drifted further
  apart, not closer.
- **Grid — Responsive Explicit Template Variants.** P0. Every other grid
  sizing option ships `md`/`lg` steps — `sp-grid-cols-*` collapses to one
  column below 768px, and span, offset, order and leading-track weight all have
  `sp-md-`/`sp-lg-` forms. The six template classes have none: `grep -cE
  "sp-(md|lg)-grid-template"` is **0**. So adopting `explicit-template` means
  accepting that template at every width, including 375px. For the
  logo/nav/CTA bar that is the difference between a nav that stacks on a phone
  and one that crams three tracks onto a 375px row, which is why the
  downstream child cannot yet drop its local nav-grid rule outright.
  `sp-md-grid-template--*` / `sp-lg-grid-template--*` would close it and make
  the option consistent with the rest of the Grid contract.
- **Inner Class — Recipe Class Acceptance.** The
  sanitizer is `/^sp-[a-z0-9]+(?:-[a-z0-9]+)*$/`, which requires an
  alphanumeric after each hyphen — so any BEM modifier fails. `sp-items-center`
  passes; `sp-grid-template--edge-fluid-edge`, `sp-dropdown__menu--mega` and
  `sp-btn--primary` are all silently dropped. `inner-class` is documented as
  the sanctioned handle on the inner element, but in practice it admits only
  the flat utility scale and none of the component vocabulary. Silent is the
  worst part: a consumer gets no console warning and no failed build, just a
  class that never appears. Either widen the pattern to accept `--` and `__`
  segments, or warn in development when a token is discarded.
- **Inner Class — Button And Card Coverage.** Only `sp-grid`,
  `sp-stack`, `sp-section`, `sp-container`, `sp-nav` and `sp-footer` expose it.
  Both omissions cost the child real CSS: brand paint on a CTA has to be
  written as `.local-cta-action .sp-btn { … }`, reaching through the host to an
  element markup cannot address, and the same pattern repeats for the footer
  and utility-bar link scoping. A descendant selector into another package's
  rendered output is exactly the coupling `inner-class` exists to avoid.
- **Host — Custom Element Display Contract.** `defineSpectreComponents()`
  registers every element without one, and there is no `sp-card`/`sp-button`
  host selector anywhere in `index.css`. So the hosts are inline boxes, and two
  documented props do not resolve: `sp-card`'s `full-height` sets
  `height: 100%` on its inner div against a host with no definite height, and
  `.sp-btn--full`'s `width: 100%` resolves against a shrink-wrapped inline
  host. The child fixes this with `display: block` in three places. A single
  `:where(sp-card, sp-button, …) { display: block }` in the base layer would
  make both props behave as documented everywhere.
- **Card — Unpadded Media Slot.** The component layer's boolean attribute conversion reads any present
  attribute as `true`, and the property defaults to `true` when the attribute
  is absent, so markup has no way to opt out.
  Full-bleed card media — a press-release image meeting the card edge, a
  portrait crop — is then only reachable by cancelling the padding with
  negative margins. The child maintains a local bleed / bleed-radius custom
  property pair for this, bound to `--sp-space-32` and `--sp-radius-lg` minus the border
  width, used across five rules. Any retune of those tokens silently breaks the
  seal. Either make `padded` falsifiable or publish a `.sp-card__media`
  bleed slot.
- **Shell — Nav And Footer Container Seam.** Both ship
  `padding: var(--sp-space-12) var(--sp-space-16)` and both are flex
  containers. The flex part is the real problem: a nested `sp-container`
  becomes a flex item and shrink-wraps to its content instead of reaching its
  `max-width`, so a nav's own grid gets no free space to distribute and its
  content bunches left. The inline padding then adds an inset that a sibling
  plain-block band does not have, so a utility bar, a nav and a footer that all
  wrap content in `sp-container` cannot share a left edge. The child corrects
  both together with `padding-inline: 0` and `width: 100%`. This is the
  "missing parent seam" behind the child's own `header.php`/`footer.php` fork.
- **Grid — Fluid Plus Equal Fixed Tracks Template.** A comparison table wants
  `minmax(0, 1fr) repeat(2, <fixed>)` — one fluid label column and N equal
  fixed columns, so the things being compared are measured in identical space.
  Neither new option fits: `fixedTracks` makes *every* track fixed at
  `--sp-space-240`, and `label-fluid-fluid` is `auto Nfr 1fr`, which is two
  fluid columns of deliberately different weight. The child writes the template
  by hand and names the width locally as a `10rem` custom property, a length
  with no token behind it because the scale has no step for a
  comparison-column measure.
- **Shadow — Inset Band Tokens.** All six `--sp-shadow-*` values are outer drop
  shadows. A section band that needs to feather at its top and bottom edges has
  to compose its own `inset` pair, which is what the child's hero band does —
  matching offset, blur and negative spread so each shadow stays confined to
  its own edge. An `--sp-shadow-inset-*` step or a `.sp-band` recipe would
  close it.
- **Grid — Cell Alignment And Column Start.** Partially closed. Still no
  `align` prop on the component and no `.sp-grid--align-*` recipe, but
  `.sp-items-start` ships and covers the article-plus-sidebar case that was
  forcing local `align-items: start` to keep `position: sticky` alive in the
  rail. Offsets are closed too: `spectre-ui@3.4.0` added `.sp-col-offset-*`
  (verified present in the bundle), so centring an eight-column article no
  longer needs local `grid-column`. What remains genuinely absent is
  `.sp-col-start-*` and an `align` prop on `<sp-grid>` itself.
- **Prose — Editor Content Recipe.** The build's CSS reset zeroes
  margins and list markers, and `spectre-ui`'s base layer restores neither — it
  styles components, not the raw HTML the WordPress editor emits. Heading and
  link type is already covered, by `spectre-theme/theme.json`'s
  `styles.elements`, and that is the right pattern; what has no home is
  `ul`/`ol` markers, `blockquote`, and top-level flow spacing, none of which
  `theme.json` can express for a classic theme calling `the_content()`
  directly. A `.sp-prose` recipe would close it for every site on this shell.
  The downstream child carries a local prose stopgap.
  **`spectre-ui@4.1.0` made this worse, not better.** It added a global
  `ul, ol { margin: 0; padding: 0; list-style: none }` to `base.css`, so
  markerless editor lists are now an upstream decision rather than a side
  effect of the build's CSS reset — and the release note says so, calling it a
  visible behavior change that consumers must opt back out of. There is still
  no recipe to opt back in with, which means the one package that took a
  position on bare `ul`/`ol` is also the one offering no way to reverse it.
  Raising the priority: this is the only item on this list that upstream is
  actively moving away from.
- **Grid — Component Gap Ceiling.** `--sp-layout-stack-gap-lg` is
  fine between cards and too tight for a page-level gutter between independent
  columns. Reachable today only via the bare-class workaround above.
  Re-verified in 4.1.1: `GRID_GAPS` is still `{sm, md, lg}` and
  `--sp-layout-stack-gap-lg` is still `1rem`, while the standalone `sp-gap-*`
  scale runs far past it — so the ceiling is on the component contract, not on
  the tokens.
- **Dropdown — Mega Panel Geometry Recipe.** Closed, kept here because the
  downstream has not adopted it yet. `spectre-ui@4.0.0` adds `.sp-dropdown--mega` /
  `.sp-dropdown__menu--mega`, which anchor to the nearest positioned ancestor
  (`sp-nav` is now a positioning context by default) and span its full width,
  capped at `70vh` and scrollable — replacing the `position: static` +
  `left/right: 0` unpicking a site layer had to do to escape trigger-width
  sizing. Pair it with `.sp-grid-fixed-tracks-{1..4}`, whose track width moved
  to `--sp-space-240` (15rem) in the same release. That is the exact figure the
  downstream child had reached independently as its own mega-column width, so its
  four local mega-panel column templates are now a direct swap.
  **Correction on the downstream state:** the child no longer has those four
  templates, or any local mega-panel rules at all — the mega-column width is not
  declared anywhere in its `style.css`. `functions/mega-menu.php` still prints
  its mega-panel column class and a `sp-col-span-full` foot onto a panel div that
  declares no grid, so the panel columns currently stack. That is a child-side
  regression to fix by adopting the recipes above, not an upstream gap.

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
