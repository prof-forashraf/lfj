# Helper: setup-vhost.ps1
# Run as Administrator. Prompts before changes.

function Is-Admin {
    $current = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
    return $current.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

# If not admin, re-launch elevated to prompt the user for UAC consent.
if (-not (Is-Admin)) {
    Write-Host "Requesting elevation (UAC) to modify system files..." -ForegroundColor Yellow
    $arg = "-NoProfile -ExecutionPolicy Bypass -File `"$PSCommandPath`""
    Start-Process -FilePath "powershell" -ArgumentList $arg -Verb RunAs
    exit 0
}

$hostsFile = "$env:SystemRoot\System32\drivers\etc\hosts"
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$vhostSnippet = Join-Path -Path $scriptDir -ChildPath "lfjproj-vhost.conf"

Write-Host "This script will:`n - Add hosts entries for lfjproj.local`n - Try to append the vhost snippet into common Wamp Apache vhosts files" -ForegroundColor Cyan

$confirm = Read-Host "Proceed? (y/n)"
if ($confirm -ne 'y') { Write-Host 'Aborted.'; exit 0 }

if (-not (Test-Path $vhostSnippet)) {
    Write-Host "Vhost snippet not found at $vhostSnippet — please ensure deploy/lfjproj-vhost.conf exists." -ForegroundColor Red
    exit 1
}

# 1) Add hosts entries (make a backup first)
try {
    $bak = "$hostsFile.bak.$((Get-Date).ToString('yyyyMMddHHmmss'))"
    Copy-Item -LiteralPath $hostsFile -Destination $bak -Force
    Write-Host "Backed up hosts file to $bak" -ForegroundColor Green
} catch {
    Write-Host "Failed to back up hosts file: $_" -ForegroundColor Red
}

$hostsText = Get-Content -Raw -LiteralPath $hostsFile
$entries = @("127.0.0.1    lfjproj.local","127.0.0.1    www.lfjproj.local")
foreach ($e in $entries) {
    $pattern = [regex]::Escape($e)
    if ($hostsText -notmatch $pattern) {
        Write-Host "Adding hosts entry: $e"
        Add-Content -LiteralPath $hostsFile -Value "`r`n$e"
    } else {
        Write-Host "Hosts entry already present: $e" -ForegroundColor Green
    }
}

# 2) Locate Wamp Apache vhosts files and append snippet (back up before appending)
$wampRoots = @("C:\\wamp64","C:\\wamp")
$found = $false
foreach ($root in $wampRoots) {
    if (Test-Path $root) {
        $apacheBins = Join-Path $root 'bin\apache'
        if (Test-Path $apacheBins) {
            $apacheDirs = Get-ChildItem -Path $apacheBins -Directory -ErrorAction SilentlyContinue
            foreach ($dir in $apacheDirs) {
                $vhostsPath = Join-Path $dir.FullName 'conf\extra\httpd-vhosts.conf'
                if (Test-Path $vhostsPath) {
                    Write-Host "Found vhosts file: $vhostsPath"
                    $append = Read-Host "Append our vhost snippet into $vhostsPath? (y/n)"
                    if ($append -eq 'y') {
                        try {
                            $vhostsBak = "$vhostsPath.bak.$((Get-Date).ToString('yyyyMMddHHmmss'))"
                            Copy-Item -LiteralPath $vhostsPath -Destination $vhostsBak -Force
                            Write-Host "Backed up vhosts to $vhostsBak" -ForegroundColor Green
                            Get-Content -LiteralPath $vhostSnippet | Add-Content -LiteralPath $vhostsPath
                            Write-Host "Appended vhost snippet to $vhostsPath" -ForegroundColor Green
                            $found = $true
                        } catch {
                            Write-Host "Failed to append to $vhostsPath: $_" -ForegroundColor Red
                        }
                    } else {
                        Write-Host "Skipped $vhostsPath"
                    }
                }
            }
        }
    }
}

if (-not $found) {
    Write-Host "No Wamp Apache vhosts file found in common locations. Please copy deploy/lfjproj-vhost.conf into your Apache vhosts config manually." -ForegroundColor Yellow
}

Write-Host "Done. Restart Apache/Wampserver to apply vhost changes." -ForegroundColor Cyan
Write-Host "Run Wamp manager or restart the Apache service (e.g., 'wampapache64' service) as appropriate." -ForegroundColor Cyan
