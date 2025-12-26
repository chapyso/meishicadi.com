<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Business;

// Find Neha's user account
$nehaUser = User::where('name', 'LIKE', '%Neha%')
    ->orWhere('email', 'LIKE', '%neha%')
    ->first();

if (!$nehaUser) {
    echo "Neha user not found!\n";
    exit(1);
}

// Get creator ID - if Neha is a company user, use her ID, otherwise use created_by
$creatorId = $nehaUser->type == 'company' ? $nehaUser->id : $nehaUser->created_by;

// Get all businesses created by Neha
$businesses = Business::where('created_by', $creatorId)
    ->orderBy('title', 'asc')
    ->get();

$appUrl = env('APP_URL', 'https://consulum.meishicadi.com');
$appUrl = trim($appUrl, '/');
$urls = [];

echo "Neha Siddique's Business Card URLs ({$businesses->count()} cards)\n";
echo str_repeat("=", 80) . "\n\n";

foreach ($businesses as $business) {
    $url = '';
    
    // Check if custom domain is enabled
    if (!empty($business->enable_domain) && $business->enable_domain == 'on' && !empty($business->domains)) {
        $domain = $business->domains;
        if (!preg_match('/^https?:\/\//', $domain)) {
            $url = 'https://' . $domain;
        } else {
            $url = $domain;
        }
    }
    // Check if subdomain is enabled
    elseif (!empty($business->enable_subdomain) && $business->enable_subdomain == 'on' && !empty($business->subdomain)) {
        $subdomain = $business->subdomain;
        if (!preg_match('/^https?:\/\//', $subdomain)) {
            $url = 'https://' . $subdomain;
        } else {
            $url = $subdomain;
        }
    }
    // Default slug-based URL
    else {
        $url = $appUrl . '/' . $business->slug;
    }
    
    $urls[] = [
        'title' => $business->title ?? 'Untitled',
        'slug' => $business->slug,
        'url' => $url
    ];
    
    echo sprintf("%-3d. %-40s | %s\n", count($urls), $business->title ?? 'Untitled', $url);
}

// Also save to a text file
$outputFile = 'neha_card_urls.txt';
file_put_contents($outputFile, "Neha Siddique's Business Card URLs ({$businesses->count()} cards)\n");
file_put_contents($outputFile, str_repeat("=", 80) . "\n\n", FILE_APPEND);

foreach ($urls as $index => $item) {
    file_put_contents($outputFile, sprintf("%-3d. %-40s | %s\n", $index + 1, $item['title'], $item['url']), FILE_APPEND);
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ URLs have been saved to: {$outputFile}\n";
echo "Total: {$businesses->count()} cards\n";

