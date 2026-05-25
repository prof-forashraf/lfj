PRICING_GOVERNANCE

Goal

Freeze architectural drift: ensure pricing logic remains centralized, deterministic, and auditable.

Forbidden Areas (pricing must NEVER be implemented here)

- React components (frontend)
- Blade templates
- Filament UI actions (business logic)
- Controllers (beyond orchestration)
- Helpers and global utility functions
- Inline pricing in migrations or seeders

Allowed Areas (single source of truth)

- `app/Domain/Pricing/` — all pricing DTOs, calculators, services, and value objects

Enforcement Rules

1. All code that computes prices, multipliers, markups, taxes, or conversions must live in `app/Domain/Pricing/`.
2. Controllers, Filament pages, or UIs may call pricing services but must not contain formula logic.
3. Any PR that introduces pricing code outside the domain folder will be rejected until refactored.
4. Code reviews must include `@YOUR_ORG/pricing-team` (replace placeholder in `.github/CODEOWNERS`).

PR Template snippet (include in your PRs that touch pricing)

```
### Pricing Change Checklist
- [ ] Files changed limited to `app/Domain/Pricing/` or admin sync services
- [ ] Audit note provided (short description of business impact)
- [ ] Effective date specified (if different than merge date)
- [ ] Tests added/updated that cover the behavior
- [ ] `CHANGELOG_PRICING.md` entry included
```

Tier classification (apply before merging)

- Tier 1 — Critical Financial Logic
  - Examples: markup formulas, purity calculations, resale logic, taxation
  - Requirements: 2 approvals, test coverage, audit note, changelog entry

- Tier 2 — Operational Admin Changes
  - Examples: Filament UI tweaks, admin filters, non-formula UI changes
  - Requirements: 1 approval, tests if behavior changes

Operational recommendations

- Use `CHANGELOG_PRICING.md` to maintain a concise record.
- Require signed commits for higher auditability if your org supports it.
- Run a mandatory PR checklist for pricing changes; automate checks where possible.

If you want, I can:
- Replace the `@YOUR_ORG/...` placeholders in the existing `.github/CODEOWNERS` with provided team names/users.
- Draft a PR template file to enforce the pricing checklist automatically.
- Generate a sample test PR branch and instructions for creating the validation PR.
