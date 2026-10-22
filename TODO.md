# Spectre Base Theme Execution Todo

Phases 1 through 17 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

## Requested by Downstream

- **Icons — Ship an Icon Set**

  `sp-icon-box` ships with no glyphs, so each child theme hand-copies its own.
  A downstream child theme keeps nine outline icons as local path data and adds
  each new icon by hand. Wanted: a maintained outline icon set delivered by
  Spectre, with its license notice, and
  a way to render an icon by name inside `sp-icon-box` (for example
  `<sp-icon name="shield-check">` or a PHP helper in the base theme), so every
  child theme draws from one set and the child's table can be deleted. A filled
  variant would also answer requests for solid icons, which the proposed
  outline set lacks.

- **Icon Box — Icon Size Option**

  `sp-icon-box` sizes any projected SVG at `1em` of the box's own font size,
  which at `md` (a 48px box) is a 16px glyph, and it has no option for the
  icon's size; the SVG's width and height attributes are overridden. The
  downstream child theme sizes its feature-card glyphs to 32px with a local
  rule. Wanted: an `icon-size` option, or
  a larger default glyph relative to the box, so the child can drop it.

- **Typography — Responsive Display and Heading Presets**

  The display and heading presets are one size at every width, while section
  spacing already steps at `lg`. On a 390px phone display 1 (72px) runs five or
  six lines and display 2 (60px) wraps nearly word by word. A downstream child
  theme carries a temporary ramp below 768px (display 1/2 to the display 4
  size, heading h2 to h4, h3 to h5).
  Wanted: a responsive step in the presets,
  or responsive size tokens, so the child can drop it.

- **Typography — Minimum Text Size in Component Recipes**

  These recipes still hard-code text below `--sp-min-text-size` (16px) with no
  size option: the footer kit's link/text/muted roles, `sp-tabs__item`,
  `sp-form-label`, `sp-helper-text`, `sp-error-message`, `sp-prose figcaption`, and `sp-table`
  (its `size` only sets cell padding). A downstream child theme lifts each to
  `md` as a temporary workaround.

- **Typography — Prose Size Option**

  `sp-prose` has no size option; a downstream child theme sets it to the 18px
  `lg` step by hand.

- **Button — Brand Action Variant**

  No `sp-button` variant takes a theme's own action colour: `cta` is blue and
  `accent` is purple. A downstream child theme repaints `.sp-btn` with local
  classes, including hover and active shades mixed by hand. Wanted: a variant,
  or an accent colour, that a child theme can point at its brand action colour
  without declaring `--sp-*`.

- **Accent Colour — Theme Action Colour for Stat Units and Rails**

  `sp-stat`'s `unit` slot and `sp-quote`'s rail take only the system's accent
  colours. A downstream child theme marks stat units in its action colour, so
  it keeps a local class instead of using the `unit` slot.

- **Accordion and Breadcrumb — Text Size Options**

  `sp-accordion-item`'s `label` and `sp-breadcrumb`'s links render below the
  16px floor, with no size option. A downstream child theme projects `sp-text` into
  the header slot and into each crumb to hold its sizes.

- **Text — Tracking Option**

  `sp-text` has no letter-spacing option, so uppercase eyebrows and kickers in
  a downstream child theme set tracking on `[data-sp-text-native]`.

- **Rating — Stars Render Empty**

  Through UI 5.7.0, `sp-rating` renders its `.sp-rating-star` spans with no
  glyph, so only the label shows. A downstream child theme draws five stars in
  local CSS until it renders.

- **Nav and Footer — Container Width and Footer Background**

  `.sp-nav` and `.sp-footer` are flex containers with inline padding, so their
  `sp-container` shrink-wraps and sits 16px inside the edge of the content
  around it. A downstream child theme zeroes the padding and sets the container
  width to 100%. `sp-footer` also has no background option; the child paints
  `.sp-footer` with its local background colour.
