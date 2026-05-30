Param()
Set-StrictMode -Version Latest
$RepoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path | Split-Path -Parent
Set-Location $RepoRoot

Write-Host 'Building frontend...'
if (Test-Path './frontend') {
    if (Get-Command npm -ErrorAction SilentlyContinue) {
        Push-Location ./frontend
        npm ci
        npm run build
        Pop-Location
    } else {
        Write-Error 'npm not found; please build frontend manually and place dist/ into frontend/dist'
        exit 2
    }
}

Write-Host 'Merging frontend dist into backend/public (dry-run)'
node scripts/safe_copy_dist_and_report.js

Write-Host 'Merging frontend dist into backend/public (apply)'
node scripts/safe_copy_dist_and_report.js --apply

Set-Location ./backend
if (Get-Command composer -ErrorAction SilentlyContinue) {
    Write-Host 'Installing composer deps (--no-dev)'
    composer install --no-dev --no-interaction --optimize-autoloader
} else {
    Write-Warning 'composer not found; please run composer install on a machine with PHP/composer and upload vendor/'
}

Set-Location $RepoRoot
$DeployZip = Join-Path $RepoRoot 'deploy-package.zip'
if (Test-Path $DeployZip) { Remove-Item $DeployZip -Force }
Write-Host "Creating deploy-package.zip at $DeployZip (backend only)"
Add-Type -AssemblyName System.IO.Compression.FileSystem
[IO.Compression.ZipFile]::CreateFromDirectory((Resolve-Path ./backend).Path, $DeployZip)

Write-Host 'Done. Upload deploy-package.zip to your cPanel account and extract.'
