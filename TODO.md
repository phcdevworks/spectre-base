# Spectre Base Theme Execution Todo

Phases 1 through 16 are delivered — see [ROADMAP.md](ROADMAP.md) for the full
delivery history and [CHANGELOG.md](CHANGELOG.md) for release-by-release
detail.

Active development continues: dependency upkeep, drift/regression
prevention, CI health, and new scope planned proactively — e.g. richer
interactivity via editor blocks backed by Spectre web components (static
block markup, progressively enhanced via `defineSpectreComponents()`), not
a client-side router or SPA shell.

## Active

- [ ] **Code blocks are narrower than the text around them.** On a page,
      code blocks come out about 110px narrower than paragraphs and tables,
      so their edges don't line up. The cause is in `src/styles/main.css`:
      the content width is measured in characters, and code uses a smaller
      font, so it gets a smaller width.
      Done when: code blocks, paragraphs, and tables are the same width on a
      real WordPress page, and `npm run check` passes.
- [ ] **System colour mode still needs a script.** The Light / Dark /
      System setting uses a small pre-paint script in
      `spectre-theme/functions.php` to pick light or dark before the page
      draws. `spectre-tokens` 4.12.0 and `spectre-ui` 5.4.0 now follow
      `data-spectre-theme="system"` through `prefers-color-scheme` on their
      own, so the theme can set that value on `<html>` and drop the script.
      Done when: System follows the OS setting with no inline script, Light
      and Dark are unchanged, and `npm run check` passes.
- [ ] **Update to `spectre-ui` 5.6.0 and `spectre-tokens` 4.14.0 (both
      published).** The font scale now matches Tailwind: `--sp-font-lg-*`
      through `--sp-font-6xl-*` each drop one step (for example, `lg` goes
      from 20px to 18px). `spectre-ui` 5.6.0 already moved its own component
      uses up a step so they keep their size. `src/styles/main.css` uses these
      variables directly. The update also brings the table stripe fix and the
      `.sp-prose` blockquote spacing fix.
      Done when: dependencies are UI `^5.6.0` / Tokens `^4.14.0`, each direct
      `--sp-font-*` use is confirmed at its new size or moved one step up, the
      temporary blockquote fix in `src/styles/main.css` is removed, and
      `npm run check` passes.

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
