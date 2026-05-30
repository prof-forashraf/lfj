# fix-wamp-vhosts.ps1
# Run as Administrator. Scans common Wamp Apache vhosts files and replaces ${APACHE_LOG_DIR} with ${INSTALL_DIR}/logs.

function Is-Admin {
    $current = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
    return $current.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

if (-not (Is-Admin)) {
    Write-Host "This script must be run as Administrator. Right-click -> Run as Administrator." -ForegroundColor Yellow
    exit 1
}

$wampRoots = @("C:\\wamp64","C:\\wamp")
$pattern = '\$\{APACHE_LOG_DIR\}'
$repl = '${INSTALL_DIR}/logs'
$edited = @()

foreach ($root in $wampRoots) {
    if (Test-Path $root) {
        $apacheBins = Join-Path $root 'bin\apache'
        if (Test-Path $apacheBins) {
            $apacheDirs = Get-ChildItem -Path $apacheBins -Directory -ErrorAction SilentlyContinue
            foreach ($dir in $apacheDirs) {
                $vhostsPath = Join-Path $dir.FullName 'conf\extra\httpd-vhosts.conf'
                if (Test-Path $vhostsPath) {
                    try {
                        $bak = "$vhostsPath.bak.$((Get-Date).ToString('yyyyMMddHHmmss'))"
                        Copy-Item -LiteralPath $vhostsPath -Destination $bak -Force
                        (Get-Content -LiteralPath $vhostsPath) -replace $pattern, $repl | Set-Content -LiteralPath $vhostsPath -Force
                        Write-Host "Replaced APACHE_LOG_DIR in $vhostsPath (backup: $bak)" -ForegroundColor Green
                        $edited += $vhostsPath
                    } catch {
                        Write-Host ("Failed to edit {0}: {1}" -f $vhostsPath, $_) -ForegroundColor Red
                    }
                }
            }
        }
    }
}

if ($edited.Count -eq 0) {
    Write-Host "No vhosts files found/edited in common Wamp locations." -ForegroundColor Yellow
    exit 0
}

# Find httpd.exe for the first edited directory to test config
$first = $edited[0]
# $first is like: C:\wamp\bin\apache\apache2.4.65\conf\extra\httpd-vhosts.conf
try {
    $vhostsDir = Split-Path $first -Parent          # ...\conf\extra
    $confDir = Split-Path $vhostsDir -Parent       # ...\conf
    $apacheRoot = Split-Path $confDir -Parent      # ...\apache2.4.65
    $apacheBin = Join-Path $apacheRoot 'bin\httpd.exe'
} catch {
    $apacheBin = $null
}

# Fallback search if we couldn't compute path
if (-not $apacheBin -or -not (Test-Path $apacheBin)) {
    $apacheBin = Get-ChildItem C:\wamp* -Filter httpd.exe -Recurse -ErrorAction SilentlyContinue | Select-Object -First 1 -ExpandProperty FullName -ErrorAction SilentlyContinue
}

if ($apacheBin -and (Test-Path $apacheBin)) {
    Write-Host "Testing Apache config with: $apacheBin -t"
    & $apacheBin -t
    $lastExit = $LASTEXITCODE
    if ($lastExit -eq 0) {
        Write-Host "Apache config OK. Restarting wampapache64 service..." -ForegroundColor Green
        sc stop wampapache64
        Start-Sleep -Seconds 2
        sc start wampapache64
    } else {
        Write-Host ("Apache config test failed. Inspect {0} and its backup." -f $first) -ForegroundColor Red
    }
} else {
    Write-Host "Could not locate httpd.exe to test configuration. Please run 'httpd.exe -t' manually." -ForegroundColor Yellow
}
