<?php

/**
 * Script to find all Bahrain business cards and enable HTML Code Description
 * 
 * This script finds all cards with Bahrain phone numbers (+973) and enables
 * the HTML Code Description feature for them
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Business;
use App\Models\ContactInfo;

// Load the helper function
require_once __DIR__.'/app/helpers.php';

echo "========================================\n";
echo "Finding All Bahrain Business Cards\n";
echo "and Enabling HTML Code Description\n";
echo "========================================\n\n";

// Get all businesses with their contact info
$businesses = Business::with('contactInfo')->get();

$bahrainCards = [];
$updatedCount = 0;
$alreadyEnabledCount = 0;
$totalCards = 0;

foreach ($businesses as $business) {
    $totalCards++;
    
    // Get country information from contact info
    $countryInfo = get_business_country_from_contactinfo($business->contactInfo);
    
    // Check if it's Bahrain
    if ($countryInfo['country'] === 'Bahrain') {
        $bahrainCards[] = $business;
        
        // Check current status
        $currentStatus = $business->is_custom_html_enabled;
        
        // Enable HTML Code Description
        $business->is_custom_html_enabled = '1';
        $business->save();
        
        if ($currentStatus == '1') {
            $alreadyEnabledCount++;
            echo "✓ Card #{$business->id} ({$business->title}) - Already enabled\n";
        } else {
            $updatedCount++;
            echo "✓ Card #{$business->id} ({$business->title}) - HTML Code Description ENABLED\n";
        }
    }
}

// Display summary
echo "\n========================================\n";
echo "Summary\n";
echo "========================================\n";
echo "Total Business Cards Scanned: {$totalCards}\n";
echo "Bahrain Cards Found: " . count($bahrainCards) . "\n";
echo "Cards Updated (enabled): {$updatedCount}\n";
echo "Cards Already Enabled: {$alreadyEnabledCount}\n";

if (count($bahrainCards) > 0) {
    echo "\n========================================\n";
    echo "Bahrain Business Cards List\n";
    echo "========================================\n\n";
    
    foreach ($bahrainCards as $index => $card) {
        echo ($index + 1) . ". ID: {$card->id}\n";
        echo "   Title: {$card->title}\n";
        echo "   Slug: {$card->slug}\n";
        if (!empty($card->designation)) {
            echo "   Designation: {$card->designation}\n";
        }
        echo "   HTML Enabled: " . ($card->is_custom_html_enabled ?? 'NULL') . "\n";
        echo "\n";
    }
}

echo "\n========================================\n";
echo "All Bahrain cards now have HTML Code Description enabled!\n";
echo "========================================\n";

