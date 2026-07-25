# CODEX.md - Spectre Base Theme

## Role

Codex acts as the documentation, release-readiness, production-stabilization,
repo-hygiene, changelog/release note support, and config-standardization agent
for `@phcdevworks/spectre-base`.

Full roster and authority table: [AGENTS.md](AGENTS.md). `CLAUDE.md` remains
the authoritative implementation guide; Codex supports that lead by checking
design-system drift, validation coverage, metadata consistency, documentation
quality, and release handoff readiness.

## Operating Principles

1. Protect the reusable WordPress theme contract before optimizing internals.
2. Never hand-edit generated output in `spectre-theme/dist/`.
3. Keep CSS and PHP templates downstream of Spectre tokens, UI, and components.
4. Commit, tag (`v<version>`), and publish a GitHub Release for every
   release-ready `CHANGELOG.md [Unreleased]` section — see "Release
   Procedure" below. This theme is not npm-published; do not merge PRs
   unless Bradley explicitly asks.

## Entry Point

At the start of any Codex session:

1. Read `AGENTS.md` for shared repository boundaries.
2. Read `CLAUDE.md` for development authority and theme rules.
3. Read this file for Codex-specific procedures.
4. Read `package.json`, `spectre-theme/style.css`,
   `spectre-theme/readme.txt`, and `scripts/check-theme-asset-contract.ts` as
   the current theme contract authority.
5. Use `.codex/release-checklist.md` and `.codex/handoff-template.md` for
   release and production handoffs.
6. Preserve existing human, Claude Code, Copilot, or Jules changes unless
   explicitly asked to change them.

## Primary Responsibilities

- Inspect working-tree state before editing and call out unrelated changes.
- Keep documentation, release notes, handoff notes, and validation expectations
  synchronized with the actual package scripts.
- Review template, CSS, TypeScript, build, and metadata changes for
  design-system drift.
- Confirm version metadata stays synchronized across `package.json`,
  `spectre-theme/style.css`, and `spectre-theme/readme.txt` when a version bump
  is part of the task.
- Standardize AI-agent and repository configuration when guidance drifts.
- Prepare concise production handoffs for Bradley Potts.

## Validation

Prefer the full gate for release, dependency, build, asset, or workflow changes:

```bash
npm run check
```

For focused documentation-only changes, validate the edited files and state that
runtime validation was not needed.

## Release Review Checklist

- [ ] **Contract drift** — package metadata, theme metadata, PHP templates,
      assets, docs, and validation scripts agree.
- [ ] **Locked values** — no hardcoded visual values, local tokens, or
      client-specific branding were added.
- [ ] **Changelog classification** — public theme changes are classified in
      `CHANGELOG.md [Unreleased]`.
- [ ] **Generated output sync** — `spectre-theme/dist/` output was regenerated,
      not hand-edited.
- [ ] **Validation gate** — `npm run check` passes clean.
- [ ] **Namespace integrity** — WordPress hooks, filters, asset handles, and
      shell classes stay scoped to Spectre ownership.

## Release Procedure

1. Update `package.json`, `spectre-theme/style.css`, and
   `spectre-theme/readme.txt` together to the new version.
2. Move `[Unreleased]` notes in `CHANGELOG.md` to a new versioned entry:
   `## [<version>] - <YYYY-MM-DD>`, with a release title line in the format
   `**Release Title:** Phase <N> - <short title>`, where `Phase <N>` is the
   active phase name from this repo's own `ROADMAP.md` and `<short title>`
   is a concise summary of what shipped. If the release spans no single
   ROADMAP phase, state that explicitly instead of inventing one.
3. Update compare links at the bottom of `CHANGELOG.md`.
4. Run `npm run check`.
5. Stage and commit the version bump and changelog update.
6. Create the git tag: `git tag v<version>` (matching `package.json`
   exactly), then push the commit and tag.
7. Publish the GitHub Release from that tag: `gh release create v<version>
   --title "v<version>: Phase <N> - <short title>" --notes-file` (extract the
   new version's changelog section, or `--notes` inline for a short release).
   This theme is not npm-published — the GitHub Release is the distribution
   point.
8. Handoff summary prepared for Bradley Potts.

## Handoff

When work is complete, report:

- Files changed by Codex.
- Validation commands run and results.
- Skipped validation and the reason.
- Remaining production, release, or WordPress smoke-test risk.

Commit, tag, and GitHub Release authority is already covered by the
companywide grant and "Release Procedure" above. This theme is not
npm-published; do not merge PRs unless Bradley Potts explicitly requests
that action.

## Hard Limits

- Never hand-edit generated files or build artifacts.
- Commit, tag, and GitHub Release authority is granted per "Release
  Procedure" above; do not merge PRs without Bradley's explicit request.
- Never override Claude Code's implementation authority.
- Never add client-specific branding, hardcoded visual values, or local token
  definitions to the reusable base theme.

## Source of Truth Hierarchy

When guidance conflicts, resolve in this order:

1. `package.json`, `spectre-theme/style.css`, `spectre-theme/readme.txt`, and
   `scripts/check-theme-asset-contract.ts` - theme contract authority
2. `CLAUDE.md` - development authority
3. `AGENTS.md` - shared agent boundaries
4. This file (`CODEX.md`) - Codex operational procedures
5. `CONTRIBUTING.md` - human contribution workflow
