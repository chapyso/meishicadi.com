<?php

/**
 * Script to add HTML code to all Bahrain business cards
 * 
 * This script adds the provided HTML code (address + Google Maps) to the
 * HTML Code Description field for all Bahrain cards
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Business;
use App\Models\ContactInfo;

// Load the helper function
require_once __DIR__.'/app/helpers.php';

// HTML code to add
$htmlCode = '<div style="color: #907C6A; font-family: Montserrat; text-align: left;">    Office 3701, 37th Floor, United Tower,<br>Bahrain Bay P.O. Box 20652,Manama, <br>Kingdom of Bahrain</div><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3578.471024333342!2d50.57417842541657!3d26.24637207705065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49a5ccf6f83089%3A0xa9f41bf586a709a9!2sUnited%20Tower%20Bahrain!5e0!3m2!1sen!2sbh!4v1702501274661!5m2!1sen!2sbh" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

echo "========================================\n";
echo "Adding HTML Code to All Bahrain\n";
echo "Business Cards\n";
echo "========================================\n\n";

// Get all businesses with their contact info
$businesses = Business::with('contactInfo')->get();

$bahrainCards = [];
$updatedCount = 0;
$skippedCount = 0;
$totalCards = 0;

foreach ($businesses as $business) {
    $totalCards++;
    
    // Get country information from contact info
    $countryInfo = get_business_country_from_contactinfo($business->contactInfo);
    
    // Check if it's Bahrain
    if ($countryInfo['country'] === 'Bahrain') {
        $bahrainCards[] = $business;
        
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
echo "Bahrain Cards Found: " . count($bahrainCards) . "\n";
echo "Cards Updated: {$updatedCount}\n";
echo "Cards Skipped: {$skippedCount}\n";
echo "\n========================================\n";
echo "HTML Code has been added to all Bahrain cards!\n";
echo "The code includes:\n";
echo "- Address: Office 3701, 37th Floor, United Tower,\n";
echo "           Bahrain Bay P.O. Box 20652, Manama, Kingdom of Bahrain\n";
echo "- Google Maps embed\n";
echo "========================================\n";

