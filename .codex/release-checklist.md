# Codex Release Checklist

Use this checklist when Codex is asked to review, prepare, or validate a release
for `@phcdevworks/spectre-base`.

## 1. Orient

- Read `CLAUDE.md` and `AGENTS.md` before changing files.
- Read `CODEX.md` for Codex-specific release, stabilization, repo hygiene, and
  config standardization duties.
- Run `git status --short` and note unrelated work already in progress.
- Inspect recent diffs with `git diff --stat` and targeted `git diff -- <path>`.
- Identify whether the change touches templates, styles, TypeScript, build
  tooling, package metadata, documentation, or release assets.

## 2. Design-System Guardrail

- PHP templates must stay structural: WordPress hierarchy, loops, escaping,
  metadata, and Spectre web components.
- CSS must consume Spectre tokens through `var(--sp-*)` or approved Spectre
  imports.
- Do not add local colors, spacing scales, radii, shadows, typography values,
  gradients, or component styling that belongs in Spectre packages.
- If a reusable visual pattern appears in the theme, note whether it should
  graduate to `@phcdevworks/spectre-ui` or
  `@phcdevworks/spectre-components`.

## 3. Metadata And Release Notes

- If releasing a new version, verify all three version locations match:
  - `package.json`
  - `spectre-theme/style.css`
  - `spectre-theme/readme.txt`
- Check whether `CHANGELOG.md`, README files, or template documentation need an
  update.
- Confirm PR or release notes explain user-facing behavior, validation results,
  and any known risk.

## 4. Validation Gate

Prefer the full gate before handoff:

```bash
npm run build
npm run check:assets
npm run lint
npm run lint:php
npm run check:drift
```

For dependency updates, run `npm install` first, then rebuild and rerun the
checks above.

If validation cannot run, record the exact command that was skipped or failed
and why.

## 5. Production Handoff

Summarize:

- Files changed by Codex.
- Validation commands run and results.
- Remaining review items for Bradley.
- Any user-facing, deployment, or rollback notes.

Do not commit, tag, push, publish, or merge unless Bradley explicitly requests
that action.
