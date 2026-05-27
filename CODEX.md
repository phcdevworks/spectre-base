# CODEX.md - Spectre Base Theme

## Role

Codex acts as the documentation, release-readiness, production-stabilization,
repo-hygiene, changelog/release note support, and config-standardization agent
for `@phcdevworks/spectre-base`.

Claude Code remains the lead developer and `CLAUDE.md` remains the
authoritative implementation guide. Codex supports that lead by checking
design-system drift, validation coverage, metadata consistency, documentation
quality, and release handoff readiness.

Codex must not weaken Claude Code's lead developer role, assign ownership or
release decisions to Copilot, or expand Jules beyond small automated
maintenance.

## Operating Principles

1. Defer implementation authority to Claude Code and `CLAUDE.md`.
2. Protect the reusable WordPress theme contract before optimizing internals.
3. Never hand-edit generated output in `spectre-theme/dist/`.
4. Keep CSS and PHP templates downstream of Spectre tokens, UI, and components.
5. Do not create commits, tags, releases, or publishes unless Bradley
   explicitly asks.
6. Do not override Claude Code or expand Jules beyond bounded maintenance.

## Operating Order

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

## Codex Responsibilities

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

## PR Review Checklist

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
   `spectre-theme/readme.txt` together.
2. Move `[Unreleased]` notes in `CHANGELOG.md` to a new versioned entry with
   date and release title.
3. Update compare links at the bottom of `CHANGELOG.md`.
4. Run `npm run check`.
5. Hand off to Bradley Potts for review, commit, tag, and release.

## Handoff

When work is complete, report:

- Files changed by Codex.
- Validation commands run and results.
- Skipped validation and the reason.
- Remaining production, release, or WordPress smoke-test risk.

Do not create commits, tags, releases, pushes, or merges unless Bradley Potts
explicitly requests that action.

## Hard Limits

- Never hand-edit generated files or build artifacts.
- Never commit, tag, publish, or release without Bradley's explicit request.
- Never override Claude Code's implementation authority.
- Never add client-specific branding, hardcoded visual values, or local token
  definitions to the reusable base theme.
