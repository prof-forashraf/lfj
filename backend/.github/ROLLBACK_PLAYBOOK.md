Rollback Playbook — LFJProj Pricing Backend

Purpose

Steps to safely rollback a deploy in case of critical failures that cannot be mitigated quickly.

When to rollback

- Critical pricing discrepancies affecting invoices or customer totals
- Reproducible errors in production endpoints that block checkout or quoting
- Data corruption in market price history introduced by the deploy

Immediate actions

1. Alert stakeholders and on-call (notify channels).
2. Put system in maintenance (if needed): `php artisan down --message="Investigating outage"`.
3. Stop background jobs and pause consumers:
   - `php artisan queue:restart` (workers will stop after current job)
   - Pause schedulers if applicable.

Rollback steps

A. Fast rollback (preferred when using releases/tags)
- Checkout the previous release tag or commit on the deploy host and redeploy:
  - `git checkout <previous-tag>`
  - `composer install --no-dev --optimize-autoloader`
  - `php artisan migrate` (only if safe; avoid destructive down migrations)
  - Clear caches and restart services
  - `php artisan up`

B. Database rollback (when required)
- If data corruption occurred, restore from backup taken immediately before deploy:
  - Restore DB snapshot and market price export
  - Re-run any post-restore sanity checks

Verification after rollback

- Run the same smoke tests used in deployment playbook.
- Validate historical quote reproducibility for sample inputs.
- Monitor logs and metrics for error reduction.

Post-mortem

- Record the incident, root cause, and remediation in an incident report.
- Update `CHANGELOG_PRICING.md` if the rollback impacts pricing history or versions.
- Plan a safe re-deploy after fixes and additional testing.

Contacts

- On-call: @lfjproj/devops
- Pricing owners: @lfjproj/pricing

