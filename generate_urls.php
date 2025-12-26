<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vcard;

$baseUrl = config('app.url');
$urls = [];

// Get all vcard URLs
$vcards = Vcard::select('url_alias')->get();
foreach ($vcards as $vcard) {
    $urls[] = $baseUrl . '/' . $vcard->url_alias;
}

// Add common routes
$commonRoutes = [
    '/',
    '/login',
    '/register',
    '/forgot-password',
    '/sitemap.xml',
];

foreach ($commonRoutes as $route) {
    $urls[] = $baseUrl . $route;
}

// Output URLs
echo "=== ALL INDEXED URLs ===\n\n";
foreach ($urls as $url) {
    echo $url . "\n";
}

echo "\n\n=== SUMMARY ===\n";
echo "Total URLs found: " . count($urls) . "\n";
echo "Base URL: " . $baseUrl . "\n";

