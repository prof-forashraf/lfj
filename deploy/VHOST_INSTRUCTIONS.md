# Configure Apache Virtual Host for `lfjproj`

Follow these steps on your Windows machine running Wampserver to serve the app at `http://lfjproj.local` instead of using `php artisan serve`.

1. Open Wampserver -> Apache -> httpd-vhosts.conf (or use the Add a Virtual Host tool at `http://localhost/add_vhost.php`).
2. Copy the contents of `deploy/lfjproj-vhost.conf` into the vhosts file (or import it in the Add a Virtual Host UI).
3. Edit your hosts file (`C:\Windows\System32\drivers\etc\hosts`) and add:

```
127.0.0.1    lfjproj.local
127.0.0.1    www.lfjproj.local
```

4. Ensure `DocumentRoot` points to `C:/wamp/www/lfjproj/backend/public` (the provided snippet is configured that way).
5. Restart Wampserver (or Apache) so the new vhost is registered.
6. Visit `http://lfjproj.local/` in your browser. If you still see a Wamp landing page, ensure no conflicting vhost declarations exist and that `lfjproj.local` resolves to 127.0.0.1.

Troubleshooting notes:
- If you get a 500 error on the Laravel entry, check `C:/wamp/www/lfjproj/backend/storage/logs/laravel.log` and Apache error logs.
- Ensure PHP extensions required by the app are enabled and that `vendor/` is present (run `composer install` in `backend` if needed).
- If permissions are an issue on Windows, ensure the Apache user can read the project files.

Automation and verification
---------------------------

I added two helper scripts in `deploy/` to make local setup and verification easier:

- `setup-vhost.ps1` — prompts and (when run as Administrator) appends hosts entries and attempts to import the vhost snippet into common Wamp Apache vhosts files. Review before running.
- `fix-wamp-vhosts.ps1` — replaces `${APACHE_LOG_DIR}` occurrences in Wamp vhosts with `${INSTALL_DIR}/logs`, backs up files, tests Apache config, and restarts the `wampapache64` service.
- `smoke-test.php` — a small CLI smoke-test you can run with PHP to check important routes. Example:

```powershell
cd C:\wamp\www\lfjproj\deploy
php smoke-test.php http://127.0.0.1:8000
```

Recommended long-term workflow
-----------------------------

1. Use the Laravel dev server and Vite for development (`php artisan serve` in `backend`, `npm run dev` in `frontend`). This is fast and isolates the app from system-level Apache differences.
2. For integration testing and QA, configure the Apache vhost (either manually or using `setup-vhost.ps1`) so the app runs on port 80 as `lfjproj.local`.
3. Run `php deploy/smoke-test.php http://lfjproj.local` to validate the main site routes after vhost setup.
4. For CI, consider adding a workflow that runs `composer install`, executes the test suite (Pest/PHPUnit) using SQLite, and runs the smoke-test against a short-lived PHP server started by the runner. I can add a CI workflow file if you want.
