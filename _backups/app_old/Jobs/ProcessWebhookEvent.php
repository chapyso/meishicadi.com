<?php

namespace App\Jobs;

use App\Models\Integration;
use App\Services\WebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWebhookEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $tries = 3;

    protected $event;
    protected $eventData;

    /**
     * Create a new job instance.
     */
    public function __construct(string $event, array $eventData)
    {
        $this->event = $event;
        $this->eventData = $eventData;
    }

    /**
     * Execute the job.
     */
    public function handle(WebhookService $webhookService): void
    {
        try {
            Log::info('Processing webhook event', [
                'event' => $this->event,
                'data' => $this->eventData
            ]);

            // Get all active webhook integrations that have this event enabled
            $integrations = Integration::active()
                ->ofType('webhook')
                ->whereJsonContains('events', $this->event)
                ->get();

            foreach ($integrations as $integration) {
                try {
                    $webhookService->sendEventToAllIntegrations($this->event, $this->eventData);
                    
                    Log::info('Webhook event processed successfully', [
                        'integration_id' => $integration->id,
                        'event' => $this->event
                    ]);

                } catch (\Exception $e) {
                    Log::error('Failed to process webhook event for integration', [
                        'integration_id' => $integration->id,
                        'event' => $this->event,
                        'error' => $e->getMessage()
                    ]);

                    // Update integration error status
                    $integration->update([
                        'last_error_at' => now(),
                        'last_error_message' => $e->getMessage(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to process webhook event', [
                'event' => $this->event,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Webhook event job failed', [
            'event' => $this->event,
            'error' => $exception->getMessage()
        ]);
    }
}
