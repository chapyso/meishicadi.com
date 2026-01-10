<?php

namespace App\Console\Commands;

use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class EnableCustomHtmlForAllBusinesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:enable-custom-html {--dry-run : Show how many rows would change without saving} {--chunk=200 : Chunk size for processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn on the Custom HTML feature for every business record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasTable('businesses') || !Schema::hasColumn('businesses', 'is_custom_html_enabled')) {
            $this->error('The businesses table or is_custom_html_enabled column is missing.');

            return Command::FAILURE;
        }

        $chunkSize = max(1, (int) $this->option('chunk'));
        $dryRun = (bool) $this->option('dry-run');

        $total = Business::count();
        if ($total === 0) {
            $this->info('No businesses found to update.');

            return Command::SUCCESS;
        }

        $this->info("Processing {$total} businesses" . ($dryRun ? ' (dry run).' : '.'));

        $updated = 0;
        Business::chunkById($chunkSize, function ($businesses) use (&$updated, $dryRun) {
            /** @var \App\Models\Business $business */
            foreach ($businesses as $business) {
                if ($business->is_custom_html_enabled === '1') {
                    continue;
                }

                if (!$dryRun) {
                    $business->is_custom_html_enabled = '1';
                    $business->save();
                }

                $updated++;
            }
        });

        if ($dryRun) {
            $this->info("Dry run complete. {$updated} of {$total} businesses would be updated.");
        } else {
            $this->info("Custom HTML enabled for {$updated} of {$total} businesses.");
        }

        return Command::SUCCESS;
    }
}
