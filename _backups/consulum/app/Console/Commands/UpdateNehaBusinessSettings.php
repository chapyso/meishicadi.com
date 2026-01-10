<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Business;
use App\Models\ContactInfo;
use App\Models\social;

class UpdateNehaBusinessSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neha:update-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all Neha business cards settings: Contact Info, Social, Custom HTML (On), Google Maps (Off)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Finding Neha Siddique user...');
        
        // Find Neha's user account
        $nehaUser = User::where('name', 'LIKE', '%Neha%')
            ->orWhere('email', 'LIKE', '%neha%')
            ->first();
        
        if (!$nehaUser) {
            $this->error('Neha user not found!');
            return 1;
        }
        
        $this->info("Found user: {$nehaUser->name} (ID: {$nehaUser->id})");
        
        // Get creator ID - if Neha is a company user, use her ID, otherwise use created_by
        $creatorId = $nehaUser->type == 'company' ? $nehaUser->id : $nehaUser->created_by;
        
        // Find all businesses created by Neha
        $businesses = Business::where('created_by', $creatorId)->get();
        
        if ($businesses->isEmpty()) {
            $this->error('No businesses found for Neha!');
            return 1;
        }
        
        $this->info("Found {$businesses->count()} businesses to update.");
        
        $updated = 0;
        
        foreach ($businesses as $business) {
            $this->info("Updating business ID: {$business->id} - {$business->title}");
            
            // Update ContactInfo
            $contactInfo = ContactInfo::where('business_id', $business->id)->first();
            if ($contactInfo) {
                $contactInfo->is_enabled = '1';
                $contactInfo->save();
                $this->line("  ✓ Contact Info enabled");
            } else {
                ContactInfo::create([
                    'business_id' => $business->id,
                    'is_enabled' => '1',
                    'created_by' => $creatorId
                ]);
                $this->line("  ✓ Contact Info created and enabled");
            }
            
            // Update Social
            $social = social::where('business_id', $business->id)->first();
            if ($social) {
                $social->is_enabled = '1';
                $social->save();
                $this->line("  ✓ Social enabled");
            } else {
                social::create([
                    'business_id' => $business->id,
                    'is_enabled' => '1',
                    'created_by' => $creatorId
                ]);
                $this->line("  ✓ Social created and enabled");
            }
            
            // Update Custom HTML
            $business->is_custom_html_enabled = '1';
            $business->save();
            $this->line("  ✓ Custom HTML enabled");
            
            // Update Google Maps (set to Off/0 as per image 1)
            $business->is_map_iframe_enabled = '0';
            $business->save();
            $this->line("  ✓ Google Maps disabled (as per image 1)");
            
            $updated++;
        }
        
        $this->info("\n✅ Successfully updated {$updated} businesses!");
        return 0;
    }
}



