# Spectre Base Theme Execution Todo

Phases 1 through 15 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

Active development continues: dependency upkeep, drift/regression
prevention, CI health, and new scope planned proactively — e.g. richer
interactivity via editor blocks backed by Spectre web components (static
block markup, progressively enhanced via `defineSpectreComponents()`), not
a client-side router or SPA shell.

## Active — Component Coverage

An audit of a downstream child theme (2026-10-09) found its pages built almost
entirely from Spectre components, but about 67 child-theme CSS selectors
reaching into component internals (`[data-sp-text-native]`, `.sp-btn`). Each
works around an option Spectre does not have yet. The items below remove the
need for those workarounds, adopt components that now exist, and add
blockquote styling.

### Base theme work

- [ ] **Blockquote and pull-quote styles.** Give core `core/quote` and
      `core/pullquote` blocks Spectre-based styles with an accent: a default
      quote, an accent-rule variant (a thick accent-coloured leading edge), and
      a large statement variant with a decorative quote mark. Register them as
      block styles so editors can pick them, and mirror them in the editor
      through `theme.json`. Attribution (`<cite>` / figcaption) is styled as
      name, then role and organization, muted.
      Acceptance: every value comes from `var(--sp-*)` or an upstream recipe,
      `npm run check:drift` passes, the styles render the same on the front
      end and in the editor, and the accent colour follows a token a child
      theme can set without redeclaring `--sp-*`. If the upstream quote recipe
      requested below has not shipped, build on the existing
      `--sp-prose-blockquote-*` tokens and swap to the recipe when it lands.
- [ ] **Tables on the Spectre table recipe.** Map core `core/table` block
      output to the `sp-table` recipe (shipped in `spectre-components` 1.22) so
      editor tables match component tables, and document in README.md that
      child themes should use `sp-table` rather than a hand-styled `<table>`.
      Acceptance: a core table block and an `sp-table` render alike, and the
      drift check passes.
- [ ] **Child-theme guidance follows upstream.** As each upstream request
      below ships, update README.md "Child Themes" to point at the new option
      and remove the `[data-sp-text-native]` / `.sp-btn` workaround advice it
      replaces.

### Upstream requests to file

These belong in the owning repo's `TODO.md` under `## Requested by Downstream`
(see AGENTS.md § "Upstream Requests and Roadmap Self-Expansion"). They are
listed here only because those repos were not available when the audit ran.
Move each one upstream when filing it, then delete it from this list.

- [ ] **`sp-text` weight, line-height, and colour options**
      (`spectre-components` / `spectre-ui`). A child theme restates font
      weight, line height, and colour on `[data-sp-text-native]` for eyebrows,
      card titles, stat labels, and logo-strip text, because `sp-text` exposes
      size and variant only. Request: `weight`, `leading` (tight / snug /
      normal), and an accent colour role, applied to the native element.
- [ ] **A themeable accent variant for `sp-button`** (`spectre-components` /
      `spectre-ui`). Child themes repaint `.sp-btn` inside the host to give
      primary calls to action a brand colour, solid and outline, including
      hover and active shades. Request: an `accent` tone, solid and outline,
      driven by a documented accent token role a child theme may set.
- [ ] **Tone options for `sp-stepper`** (`spectre-components` /
      `spectre-ui`). Its active and done steps are fixed to blue and green, so
      a child theme that needs its own accent hand-builds a stepper instead.
      Request: active and done tone roles, defaulting to today's colours.
- [ ] **A quote component with accents** (`spectre-components` /
      `spectre-ui`). Request: `sp-blockquote` (or `sp-quote`) with an accent
      leading edge or decorative quote mark, a size scale up to a large
      statement, an attribution slot (name, role, organization, optional
      avatar), and plain / subtle / inverse surfaces. The base theme's
      block-style work above adopts it once it ships.
- [ ] **A stat component** (`spectre-components` / `spectre-ui`). Child themes
      hand-build figure + accented unit + label blocks in three places (stat
      rails, metric cards, case-study figures). Request: `sp-stat` with figure,
      unit (accent-coloured), label, size, and alignment.
- [ ] **A split section-head layout** (`spectre-ui`). Request: a section-head
      recipe that sets the title left and the lead right on a twelve-column
      grid at large widths, stacking below, as an alternative to the centred
      stacked head, so body sections can read from the left.

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
