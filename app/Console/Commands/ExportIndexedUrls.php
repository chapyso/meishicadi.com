<?php

namespace App\Console\Commands;

use App\Models\Vcard;
use Illuminate\Console\Command;

class ExportIndexedUrls extends Command
{
    protected $signature = 'urls:export';
    protected $description = 'Export all indexed URLs for Google Search Console removal';

    public function handle()
    {
        $baseUrl = config('app.url');
        $urls = [];

        // Get all vcard URLs
        $vcards = Vcard::select('url_alias')->get();
        foreach ($vcards as $vcard) {
            if (!empty($vcard->url_alias)) {
                $urls[] = $baseUrl . '/' . $vcard->url_alias;
            }
        }

        // Add common routes
        $commonRoutes = [
            '/',
            '/login',
            '/register',
            '/forgot-password',
            '/sitemap.xml',
        ];

        foreach ($commonRoutes as $route) {
            $urls[] = $baseUrl . $route;
        }

        // Save to file
        $filePath = storage_path('app/indexed_urls.txt');
        file_put_contents($filePath, implode("\n", $urls));

        $this->info("Exported " . count($urls) . " URLs to: " . $filePath);
        $this->info("Base URL: " . $baseUrl);
        
        return 0;
    }
}

