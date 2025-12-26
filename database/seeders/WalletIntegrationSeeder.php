<?php

namespace Database\Seeders;

use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class WalletIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Enable wallet integration for premium plans (plans with ID > 1)
        $premiumPlans = PlanFeature::where('plan_id', '>', 1)->get();
        
        foreach ($premiumPlans as $planFeature) {
            $planFeature->update([
                'wallet_integration' => true
            ]);
        }
        
        $this->command->info('Wallet integration enabled for premium plans.');
    }
} 