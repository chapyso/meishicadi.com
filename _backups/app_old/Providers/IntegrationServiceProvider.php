<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WebhookService;
use App\Services\HubSpotService;
use App\Services\ZohoService;
use App\Services\SoftchapService;

class IntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WebhookService::class, function ($app) {
            return new WebhookService();
        });

        $this->app->singleton(HubSpotService::class, function ($app) {
            return new HubSpotService();
        });

        $this->app->singleton(ZohoService::class, function ($app) {
            return new ZohoService();
        });

        $this->app->singleton(SoftchapService::class, function ($app) {
            return new SoftchapService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
