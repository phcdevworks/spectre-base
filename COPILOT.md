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

## Practical Guardrails

Follow the shared source, validation, PR, and drift-prevention rules in
`AGENTS.md`. Keep assistance scoped to targeted edits, suggestions, and local
cleanup. Defer release, architecture, and governance decisions to the owning
guide.

- Preserve unrelated local changes.
- Do not create commits, tags, or releases unless explicitly asked.
- Never hand-edit `spectre-theme/dist/`; regenerate with `npm run build`.

## Pull Request Creation

Follow the shared PR requirements in `AGENTS.md`.

## References

- Shared boundaries: `AGENTS.md`
- Lead implementation rules: `CLAUDE.md`
- Release/readiness rules: `CODEX.md`
- GitHub-integrated support summary: `.github/copilot-instructions.md`
