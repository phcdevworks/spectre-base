# Spectre Base Theme Execution Todo

Phases 1 through 13 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

Active development continues: dependency upkeep, drift/regression
prevention, CI health, and new scope planned proactively — e.g. richer
interactivity via editor blocks backed by Spectre web components (static
block markup, progressively enhanced via `defineSpectreComponents()`), not
a client-side router or SPA shell.

### 2026-09-24 — Footer — Public Background Surface Option

A production child theme runs its footer on a brand surface rather than
Spectre's footer background. `sp-footer` exposes no public option for that, so
the child paints the rendered `.sp-footer` element directly. The shell now
stretches the footer and nav containers correctly; only the public surface
option remains unresolved.

Acceptance criteria:

- A downstream site can put `sp-footer` on its own surface through a public
  attribute, variant or documented custom property, without selecting
  `.sp-footer` or `[data-sp-footer-native]`.
- Route the component-level half to `@phcdevworks/spectre-components` /
  `@phcdevworks/spectre-ui` if it cannot be solved in the shell.

### 2026-09-24 — Utilities — Typography, Surface And Responsive Grid Gaps

The same child theme moved its layout and spacing onto shipped `sp-*` utilities.
A handful of declarations remain local because no utility expresses them, and
each is a reusable need rather than a site one:

- `text-wrap: balance` for card headlines that would otherwise end on an orphan
  word.
- `font-variant-numeric: tabular-nums` for a figure that repaints in place (a
  countdown), so it stops jittering between ticks.
- A background utility bound to a surface role — `--sp-surface-subtle` in
  particular. The `sp-bg-*` utilities are bound to palette steps only.
- A thick border utility (`--sp-border-width-thick`) that can take a role
  colour. `sp-border-*` carries the hairline width and divider colour only.
- Responsive grid-column steps (for example `sp-md-grid-cols-2`). `sp-grid-cols-*`
  has no breakpoint variants, so a two-column list at the md breakpoint needs a
  local media query.

Acceptance criteria:

- Each capability is either shipped upstream as a documented utility or
  explicitly declined, with the reason recorded here.
- No local visual values are introduced in this repo to satisfy it.

### 2026-09-24 — Shell — Header And Footer Extension Seams

A production child theme still forks `header.php` because the shell cannot
express its three-region header. Recent shell work added the reusable footer
brand, menu-column, contact and legal-link contracts, removing most reasons to
fork `footer.php`. A template fork stops receiving shell fixes silently: the
child's header had already lost the shell's skip link, and its template set did
not open a `<main>` landmark consistently. Each remaining item below is a seam
that lets the child delete more of the forked structure.

Header (`header.php`):

- **Branding override.** The shell prints `the_custom_logo()` or the site name
  between `spectre_base_before_site_branding` and `…_after_site_branding`, with
  no way to replace it. The child needs its own bundled logo there. Needs a
  filter on the branding markup, or a documented custom-logo path that renders
  a sized image without a width override.
- **Primary nav render.** Only `spectre_base_primary_nav_args` is exposed, so a
  child can only shape `wp_nav_menu()`. The child renders top-level items
  itself so a panelled item becomes an `<sp-dropdown mega>`. Needs an action
  or filter that replaces the nav region's contents.
- **Header actions slot.** No hook after the primary nav, so a CTA button
  ("Book a demo") has nowhere to go. Needs `spectre_base_header_actions`, or
  equivalent, inside the nav bar.
- **Nav bar layout and anchor.** The shell's bar is a horizontal `sp-stack`.
  With three regions (branding, nav, actions) the child needs the
  `edge-fluid-edge` grid, and mega panels need the container to be the
  positioned ancestor (`sp-container inner-class="sp-relative"`). Needs the
  shell to own that layout, or to expose a filter for the `sp-nav`
  classes/attributes and the container `inner-class`.
- **Main landmark.** `<main id="spectre-main-content">` lives in each shell
  template, not in the header, so a child that renders content on
  `spectre_base_after_header` (a page hero) cannot place it inside the
  landmark without opening `<main>` itself. Needs the landmark opened in
  `header.php` and closed in `footer.php`, or an equivalent documented
  contract.

Footer (`footer.php`):

- **Social icons without the shortcode gate.** `spectre_base_has_icons()` gates
  the social row on a `[spectre-icon]` shortcode nothing registers, so the
  `spectre_base_footer_social_icons` filter renders nothing on a stock install.
  Needs an inline-SVG fallback or a documented icon provider.
- **Footer surface.** See the "Footer — Public Background Surface Option"
  request above.

Acceptance criteria:

- Each seam exists in the shell, with the rest of the shell's markup unchanged
  for sites that do not use it.
- The shell's skip link and `<main>` landmark survive in any child that uses
  the seams without forking.
- A child theme can drop its remaining header/footer template forks using only
  these seams and the shell's existing footer filters.

### 2026-10-01 — Section — Hero Variant With Its Own Spacing Scale

A production child theme builds every hero as a plain `sp-section` with a
local hero class, so its heroes take body-section spacing. `sp-section`
`spacing` (`sm | md | lg`) is deliberately symmetric, and that constraint is
right for body sections. A hero is a different kind of band, though. Its top
edge sits under the site header rather than another section, it needs more room
than `lg` (48px) gives, and its bottom edge is the handoff into the page body.
None of that can be expressed without downstream padding overrides, which is
the pattern that previously made the gaps between sections uneven.

Proposed shape (route to the owning packages; the shell only consumes it):

- **spectre-tokens:** hero padding tokens as fixed top/bottom pairs per size,
  e.g. `--sp-layout-hero-padding-top-{sm,md,lg}` and
  `--sp-layout-hero-padding-bottom-{sm,md,lg}`. Top and bottom may differ, but
  only as the token pairs define them.
- **spectre-ui:** a `.sp-section--hero` recipe (or `.sp-hero`) bound to those
  tokens, in the same layer as the existing section spacing recipes.
- **spectre-components:** `variant="hero"` on `sp-section` (preferred, since it
  reuses `inner-class`, host display and the existing section tests), or a
  dedicated `<sp-hero>`. Size is chosen by attribute and never by pixel value.

Acceptance criteria:

- A downstream hero can use a hero-specific spacing scale through a public
  attribute alone, with no `sp-pt-*`/`sp-pb-*`/`sp-py-*` override and no
  selector targeting the rendered `<section>`.
- Ordinary `sp-section` spacing stays symmetric and unchanged.
- The shell documents the hero variant in its theme contract once it is
  consumable, and any shell template that renders a hero adopts it.

### 2026-10-01 — Section — Larger, Responsive Spacing Scale

A production child theme now sets `spacing="lg"` on every `sp-section`, and
that is the largest step Spectre offers: 48px top and bottom at every
viewport width. That suits application and content-dense layouts. A marketing
site needs a wider range, though. Common practice is roughly 48–64px between
sections on mobile and 80–128px on desktop. Two gaps keep the system from
expressing that:

- **The scale tops out too low.** `sm | md | lg` is 24 / 32 / 48px, so a site
  that wants more room has no public step to reach for.
- **The steps are the same at every width.** Padding that reads well on a phone
  reads cramped on a wide desktop, and the reverse. Section padding should scale
  with the viewport.

Proposed shape (route to the owning packages; the shell only consumes it):

- **spectre-tokens:** add four larger section padding steps, `xl`, `2xl`,
  `3xl` and `4xl`, as responsive values (fluid `clamp()` or breakpoint steps).
  The top two are deliberate headroom for edge cases (landing pages, campaign
  heroes, very wide displays), so the scale is not outgrown again. A starting
  proposal for the owner to tune, mobile → desktop:

  | Step  | Mobile | Desktop |
  | ----- | ------ | ------- |
  | `xl`  | 64px   | 96px    |
  | `2xl` | 80px   | 128px   |
  | `3xl` | 96px   | 160px   |
  | `4xl` | 128px  | 192px   |

  Decide whether the existing `sm | md | lg` steps should also become
  responsive or stay fixed for dense layouts.

- **spectre-ui:** `.sp-section--spacing-xl` through `--spacing-4xl` recipes,
  in the same layer as the existing section spacing recipes, so the `sp-pt-*` /
  `sp-pb-*` / `sp-py-*` layer-precedence contract is unchanged.
- **spectre-components:** extend `SpectreSpacingStep` /
  `spectreSectionSpacings` for `sp-section` `spacing` to accept the new steps.

Coordinate with the hero variant item above. Hero padding should draw on the
same responsive scale rather than define a parallel one.

Acceptance criteria:

- A downstream site can set larger, viewport-responsive section padding through
  the public `spacing` attribute alone, with no padding utility override and no
  selector targeting the rendered `<section>`.
- Existing `spacing` values keep their current output unless the owner decides
  otherwise, and that decision is recorded in the upstream changelog.
- Section padding stays symmetric (top equals bottom) for regular sections.
- The shell documents the new steps in its theme contract once they are
  consumable.

### 2026-10-01 — Layout — Larger, Responsive Gap Scale

The same child theme reads tight and cluttered for a marketing site, and the
cause is the gap scale rather than any one template. It already uses the
largest gap step almost everywhere: `gap="lg"` appears 58 times. The steps
stop well short of marketing-site spacing:

- **`sp-stack` `gap`** tops out at `lg`, which is `--sp-layout-stack-gap-lg`
  (16px). That is the space between a section's head block and its content
  (heading → cards, heading → list), and it is where the clutter is most
  visible. Marketing layouts typically run 32–64px there.
- **`sp-grid` `gap` / `row-gap` / `column-gap`** and **`sp-section` `gap`**
  also stop at `lg` (`--sp-layout-section-gap-lg`, 32px).
- **`sp-container` `padding`** tops out at `lg` (32px inline).

Today a site that wants more room can only add `sp-gap-*` / `sp-gap-y-*`
utilities over the component, which is the override pattern that previously
made section rhythm uneven. The scale itself needs to grow, so every site
gets the same larger steps from the components' own attributes.

Proposed shape (route to the owning packages; the shell only consumes it):

- **spectre-tokens:** add four larger, responsive gap steps, `xl`, `2xl`, `3xl`
  and `4xl`, for stack, grid and section gaps, plus matching container inline
  padding steps. As with section padding, the top two are headroom for edge
  cases. A starting proposal for the owner to tune, mobile → desktop:

  | Step  | Mobile | Desktop |
  | ----- | ------ | ------- |
  | `xl`  | 24px   | 32px    |
  | `2xl` | 32px   | 48px    |
  | `3xl` | 48px   | 64px    |
  | `4xl` | 64px   | 96px    |

- **spectre-ui:** the matching stack, grid and section gap recipes, and
  container padding recipes, in the same layers as the existing ones.
- **spectre-components:** extend the `gap` types for `sp-stack`, `sp-grid`
  (`gap`, `row-gap`, `column-gap`) and `sp-section`, and `padding` for
  `sp-container`, to accept the new steps.

Coordinate with the section spacing scale above. Both scales should step
together, so a page can grow its section padding and its internal gaps in
proportion.

Acceptance criteria:

- A downstream site can set larger, viewport-responsive gaps and container
  padding through the components' public attributes alone, with no `sp-gap-*`
  or padding utility override and no selector targeting rendered internals.
- Existing `sm | md | lg` values keep their current output unless the owner
  decides otherwise, and that decision is recorded in the upstream changelog.
- The shell documents the new steps in its theme contract once they are
  consumable.

### 2026-10-01 — Spacing — One 8px Grid Across Every Package

Section padding (top, bottom, left, right) and every row and column gap
should sit on one 8px grid. They should also step consistently from one
package to the next, so a site never mixes rhythms. An audit of the installed
packages and a production child theme on 2026-10-01 found the scale and its
use are not on one grid today:

- **Tokens.** `--sp-layout-stack-gap-md` is 12px (0.75rem), off the 8px grid.
  The other layout tokens are on it: section padding 24 / 32 / 48, section gap
  16 / 24 / 32, container inline padding 16 / 24 / 32, stack gap 8 / 16.
- **Component recipes (spectre-ui `components.css`).** Several recipes use
  off-grid steps for padding and gaps: 12px vertical and horizontal padding
  (for example `.sp-list-group__item` at 12px / 16px), 12px gaps, and 4px
  gaps and padding.
- **Downstream use.** The child theme reaches for off-grid utilities and
  tokens where the component scale falls short: `sp-gap-12` (11 uses),
  `sp-py-20`, `sp-gap-20`, `sp-pt-12`, `sp-py-12`, `sp-gap-6`, `sp-gap-2`,
  `sp-gap-4`, and `--sp-space-2` / `-4` / `-12` / `-20` in its stylesheet.

Proposed rule (for the owner to confirm, then apply across spectre-tokens,
spectre-ui, spectre-components and this shell):

- **8px is the layout grid.** Section padding on all four sides, container
  inline padding, and stack, grid (`gap`, `row-gap`, `column-gap`) and section
  gaps take multiples of 8px only.
- **4px is the only sub-step.** It is allowed inside a component, for
  typography and icon alignment (a label against its value, an icon against
  its text), and never for spacing between layout blocks. 2px, 6px, 10px,
  12px, 14px and 20px leave the layout scale entirely.
- **Responsive steps snap to the grid.** The new `xl`–`4xl` section and gap
  steps (see the two items above) change at breakpoints between 8px values. A
  free fluid `clamp()` would land on off-grid values between breakpoints. If a
  fluid curve is kept, its endpoints and breakpoint values must be on the
  grid.
- **Left/right matches top/bottom.** Container inline padding steps use the
  same 8px values as section and gap steps, so a section's sides and its top
  and bottom read as one system.

Rollout, in order, each a release of its package:

1. **spectre-tokens:** move `--sp-layout-stack-gap-md` to an 8px value, ship
   the `xl`–`4xl` steps on the grid, and add a token test that fails on any
   off-grid layout spacing value.
2. **spectre-ui:** move the off-grid recipe padding and gaps onto the grid (or
   onto the 4px sub-step where it is genuinely in-component alignment), and add
   a recipe check that fails on off-grid layout spacing.
3. **spectre-components:** accept the new steps in every `gap`, `row-gap`,
   `column-gap`, `spacing` and `padding` attribute, and document the rule.
4. **This shell:** extend `check:drift` to flag off-grid spacing utilities and
   `--sp-space-*` references in maintained source, and document the rule in the
   theme contract.
5. **Downstream child themes:** replace off-grid utilities and tokens with
   on-grid component attributes, using the new steps where the old scale fell
   short.

Acceptance criteria:

- Every layout spacing token, recipe and component attribute value is a
  multiple of 8px, with 4px used only for in-component alignment.
- Section padding (all four sides), container padding, and row and column gaps
  step together on the same values in every package.
- Each package has an automated check that fails on off-grid layout spacing,
  and this shell's `check:drift` covers maintained theme source.
- Any change to an existing value's output is recorded in that package's
  changelog.

### 2026-10-01 — Section — Rhythm Between Adjacent Sections

When two sections sit back to back (for example a hero followed by a compact
logo strip), the first one's bottom padding and the second one's top padding
add together into a seam larger than either one intends. Today the only
downstream fixes are per-side padding overrides, which break the constraint
above. The reusable need is a rule for the seam itself, not per-instance
top/bottom controls.

Acceptance criteria:

- Investigate whether the space between adjacent sections can be governed
  upstream, e.g. an adjacent-sibling rule in the spectre-ui section recipe, or
  a documented `flush`/`attached` option for a band that belongs to the
  section above it.
- Either ship it upstream as a documented contract or explicitly decline it,
  with the reason recorded here.
- No per-side padding knobs are added to `sp-section` to satisfy this.

### 2026-10-01 — Text — Weight Option And Inline Line-Height

The same child theme restates typography on `sp-text`'s rendered internals in
two places where the component's public API runs out:

- **Weight.** `sp-text` exposes `size`, `variant` and `transform` but no
  weight, so a label that has to read as bolder than the muted line beneath
  it sets `font-weight` on both the host and `[data-sp-text-native]`.
- **Line-height on inline levels.** With `level="span"`, the host is a block
  that inherits the body line-height, and the native span sits inside it. The
  host's strut then sets a floor on the line box whatever the span's recipe
  asks for, so two stacked small spans cannot sit tight. The child sets
  `line-height` on both host and native element to work around it.

Acceptance criteria:

- `sp-text` offers a public weight option bound to the token weight scale, or
  the need is explicitly declined with the reason recorded here.
- An inline-level `sp-text` resolves its line box from its own size recipe,
  without a downstream rule on the host or the native element.
- Route to `@phcdevworks/spectre-components` / `@phcdevworks/spectre-ui`; this
  shell only consumes the result.

### 2026-10-01 — Pattern — Logo Cloud (Candidate)

The same child theme hand-builds a partner logo row: each mark in a fixed
square tile with a neutral fill so marks drawn at different scales and on
different field colours read as one row, desaturated at rest and returned to
full colour on hover. The tile size, fill and grayscale/opacity treatment are
local CSS because nothing upstream expresses them. A logo row is a common
social-proof pattern, so this may be a reusable component rather than a site
one.

Acceptance criteria:

- Decide whether a logo cloud belongs upstream (component or recipe in
  `@phcdevworks/spectre-components` / `@phcdevworks/spectre-ui`) or stays
  site-specific, and record the decision here.
- If accepted upstream, tile size, fill and the at-rest/hover treatment are
  public options backed by tokens, and the treatment respects user motion and
  contrast preferences.

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
