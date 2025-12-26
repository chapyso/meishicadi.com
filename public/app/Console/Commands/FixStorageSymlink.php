<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class FixStorageSymlink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-symlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create storage symlink and ensure directories exist';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fixing storage symlink and directories...');

        // Create necessary directories
        $directories = [
            storage_path('app/public/card_logo'),
            storage_path('app/public/card_banner'),
            storage_path('app/public/service_images'),
            storage_path('app/public/product_images'),
            storage_path('app/public/testimonials_images'),
            storage_path('app/public/gallery'),
            storage_path('app/public/meta_image'),
            storage_path('app/public/qrcode'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info("Created directory: $directory");
            }
        }

        // Remove existing symlink if it exists
        $symlinkPath = public_path('storage');
        if (File::exists($symlinkPath) || is_link($symlinkPath)) {
            if (is_link($symlinkPath)) {
                unlink($symlinkPath);
                $this->info('Removed existing symlink');
            } else {
                File::deleteDirectory($symlinkPath);
                $this->info('Removed existing directory');
            }
        }

        // Create the symlink
        try {
            Artisan::call('storage:link');
            $this->info('✓ Storage symlink created successfully');
        } catch (\Exception $e) {
            // Try manual symlink creation
            if (PHP_OS_FAMILY !== 'Windows') {
                $target = storage_path('app/public');
                symlink($target, $symlinkPath);
                $this->info('✓ Storage symlink created manually');
            } else {
                $this->error('Could not create symlink on Windows. Please run: php artisan storage:link');
            }
        }

        // Set permissions
        $storagePath = storage_path('app/public');
        if (PHP_OS_FAMILY !== 'Windows') {
            chmod($storagePath, 0755);
            $this->info('✓ Permissions set');
        }

        $this->info('');
        $this->info('Storage setup complete!');
        $this->info('Verify by checking: ' . public_path('storage'));
        
        return 0;
    }
}

