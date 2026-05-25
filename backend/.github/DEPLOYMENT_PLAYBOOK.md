Deployment Playbook — LFJProj Pricing Backend

Purpose

A concise, repeatable checklist for deploying the backend to production with minimal risk. Follow this checklist during the deployment window.

Pre-deploy (Preparation)

- Ensure all PRs touching `app/Domain/Pricing` are merged to `develop`/`main` and have: PASSing `Pricing Domain Quality` checks, CODEOWNER approvals, and `CHANGELOG_PRICING.md` entries when applicable.
- Ensure monitoring and alerts are configured (APM, error reporting, market price drift alerts).
- Take a fresh backup of production database and market price history export.
- Verify deploy window and notify on-call and stakeholders.

Deployment steps

1. Pull latest tagged release (or merge commit) on the application host.
2. Put system in maintenance mode if required.
   - `php artisan down --message="Deploying pricing update"`
3. Run deploy script / composer install:
   - `composer install --no-dev --optimize-autoloader`
   - `php artisan migrate --force`
   - `php artisan config:cache && php artisan route:cache && php artisan view:cache`
4. Warm caches and precompute any pricing caches if present.
5. Restart PHP / queue workers / web server as needed.
   - `php artisan queue:restart`
   - Restart PHP-FPM / php service and web server.
6. Exit maintenance mode:
   - `php artisan up`

Post-deploy verification (first 15 minutes)

- Run smoke tests: verify quote APIs, resale, zakat endpoints return expected sample outputs.
- Compare a few historical quote reproductions using snapshot inputs; ensure outputs match expected.
- Check logs for errors and monitor APM for latency spikes.
- Verify market price sync job ran successfully (if scheduled) or run `php artisan schedule:run` once.
- Verify cache consistency and that `DailyMetalPrice`/`GoldPrice` ingestion is healthy.

If all checks pass, inform stakeholders and monitor for 24–48 hours for anomalies.

If issues occur, follow the Rollback Playbook.

Notes

- For zero-downtime deploys, ensure workers are drained and use blue/green or canary strategies if available.
- For schema changes that are not backward-compatible, coordinate multi-step deploys and feature flags.

Contact

- On-call: @lfjproj/devops
- Pricing owners: @lfjproj/pricing

