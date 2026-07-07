---
description: 'Use when reviewing releases, dependency updates, production readiness, validation gates, version sync, changelog updates, or release handoff notes.'
name: 'Release Readiness'
---

# Release Readiness

- Start with [AGENTS.md](../../AGENTS.md), [CLAUDE.md](../../CLAUDE.md), and [.codex/release-checklist.md](../../.codex/release-checklist.md).
- Preserve unrelated work already in progress. Check `git status --short` before editing.
- Prefer the full release gate for release-scoped, dependency, build, asset, or workflow changes:
  - `npm run build`
  - `npm run check:assets`
  - `npm run check:version-sync`
  - `npm run lint`
  - `npm run lint:php`
  - `npm run check:drift`
  - `npm run check:ecosystem`
- If the task changes version metadata, keep `package.json`, `spectre-theme/style.css`, and `spectre-theme/readme.txt` synchronized.
- Document every skipped validation step, known risk, and any WordPress smoke-testing still needed in the final handoff.
