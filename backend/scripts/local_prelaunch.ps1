<#
PowerShell Local Prelaunch Script for LFJProj Backend
Usage: Run from repository `backend` folder in PowerShell 7+.
This script performs environment setup and runs pricing-domain checks locally.
It does not push or create PRs automatically.
#>

param(
    [switch] $RunMigrations,
    [switch] $SeedDatabase
)

function Check-Command($name) {
    $cmd = Get-Command $name -ErrorAction SilentlyContinue
    if (-not $cmd) {
        Write-Error "Required command '$name' not found in PATH. Install and re-run."
        exit 2
    }
}

Write-Host "== Local prelaunch: LFJProj backend =="

# Prereqs
Check-Command php
Check-Command composer

# Optional tools
Write-Host "Checking optional tools: gh (GitHub CLI)"
if (Get-Command gh -ErrorAction SilentlyContinue) { Write-Host "gh available" } else { Write-Host "gh not found — PR creation will be manual" }

# Environment
Copy-Item -Path .env.example -Destination .env -Force
php artisan key:generate

# SQLite setup
if (-not (Test-Path -Path database)) { New-Item -ItemType Directory -Path database | Out-Null }
if (-not (Test-Path -Path database/database.sqlite)) { New-Item -ItemType File -Path database/database.sqlite | Out-Null }

# Recommended env overrides (session only)
$env:APP_ENV = 'local'
$env:DB_CONNECTION = 'sqlite'
$env:DB_DATABASE = Resolve-Path database/database.sqlite
$env:CACHE_DRIVER = 'array'
$env:QUEUE_CONNECTION = 'sync'
$env:SESSION_DRIVER = 'array'

Write-Host "Installing composer dependencies..."
composer install --no-interaction --prefer-dist

if ($RunMigrations) {
    Write-Host "Running migrations (force)"
    php artisan migrate --force
    if ($SeedDatabase) { php artisan db:seed }
}

Write-Host "Running formatting check (Pint)..."
php vendor/bin/pint --test app/Domain/Pricing tests/Unit/Services
if ($LASTEXITCODE -ne 0) { Write-Error "Pint check failed"; exit $LASTEXITCODE }

Write-Host "Running PHPStan (Larastan)..."
php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G
if ($LASTEXITCODE -ne 0) { Write-Error "PHPStan failed"; exit $LASTEXITCODE }

Write-Host "Running pricing tests (Pest)..."
php vendor/bin/pest tests/Unit/PricingEngineTest.php tests/Unit/Services --colors=never
if ($LASTEXITCODE -ne 0) { Write-Error "Pricing tests failed"; exit $LASTEXITCODE }

Write-Host "Optional: run full unit test suite"
Write-Host "php artisan test --testsuite=Unit"

Write-Host "Smoke tests: call pricing APIs manually or run provided scripts"
Write-Host "python run_api_test.py || python run_origin.py"

Write-Host "Local prelaunch checks completed. Address any failures before staging/production deploy."
