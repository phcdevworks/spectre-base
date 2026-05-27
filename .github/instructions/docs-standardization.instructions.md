---
description: 'Use when updating README files, contributing docs, changelog entries, pull request text, release notes, or other repository documentation that should stay standardized.'
name: 'Docs Standardization'
---

# Docs Standardization

- Match command names and validation steps to the scripts in [package.json](../../package.json). Do not mention `npm test` unless a real test script exists.
- Refer to the repository and package as `spectre-base`, and the deployable theme directory as `spectre-theme/`.
- Prefer linking existing guidance in [AGENTS.md](../../AGENTS.md), [CLAUDE.md](../../CLAUDE.md), [README.md](../../README.md), and [CONTRIBUTING.md](../../CONTRIBUTING.md) instead of repeating policy in new words.
- When documenting validation, use the real release gate: `build`, `check:assets`, `lint`, `lint:php`, and `check:drift`.
- Release and handoff notes should state scope, validation run, skipped checks, known risk, and the next reviewer action.
