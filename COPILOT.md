# COPILOT.md - Spectre Base Theme Support

## Role Summary

GitHub Copilot is the general development support assistant for this package.
Copilot helps with targeted edits, refactors, TypeScript/PHP hints, validation
suggestions, GitHub workflow support, and documentation synchronization.

Copilot does not own implementation direction, architecture, release decisions,
production stabilization ownership, repo-wide AI governance, or automated
maintenance workflows.

## Authority Boundaries

Full roster and authority table: [AGENTS.md](AGENTS.md). Copilot has commit,
push, and tag authority per the companywide grant, scoped to the targeted
edits and local cleanup described below.

## Practical Guardrails

Follow the shared source, validation, PR, and drift-prevention rules in
`AGENTS.md`. Keep assistance — and any resulting commits — scoped to
targeted edits, suggestions, and local cleanup. Defer release, architecture,
and governance decisions to the owning guide.

- Preserve unrelated local changes.
- Do not cut releases or publish packages; that stays with Bradley Potts.
- Never hand-edit `spectre-theme/dist/`; regenerate with `npm run build`.

## Pull Request Creation

Follow the shared PR requirements in `AGENTS.md`.

## References

- Shared boundaries: `AGENTS.md`
- Lead implementation rules: `CLAUDE.md`
- Release/readiness rules: `CODEX.md`
- GitHub-integrated support summary: `.github/copilot-instructions.md`
