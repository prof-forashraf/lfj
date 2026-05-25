PR Test — Pricing Domain

Purpose

This file is a small, no-op touch to help create a test PR that verifies CODEOWNERS
and CI enforcement for the `app/Domain/Pricing/` area.

How to use

1. Create a branch locally:

```bash
git checkout -b ci/test-pricing-codeowners
```

2. Make a trivial change (this file is sufficient), commit and push:

```bash
git add app/Domain/Pricing/PR_TEST.md
git commit -m "chore(ci): test CODEOWNERS and Pricing Domain Quality checks"
git push -u origin ci/test-pricing-codeowners
```

3. Open a pull request on GitHub targeting `develop` (or `main`) and verify:
- CODEOWNER review is requested from the pricing team
- The `Pricing Domain Quality` checks (`pint`, `phpstan`, `tests`) run and pass
- Merge is blocked without required approvals and passing checks

Notes

Do not merge this test PR in production branches unless CI passes and approvals are present.
