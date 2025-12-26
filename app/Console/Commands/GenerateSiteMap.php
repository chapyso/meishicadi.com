<?php

namespace App\Console\Commands;

use App\Models\Vcard;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a site map for your vcard view urls';

    /**
     * Execute the console command.
     * DISABLED: This command now generates an empty sitemap to prevent search engine indexing.
     */
    public function handle(): void
    {
        // Generate empty sitemap to prevent any URLs from being indexed
        $emptySitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
                        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" .
                        '</urlset>';
        
        file_put_contents(public_path('sitemap.xml'), $emptySitemap);
        
        $this->info('Empty sitemap generated. No URLs will be indexed.');
    }
}
