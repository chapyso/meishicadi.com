<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AssetOptimizer;

class OptimizeAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:optimize {--force : Force re-optimization}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize CSS, JS, and images for better performance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting asset optimization...');

        $optimizer = new AssetOptimizer();

        // Optimize CSS
        $this->info('Optimizing CSS files...');
        try {
            $cssPath = $optimizer->optimizeCSS();
            $this->info("CSS optimized: {$cssPath}");
        } catch (\Exception $e) {
            $this->error("CSS optimization failed: " . $e->getMessage());
        }

        // Optimize JavaScript
        $this->info('Optimizing JavaScript files...');
        try {
            $jsPath = $optimizer->optimizeJS();
            $this->info("JavaScript optimized: {$jsPath}");
        } catch (\Exception $e) {
            $this->error("JavaScript optimization failed: " . $e->getMessage());
        }

        // Generate manifest
        $this->info('Generating asset manifest...');
        try {
            $manifest = $optimizer->generateManifest();
            $this->info('Asset manifest generated successfully');
        } catch (\Exception $e) {
            $this->error("Manifest generation failed: " . $e->getMessage());
        }

        // Optimize images (if force flag is used)
        if ($this->option('force')) {
            $this->info('Optimizing images...');
            try {
                $optimizer->optimizeImages();
                $this->info('Images optimized successfully');
            } catch (\Exception $e) {
                $this->error("Image optimization failed: " . $e->getMessage());
            }
        }

        // Clear caches
        $this->info('Clearing caches...');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('cache:clear');

        $this->info('Asset optimization completed successfully!');
        
        return 0;
    }
} 