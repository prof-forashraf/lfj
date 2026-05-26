#!/usr/bin/env bash
set -euo pipefail
REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

echo "Building frontend..."
if [ -d "frontend" ]; then
  cd frontend
  if command -v npm >/dev/null 2>&1; then
    npm ci
    npm run build
  else
    echo "npm not found; please build frontend manually and place dist/ into frontend/dist" >&2
    exit 2
  fi
  cd - >/dev/null
fi

echo "Merging frontend dist into backend/public (dry-run)..."
node scripts/safe_copy_dist_and_report.js || true

echo "Merging frontend dist into backend/public (apply)..."
node scripts/safe_copy_dist_and_report.js --apply

cd backend
if command -v composer >/dev/null 2>&1; then
  echo "Installing composer deps (--no-dev)..."
  composer install --no-dev --no-interaction --optimize-autoloader
else
  echo "composer not found; please run composer install on a machine with PHP/composer and upload vendor/" >&2
fi

cd "$REPO_ROOT"
echo "Creating deploy-package.zip (backend only)..."
rm -f deploy-package.zip
cd backend
zip -r ../deploy-package.zip . -x ".git/*" "node_modules/*" "../frontend/*" "tests/*"
cd - >/dev/null

echo "Done. Upload deploy-package.zip to your cPanel account and extract."
