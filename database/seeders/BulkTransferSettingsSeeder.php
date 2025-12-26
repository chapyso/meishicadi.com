<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BulkTransferSetting;

class BulkTransferSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create global settings
        BulkTransferSetting::create([
            'plan_id' => null, // Global settings
            'feature_enabled' => true,
            'max_file_size_mb' => 2048, // 2GB
            'retention_hours' => 72, // 3 days
            'password_protection_enabled' => true,
            'daily_transfer_limit' => 10,
            'monthly_transfer_limit' => 100,
            'max_storage_gb' => 10
        ]);

        // Create settings for existing plans (if any)
        $plans = \App\Models\Plan::all();
        
        foreach ($plans as $plan) {
            // Premium plans get higher limits
            $isPremium = str_contains(strtolower($plan->name), 'premium') || 
                        str_contains(strtolower($plan->name), 'pro') ||
                        $plan->price > 0;
            
            BulkTransferSetting::create([
                'plan_id' => $plan->id,
                'feature_enabled' => true,
                'max_file_size_mb' => $isPremium ? 5120 : 2048, // 5GB for premium, 2GB for others
                'retention_hours' => $isPremium ? 168 : 72, // 7 days for premium, 3 days for others
                'password_protection_enabled' => true,
                'daily_transfer_limit' => $isPremium ? 25 : 10,
                'monthly_transfer_limit' => $isPremium ? 250 : 100,
                'max_storage_gb' => $isPremium ? 25 : 10
            ]);
        }
    }
}
