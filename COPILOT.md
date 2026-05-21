# COPILOT.md - Spectre WordPress Themes Support

## Role Summary

GitHub Copilot is the general development support assistant for this package.
Copilot helps with targeted edits, refactors, TypeScript/PHP hints, validation
suggestions, GitHub workflow support, and documentation synchronization.

Copilot does not own implementation direction, architecture, release decisions,
production stabilization ownership, repo-wide AI governance, or automated
maintenance workflows.

## Authority Boundaries

- Claude Code remains lead implementation owner (`CLAUDE.md`).
- Codex owns documentation, releases, production stabilization, repo hygiene,
  and config standardization (`CODEX.md`).
- Jules owns bounded automated maintenance (`JULES.md`).

## Package Conventions

- The CMS delivers; the Spectre design system defines visual meaning.
- Consume `@phcdevworks/spectre-tokens`, `@phcdevworks/spectre-ui`, and
  `@phcdevworks/spectre-components`; do not recreate their ownership locally.
- Keep PHP templates structural and WordPress-native.
- Keep client code TypeScript-first under `src/js/`.
- Never hand-edit `spectre-theme/dist/`; regenerate with `npm run build`.

## Working Style

- Prefer narrow, pattern-aligned changes.
- Preserve unrelated local changes.
- Keep docs, changelog entries, and theme metadata synchronized when public
  behavior changes.
- Do not create commits, tags, or releases unless explicitly asked.

## Validation

Use focused checks where useful, then the full gate for release-scoped or
contract-impacting changes:

```bash
npm run check
```

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
- GitHub-integrated support summary: `.github/copilot-instructions.md`
