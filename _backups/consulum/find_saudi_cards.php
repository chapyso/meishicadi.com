<?php

/**
 * Script to find all Saudi Arabia business cards
 * 
 * This script searches for all business cards that have phone numbers
 * with Saudi Arabia country code (+966)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Business;
use App\Models\ContactInfo;

// Load the helper function
require_once __DIR__.'/app/helpers.php';

echo "========================================\n";
echo "Finding All Saudi Arabia Business Cards\n";
echo "========================================\n\n";

// Get all businesses with their contact info
$businesses = Business::with('contactInfo')->get();

$saudiCards = [];
$totalCards = 0;

foreach ($businesses as $business) {
    $totalCards++;
    
    // Get country information from contact info
    $countryInfo = get_business_country_from_contactinfo($business->contactInfo);
    
    // Check if it's Saudi Arabia
    if ($countryInfo['country'] === 'Saudi Arabia') {
        $saudiCards[] = [
            'id' => $business->id,
            'title' => $business->title,
            'slug' => $business->slug,
            'designation' => $business->designation,
            'country' => $countryInfo['country'],
            'country_code' => $countryInfo['code'],
            'phone_country_code' => $countryInfo['country_code'],
            'created_by' => $business->created_by,
        ];
    }
}

// Display results
echo "Total Business Cards: {$totalCards}\n";
echo "Saudi Arabia Cards Found: " . count($saudiCards) . "\n\n";

if (count($saudiCards) > 0) {
    echo "========================================\n";
    echo "Saudi Arabia Business Cards List\n";
    echo "========================================\n\n";
    
    foreach ($saudiCards as $index => $card) {
        echo ($index + 1) . ". ID: {$card['id']}\n";
        echo "   Title: {$card['title']}\n";
        echo "   Slug: {$card['slug']}\n";
        if (!empty($card['designation'])) {
            echo "   Designation: {$card['designation']}\n";
        }
        echo "   Country: {$card['country']} ({$card['country_code']})\n";
        echo "   Phone Code: {$card['phone_country_code']}\n";
        echo "   Created By User ID: {$card['created_by']}\n";
        echo "\n";
    }
    
    // Export to CSV
    $csvFile = 'saudi_cards_' . date('Y-m-d_His') . '.csv';
    $fp = fopen($csvFile, 'w');
    
    // CSV Header
    fputcsv($fp, ['ID', 'Title', 'Slug', 'Designation', 'Country', 'Country Code', 'Phone Country Code', 'Created By']);
    
    // CSV Data
    foreach ($saudiCards as $card) {
        fputcsv($fp, [
            $card['id'],
            $card['title'],
            $card['slug'],
            $card['designation'] ?? '',
            $card['country'],
            $card['country_code'],
            $card['phone_country_code'],
            $card['created_by']
        ]);
    }
    
    fclose($fp);
    echo "========================================\n";
    echo "Results exported to: {$csvFile}\n";
    echo "========================================\n";
} else {
    echo "No Saudi Arabia business cards found.\n";
}

