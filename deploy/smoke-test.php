<?php
// Simple CLI smoke test for local routes
// Usage: php smoke-test.php http://127.0.0.1:8000

$routes = [
    '/',
    '/shop',
    '/shop/collection/new-arrivals',
    '/shop/advanced-search',
    '/tools',
    '/tools/gold-prices',
    '/tools/carat-converter',
    '/blog',
    '/about',
    '/contact',
    '/login',
    '/register',
];

$base = $argv[1] ?? 'http://127.0.0.1:8000';
if (!filter_var($base, FILTER_VALIDATE_URL)) {
    fwrite(STDERR, "Usage: php smoke-test.php http://127.0.0.1:8000\n");
    exit(2);
}

$ok = 0; $total = count($routes);
foreach ($routes as $r) {
    $url = rtrim($base, '/') . $r;
    $opts = [
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true,
            'header' => "User-Agent: lfjproj-smoke-test/1.0\r\n",
        ],
    ];
    $ctx = stream_context_create($opts);
    $start = microtime(true);
    $content = @file_get_contents($url, false, $ctx);
    $dur = microtime(true) - $start;
    $status = 0;
    if (isset($http_response_header)) {
        foreach ($http_response_header as $h) {
            if (preg_match('#^HTTP/\d\.\d\s+(\d{3})#', $h, $m)) { $status = intval($m[1]); break; }
        }
    }
    $line = sprintf("%-40s %3d %6.2fs", $url, $status, $dur);
    if ($status >= 200 && $status < 400) { echo "[OK]  $line\n"; $ok++; }
    else { echo "[BAD] $line\n"; }
}

echo "\nSummary: $ok/$total routes OK\n";
exit($ok === $total ? 0 : 1);
