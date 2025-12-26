<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LogoSliderController;
use Illuminate\Support\Facades\Cache;

class UpdateLogoSliderCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logo-slider:update-cache {--force : Force update even if cache exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the logo slider cache with fresh business logos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Updating logo slider cache...');

        try {
            $controller = new LogoSliderController();
            
            // Check if cache exists and force flag
            if (!$this->option('force') && Cache::has('business_logos_public')) {
                $this->warn('Cache already exists. Use --force to update anyway.');
                return 0;
            }

            // Update public logos cache
            $this->info('📊 Fetching public logos...');
            $publicLogos = $controller->getPublicLogos(request());
            if ($publicLogos->getData()->success) {
                Cache::put('business_logos_public', $publicLogos->getData(), now()->addHours(6));
                $this->info('✅ Public logos cache updated: ' . count($publicLogos->getData()->data) . ' logos');
            } else {
                $this->error('❌ Failed to update public logos cache');
            }

            // Update admin logos cache
            $this->info('👤 Fetching admin logos...');
            $adminLogos = $controller->getAdminLogos(request());
            if ($adminLogos->getData()->success) {
                Cache::put('business_logos_admin', $adminLogos->getData(), now()->addHours(6));
                $this->info('✅ Admin logos cache updated: ' . count($adminLogos->getData()->data) . ' logos');
            } else {
                $this->error('❌ Failed to update admin logos cache');
            }

            // Clear old cache entries
            $this->info('🧹 Cleaning up old cache entries...');
            $this->cleanupOldCache();

            $this->info('🎉 Logo slider cache update completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error updating logo slider cache: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clean up old cache entries
     */
    private function cleanupOldCache()
    {
        $keys = [
            'business_logos_public',
            'business_logos_admin',
            'logo_slider_cache_*'
        ];

        foreach ($keys as $key) {
            if (str_contains($key, '*')) {
                // Handle wildcard keys
                $pattern = str_replace('*', '', $key);
                $cachedKeys = Cache::get('cache_keys', []);
                foreach ($cachedKeys as $cachedKey) {
                    if (str_starts_with($cachedKey, $pattern)) {
                        Cache::forget($cachedKey);
                    }
                }
            } else {
                // Handle specific keys
                if (Cache::has($key)) {
                    $this->line("   - Cleared: {$key}");
                }
            }
        }
    }
} 