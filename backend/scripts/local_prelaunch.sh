#!/usr/bin/env bash
# Local prelaunch script (bash)
# Usage: ./scripts/local_prelaunch.sh [--migrate] [--seed]
set -euo pipefail
SCRIPT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
REPO_ROOT="$SCRIPT_DIR/.."
cd "$REPO_ROOT/backend" || cd "$REPO_ROOT"

RUN_MIGRATE=0
SEED=0
while [[ $# -gt 0 ]]; do
  case $1 in
    --migrate) RUN_MIGRATE=1; shift;;
    --seed) SEED=1; shift;;
    *) shift;;
  esac
done

command -v php >/dev/null || { echo "php is required"; exit 2; }
command -v composer >/dev/null || { echo "composer is required"; exit 2; }

cp .env.example .env || true
php artisan key:generate || true

mkdir -p database
: > database/database.sqlite

export APP_ENV=local
export DB_CONNECTION=sqlite
export DB_DATABASE=$(pwd)/database/database.sqlite
export CACHE_DRIVER=array
export QUEUE_CONNECTION=sync
export SESSION_DRIVER=array

composer install --no-interaction --prefer-dist

if [[ $RUN_MIGRATE -eq 1 ]]; then
  php artisan migrate --force
  if [[ $SEED -eq 1 ]]; then
    php artisan db:seed
  fi
fi

# Run checks
php vendor/bin/pint --test app/Domain/Pricing tests/Unit/Services
php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G
php vendor/bin/pest tests/Unit/PricingEngineTest.php tests/Unit/Services --colors=never

echo "Run smoke tests: python run_api_test.py or python run_origin.py"

echo "Local prelaunch finished. Fix any failures before staging/production." 
