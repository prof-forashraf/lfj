<!--
  Pull Request Template — Pricing Domain Governance
  Act as a senior engineering governance architect when filling this template.
  This repository contains a financially sensitive pricing engine.
  The template enforces pricing-rule transparency, architectural discipline,
  auditability, and testing accountability.
-->

# Summary

Provide a short, one-line description of the change and its intent.

---

# Change Type (select all that apply)

- [ ] Pricing engine logic
- [ ] Market synchronization
- [ ] Filament/admin workflow
- [ ] API change
- [ ] Cache behavior
- [ ] Database schema
- [ ] CI/CD or governance
- [ ] Documentation only

---

# Pricing Impact Assessment (required)

Answer the questions below explicitly. Be concise but precise.

- Does this change affect pricing calculations? (Yes / No) — explain.
- Does this change affect synchronization behavior? (Yes / No) — explain.
- Does this change alter markup, purity, resale, or tax logic? (Yes / No) — explain.
- Does this impact historical pricing reproducibility? (Yes / No) — explain.
- Are customer-facing totals expected to change? (Yes / No) — explain.

If any answers are "Yes", include a short Audit Note describing business impact,
expected customers affected, and mitigation/rollback steps.

---

# Architectural Compliance Checklist (required)

- [ ] No pricing logic added outside `app/Domain/Pricing`
- [ ] No frontend pricing calculations introduced
- [ ] No controller/business-rule leakage introduced
- [ ] `PricingEngine` remains the single pricing entry point
- [ ] Cache behavior remains deterministic and documented
- [ ] Immutable market history preserved (no destructive changes)

If any box is unchecked, explain why and link to a refactor plan.

---

# Testing Checklist (required)

- [ ] `php vendor/bin/pint --test app/Domain/Pricing tests/Unit/Services` passes
- [ ] `php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G` passes
- [ ] Pricing-domain tests pass (`php vendor/bin/pest tests/Unit/PricingEngineTest.php tests/Unit/Services`)
- [ ] Edge cases and boundary conditions tested (list tests added/updated)
- [ ] Manual verification completed (describe steps and results)

---

# Changelog Requirement

- [ ] `CHANGELOG_PRICING.md` updated (required if pricing behavior changed)

---

# Migration / Operational Notes

List any migrations, cache resets, queue impacts, scheduler considerations, or
deployment steps required. Include commands where appropriate.

---

# Risk Assessment (required)

Select one:

- [ ] Low risk
- [ ] Medium risk
- [ ] High risk (if selected, provide rollback plan and extra reviewer names)

If High risk: include a clear rollback plan and who to notify operationally.

---

# Reviewer Guidance (for reviewers)

This PR modifies pricing-sensitive code. Reviewers MUST verify:

- All pricing logic remains in `app/Domain/Pricing`.
- Tests exercise the new/changed formulas and edge cases.
- `CHANGELOG_PRICING.md` is updated for formula/sync changes.
- Audit Note is present for Tier 1 changes.

If the PR introduces Tier 1 changes (markups, taxes, resale logic, purity),
require at least 2 approvals including a member of the pricing team.

---

# Final Sign-off

By submitting this PR you confirm that you have completed the checklist above
and that pricing logic remains centralized, deterministic, and auditable.

<!-- End of template -->
