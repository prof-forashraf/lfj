CHANGELOG_PRICING

Purpose

This file records changes to pricing formulas, synchronization behavior, tax logic, resale calculations, and other authoritative pricing rules. Maintain one entry per PR that modifies pricing logic or pricing-related configuration.

Entry format (required)

- Date: YYYY-MM-DD
- PR: #<number> - short title
- Author: GitHub user or team
- Type: formula | sync | config | admin | doc
- Files changed: list of repo paths
- Summary: short description of the change
- Audit note: short business rationale and impact (required for Tier 1 changes)
- Effective date: YYYY-MM-DD (when change becomes active)
- Version: semantic or incremental pricing_version

Example

- Date: 2026-05-24
- PR: #123 - Improve purity multiplier handling
- Author: @alice
- Type: formula
- Files changed: app/Domain/Pricing/Calculators/Purity.php, app/Domain/Pricing/Services/MetalPriceService.php
- Summary: Normalize purity scaling to use 1000-based weights; prevents rounding drift on large orders.
- Audit note: Corrects rounding bias observed in Q1 invoice reconciliation; no customer-facing change expected.
- Effective date: 2026-05-24
- Version: pricing_v1.3

--

- Date: 2026-05-24
- PR: test (ci/test-pricing-codeowners) - Validate governance and CI
- Author: @automation
- Type: documentation
- Files changed: app/Domain/Pricing/PR_TEST.md, .github/pull_request_template.md, .github/CODEOWNERS
- Summary: Add a test PR and governance artifacts to validate CODEOWNERS enforcement and the Pricing Domain Quality workflow (pint, phpstan, tests).
- Audit note: No pricing behavior change; this entry documents operational governance validation.
- Effective date: 2026-05-24
- Version: pricing_v0.0-test

How to use

- Any PR that touches `app/Domain/Pricing/` or files listed in `.github/CODEOWNERS` must include a `CHANGELOG_PRICING.md` entry in the PR description and update this file if it modifies formulas or sync rules.
- For Tier 1 changes (critical financial logic), require a short audit note and at least 2 approvals (see governance policy).

Location

- This file lives at `.github/CHANGELOG_PRICING.md` and is the canonical pricing-change history for the repository.

Operational note

- Consider extracting entries to an external audit log or release notes for long-term retention if your repo is archived or large.