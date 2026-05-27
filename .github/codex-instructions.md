# Codex Instructions — Spectre Base Theme

This is the GitHub-integrated Codex guide for
`@phcdevworks/spectre-base`. Read `CODEX.md` for the full Codex
playbook, `CLAUDE.md` for implementation authority, and `AGENTS.md` for shared
agent boundaries.

## Role Summary

Codex supports release readiness, documentation, production stabilization, repo
hygiene, and configuration standardization. Claude Code leads implementation.
Bradley Potts owns final commit, merge, tag, publish, and release authority.

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

## Pull Request Review Scope

1. **Contract drift** — do theme metadata, PHP templates, assets, docs, and
   package scripts still describe the same deployable theme behavior?
2. **Locked values** — were hardcoded visual values, client-specific branding,
   or local design tokens introduced?
3. **Changelog classification** — does `CHANGELOG.md [Unreleased]` classify
   theme-facing or release-impacting changes accurately?
4. **Generated output sync** — were `spectre-theme/dist/` artifacts regenerated
   by `npm run build` and never hand-edited?
5. **Validation gate** — does `npm run check` pass clean?
6. **Namespace integrity** — are WordPress hooks, filters, handles, and
   Spectre-facing classes still scoped to `spectre_*` / `spectre-` ownership?

## Issue Triage Scope

Codex handles documentation drift, release notes, validation interpretation,
repo hygiene, and configuration standardization. Theme architecture,
WordPress behavior, PHP template changes, asset loading, and design-system
delivery decisions go to Claude Code.

## Validation Commands

```bash
npm run check        # full gate, alias for validate
npm run validate     # build, asset contract, lint, PHP lint, drift scan
npm run build        # TypeScript check and Vite production build
npm run check:assets # Vite manifest and asset contract validation
npm run lint         # ESLint
npm run lint:php     # PHP syntax validation
npm run check:drift  # design-system drift scan
```

## Source of Truth Hierarchy

1. Theme contract authority: `spectre-theme/`, `src/`, `package.json`,
   `scripts/check-theme-asset-contract.ts`, and version metadata
2. `CLAUDE.md`
3. `AGENTS.md`
4. `CODEX.md` and this file
5. `README.md` and `CONTRIBUTING.md`

## Hard Limits

- Never hand-edit `spectre-theme/dist/` or generated asset manifests.
- Never commit, tag, publish, or release without Bradley's explicit request.
- Never override Claude Code's implementation authority.
- Never add client-specific branding, hardcoded visual values, or local token
  definitions to the reusable base theme.
- Never change WordPress theme version metadata in only one place.
