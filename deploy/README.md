# Deploy package (cPanel) — produce a ready-to-upload zip

This guide and helper scripts create a production-ready zip you can upload to cPanel (via File Manager or SFTP).

Workflow (recommended):

1. Run the helper script (bash or PowerShell) on your dev machine where Node and optionally Composer are available.
2. The script will:
   - Install frontend deps and build `frontend/dist`.
   - Merge `frontend/dist` into `backend/public` using `scripts/safe_copy_dist_and_report.js`.
   - Run `composer install --no-dev --optimize-autoloader` if `composer` is present.
   - Generate `deploy-package.zip` containing the `backend` folder ready for upload.

Usage (Linux/macOS):
```bash
./scripts/create_deploy_package.sh
```

Usage (Windows PowerShell):
```powershell
.\scripts\create_deploy_package.ps1
```

After upload to cPanel, extract the zip outside the public webroot and set the domain DocumentRoot to the extracted `backend/public`.

See the main repository README for further production checklist items (cron, SSL, permissions).
