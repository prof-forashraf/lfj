Recommended GitHub Branch Protection Rules

Protected Branches

Protect:

- main
- develop

Required Pull Request Rules

Enable:

✅ Require a pull request before merging

✅ Require approvals

Recommended:

- Minimum approvals: 1–2

CODEOWNER Enforcement

Enable:

✅ Require review from Code Owners

This is critical for:

- pricing logic
- synchronization pipeline
- financial calculations
- admin pricing rules

CI Enforcement

Enable:

✅ Require status checks to pass before merging

Required checks:

- Pricing Domain Quality / pint
- Pricing Domain Quality / phpstan
- Pricing Domain Quality / tests

Additional Recommended Rules

Enable:

✅ Dismiss stale pull request approvals when new commits are pushed

Reason:
Pricing changes can materially alter calculations after approval.

✅ Require branches to be up to date before merging

Prevents:

- stale CI results
- hidden merge conflicts
- outdated pricing tests

Strongly Recommended Restrictions

Enable:

✅ Restrict force pushes

✅ Restrict branch deletions

Optional but Recommended

Require signed commits

Good for:

- audit-sensitive pricing systems
- traceability

Conversation resolution required

Prevents unresolved review concerns.

Important Strategic Governance Rule

You are now operating a:

financially sensitive calculation subsystem

That means:

- pricing changes are business-critical
- synchronization changes affect customer trust
- hidden calculation drift is dangerous

Your governance model should increasingly resemble:

- ERP systems
- accounting systems
- billing systems

—not casual application CRUD.

Suggested Future Governance Model

Eventually classify changes into:

Tier 1 — Critical Financial Logic

Requires:

- 2 approvals
- test coverage
- audit note

Examples:

- markup formulas
- purity calculations
- resale logic
- taxation

Tier 2 — Operational Admin Changes

Requires:

- 1 approval

Examples:

- Filament UI tweaks
- table formatting
- admin filters

One More Important Recommendation

You should eventually create:

`CHANGELOG_PRICING.md`

Track:

- pricing formula changes
- synchronization rule changes
- multiplier changes
- tax logic changes
- resale logic changes

Reason:
You will eventually need to answer:

“Why did the price calculation change?”

Especially for:

- invoices
- disputes
- customer support
- accounting reconciliation

That becomes difficult without explicit pricing-change history.

Quick apply (GitHub API / gh)

Run these commands from a machine with repo admin rights and `GITHUB_TOKEN` set (or use `gh auth login`). Replace `OWNER` and `REPO`.

# Example using GitHub CLI `gh api` (replace OWNER/REPO and branch)

```bash
gh api --method PUT \
  -H "Accept: application/vnd.github+json" \
  /repos/OWNER/REPO/branches/main/protection \
  -f required_status_checks='{"strict":true,"contexts":["Pricing Domain Quality / pint","Pricing Domain Quality / phpstan","Pricing Domain Quality / tests"]}' \
  -f enforce_admins=false \
  -f required_pull_request_reviews='{"dismiss_stale_reviews":true,"require_code_owner_reviews":true,"required_approving_review_count":1}' \
  -f restrictions=null

gh api --method PUT \
  -H "Accept: application/vnd.github+json" \
  /repos/OWNER/REPO/branches/develop/protection \
  -f required_status_checks='{"strict":true,"contexts":["Pricing Domain Quality / pint","Pricing Domain Quality / phpstan","Pricing Domain Quality / tests"]}' \
  -f enforce_admins=false \
  -f required_pull_request_reviews='{"dismiss_stale_reviews":true,"require_code_owner_reviews":true,"required_approving_review_count":1}' \
  -f restrictions=null
```

# Example using curl (requires a PAT with `repo` scope):

```bash
curl -X PUT -H "Authorization: token $GITHUB_TOKEN" -H "Accept: application/vnd.github+json" \
  https://api.github.com/repos/OWNER/REPO/branches/main/protection \
  -d '{
    "required_status_checks": {"strict": true, "contexts": [
      "Pricing Domain Quality / pint",
      "Pricing Domain Quality / phpstan",
      "Pricing Domain Quality / tests"
    ]},
    "enforce_admins": false,
    "required_pull_request_reviews": {
      "dismiss_stale_reviews": true,
      "require_code_owner_reviews": true,
      "required_approving_review_count": 1
    },
    "restrictions": null
  }'
```

Notes:
- To restrict force pushes and branch deletions, use the GitHub REST endpoints for `protection/branches/{branch}/restrictions` and `protection/branches/{branch}/allow_force_pushes` if available via your API version, or set via the GitHub UI.
- If API calls fail, apply these settings in the repository Settings → Branches → Branch protection rules UI.

Final checklist (apply after pushing CODEOWNERS):

- [ ] Push `CODEOWNERS` and `BRANCH_PROTECTION.md` to the repository
- [ ] Apply branch protection rules for `main` and `develop` in GitHub
- [ ] Confirm `Pricing Domain Quality` checks appear in PR status
- [ ] Enable "Require review from Code Owners" and set minimum approvals
- [ ] Enable "Dismiss stale pull request approvals" and "Require branches to be up to date before merging"
- [ ] Restrict force pushes and branch deletions
- [ ] (Optional) Require signed commits for higher traceability

Governance tip: create `CHANGELOG_PRICING.md` and a lightweight PR template requiring an "audit note" field for pricing-formula changes.
