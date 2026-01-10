<?php

/**
 * Script to add HTML code to all Saudi Arabia business cards
 * 
 * This script adds the provided HTML code (address + Google Maps) to the
 * HTML Code Description field for all Saudi Arabia cards
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Business;
use App\Models\ContactInfo;

// Load the helper function
require_once __DIR__.'/app/helpers.php';

// HTML code to add
$htmlCode = '<div style="text-align: left; color: #907C6A; font-family: Montserrat;">    Tadawul Tower, 9th Floor – East Financial Boulevard St,<br> KAFD P.O. Box 13519, Riyadh,<br> Kingdom of Saudi Arabia</div><div style="overflow: hidden; padding-top: 56.25%; position: relative; margin: 0 auto;">    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3622.932878724205!2d46.6425345!3d24.7634904!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2ee3d3e39ba929%3A0xd0cd957ba6199d93!2sTadawul%20Tower%20Project!5e0!3m2!1sen!2sbh!4v1706779605648!5m2!1sen!2sbh" frameborder="0" style="border:0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;" allowfullscreen="" aria-hidden="false" tabindex="0" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';

echo "========================================\n";
echo "Adding HTML Code to All Saudi Arabia\n";
echo "Business Cards\n";
echo "========================================\n\n";

// Get all businesses with their contact info
$businesses = Business::with('contactInfo')->get();

$saudiCards = [];
$updatedCount = 0;
$skippedCount = 0;
$totalCards = 0;

foreach ($businesses as $business) {
    $totalCards++;
    
    // Get country information from contact info
    $countryInfo = get_business_country_from_contactinfo($business->contactInfo);
    
    // Check if it's Saudi Arabia
    if ($countryInfo['country'] === 'Saudi Arabia') {
        $saudiCards[] = $business;
        
        // Clean the HTML code (remove line breaks as per the controller logic)
        $cleanHtml = str_replace(array("\r\n"), "", $htmlCode);
        
        // Update the business card
        $business->custom_html_text = $cleanHtml;
        $business->is_custom_html_enabled = '1';
        $business->save();
        
        $updatedCount++;
        echo "✓ Card #{$business->id} ({$business->title}) - HTML Code added\n";
    }
}

// Display summary
echo "\n========================================\n";
echo "Summary\n";
echo "========================================\n";
echo "Total Business Cards Scanned: {$totalCards}\n";
echo "Saudi Arabia Cards Found: " . count($saudiCards) . "\n";
echo "Cards Updated: {$updatedCount}\n";
echo "Cards Skipped: {$skippedCount}\n";
echo "\n========================================\n";
echo "HTML Code has been added to all Saudi Arabia cards!\n";
echo "The code includes:\n";
echo "- Address: Tadawul Tower, 9th Floor – East Financial Boulevard St,\n";
echo "           KAFD P.O. Box 13519, Riyadh, Kingdom of Saudi Arabia\n";
echo "- Google Maps embed\n";
echo "========================================\n";

