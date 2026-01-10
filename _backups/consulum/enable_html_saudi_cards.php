<?php

/**
 * Script to enable HTML Code Description for all Saudi Arabia business cards
 * 
 * This script enables the custom HTML feature for all cards with Saudi Arabia phone numbers
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Business;
use App\Models\ContactInfo;

// Load the helper function
require_once __DIR__.'/app/helpers.php';

echo "========================================\n";
echo "Enabling HTML Code Description for\n";
echo "All Saudi Arabia Business Cards\n";
echo "========================================\n\n";

// Get all businesses with their contact info
$businesses = Business::with('contactInfo')->get();

$saudiCards = [];
$updatedCount = 0;
$alreadyEnabledCount = 0;
$totalCards = 0;

foreach ($businesses as $business) {
    $totalCards++;
    
    // Get country information from contact info
    $countryInfo = get_business_country_from_contactinfo($business->contactInfo);
    
    // Check if it's Saudi Arabia
    if ($countryInfo['country'] === 'Saudi Arabia') {
        $saudiCards[] = $business;
        
        // Check current status
        $currentStatus = $business->is_custom_html_enabled;
        
        // Enable HTML Code Description
        $business->is_custom_html_enabled = '1';
        
        // If custom_html_text is empty, we'll leave it empty (user can fill it later)
        // But we ensure the field exists
        if (empty($business->custom_html_text)) {
            // Keep it empty - just enable the feature
        }
        
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
echo "Saudi Arabia Cards Found: " . count($saudiCards) . "\n";
echo "Cards Updated (enabled): {$updatedCount}\n";
echo "Cards Already Enabled: {$alreadyEnabledCount}\n";
echo "\n========================================\n";
echo "All Saudi Arabia cards now have HTML Code Description enabled!\n";
echo "========================================\n";

