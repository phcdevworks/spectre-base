# GitHub Copilot Instructions for @phcdevworks/spectre-base

## Role

GitHub Copilot is the general development support assistant for this package.

- Claude Code owns implementation leadership (`CLAUDE.md`).
- Codex owns documentation, releases, production stabilization, repo hygiene,
  and config standardization (`CODEX.md`).
- Jules owns bounded automated maintenance tasks.
- Copilot supports targeted edits, refactors, TypeScript/PHP lint-safe fixes,
  test and validation suggestions, API usage hints, and IDE productivity.

Copilot does not own architecture direction, release decisions, or final
handoff authority.

## Package Conventions

- The CMS delivers; the Spectre design system defines visual meaning.
- Consume `@phcdevworks/spectre-tokens`, `@phcdevworks/spectre-ui`, and
  `@phcdevworks/spectre-components`; do not recreate their ownership locally.
- Keep PHP templates structural and WordPress-native.
- Keep client code TypeScript-first under `src/js/`.
- Avoid hardcoded visual values in styles and templates.

## Working Style

- Prefer narrow, pattern-aligned changes over broad rewrites.
- Keep docs and release artifacts in sync when behavior changes.
- Preserve unrelated local changes.
- Do not create commits, tags, or releases unless explicitly asked.

## Validation

- Use focused checks first where useful.
- For release-scoped changes, use the real gate: `npm run check` (alias for
  `npm run validate`, which runs build, check:assets, lint, lint:php, and
  check:drift in sequence).

## Pull Request Creation

When opening a PR, populate every section of
`.github/pull_request_template.md`:

- **Linked issue** — issue number (`#N`) or `N/A`.
- **Summary of changes** — one or two bullets describing what changed.
- **Theme contract change type** — exactly one of `additive`, `semantic
  change`, `breaking`, or `N/A`.
- **Type of Change** — check every box that applies.
- **Checklist** — check each completed item; leave blocked items unchecked
  with a brief inline note.

Never submit a PR with an empty body or only the template headings left
unfilled. CodeRabbit's description check blocks such PRs.

## References

- Shared boundaries: `AGENTS.md`
- Lead implementation rules: `CLAUDE.md`
- Release/readiness rules: `CODEX.md`
- Scoped task instructions: `.github/instructions/`
