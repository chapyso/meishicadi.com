<?php

namespace App\Services;

use App\Models\Integration;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{
    /**
     * Send a webhook to the specified integration
     */
    public function sendWebhook(Integration $integration, array $payload): array
    {
        if (!$integration->is_active || $integration->type !== 'webhook') {
            return [
                'success' => false,
                'error_message' => 'Integration is not active or invalid type'
            ];
        }

        $webhookUrl = $integration->webhook_url;
        if (!$webhookUrl) {
            return [
                'success' => false,
                'error_message' => 'Webhook URL not configured'
            ];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'MeishiCard-Webhook/1.0',
                    'X-MeishiCard-Event' => $payload['event'] ?? 'unknown',
                    'X-MeishiCard-Integration' => $integration->name,
                ])
                ->post($webhookUrl, $payload);

            $success = $response->successful();
            $responseCode = $response->status();
            $responseBody = $response->body();

            // Log the webhook call
            WebhookLog::create([
                'integration_id' => $integration->id,
                'event_type' => $payload['event'] ?? 'unknown',
                'payload' => $payload,
                'response_code' => $responseCode,
                'response_body' => $responseBody,
                'success' => $success,
                'error_message' => $success ? null : "HTTP {$responseCode}: {$responseBody}",
            ]);

            // Update integration last sync time
            $integration->update([
                'last_sync_at' => now(),
                'last_error_at' => $success ? null : now(),
                'last_error_message' => $success ? null : "HTTP {$responseCode}: {$responseBody}",
            ]);

            return [
                'success' => $success,
                'response_code' => $responseCode,
                'response_body' => $responseBody,
                'error_message' => $success ? null : "HTTP {$responseCode}: {$responseBody}",
            ];

        } catch (\Exception $e) {
            Log::error('Webhook call failed', [
                'integration_id' => $integration->id,
                'webhook_url' => $webhookUrl,
                'error' => $e->getMessage()
            ]);

            // Log the failed webhook call
            WebhookLog::create([
                'integration_id' => $integration->id,
                'event_type' => $payload['event'] ?? 'unknown',
                'payload' => $payload,
                'response_code' => null,
                'response_body' => null,
                'success' => false,
                'error_message' => $e->getMessage(),
            ]);

            // Update integration error status
            $integration->update([
                'last_error_at' => now(),
                'last_error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'response_code' => null,
                'response_body' => null,
                'error_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send webhook for card tap event
     */
    public function sendCardTapWebhook(Integration $integration, array $cardData): array
    {
        $payload = [
            'event' => 'card_tap',
            'timestamp' => now()->toISOString(),
            'data' => [
                'card_id' => $cardData['id'],
                'card_title' => $cardData['title'],
                'card_slug' => $cardData['slug'],
                'tap_count' => $cardData['tap_count'],
                'visitor_ip' => $cardData['visitor_ip'] ?? null,
                'visitor_location' => $cardData['visitor_location'] ?? null,
                'device_info' => $cardData['device_info'] ?? null,
            ]
        ];

        return $this->sendWebhook($integration, $payload);
    }

    /**
     * Send webhook for new lead event
     */
    public function sendNewLeadWebhook(Integration $integration, array $leadData): array
    {
        $payload = [
            'event' => 'new_lead',
            'timestamp' => now()->toISOString(),
            'data' => [
                'lead_id' => $leadData['id'],
                'name' => $leadData['name'],
                'email' => $leadData['email'],
                'phone' => $leadData['phone'] ?? null,
                'message' => $leadData['message'] ?? null,
                'card_id' => $leadData['card_id'],
                'card_title' => $leadData['card_title'],
                'source' => $leadData['source'] ?? 'contact_form',
            ]
        ];

        return $this->sendWebhook($integration, $payload);
    }

    /**
     * Send webhook for card created event
     */
    public function sendCardCreatedWebhook(Integration $integration, array $cardData): array
    {
        $payload = [
            'event' => 'card_created',
            'timestamp' => now()->toISOString(),
            'data' => [
                'card_id' => $cardData['id'],
                'card_title' => $cardData['title'],
                'card_slug' => $cardData['slug'],
                'user_id' => $cardData['user_id'],
                'user_email' => $cardData['user_email'],
                'created_at' => $cardData['created_at'],
            ]
        ];

        return $this->sendWebhook($integration, $payload);
    }

    /**
     * Send webhook for appointment booked event
     */
    public function sendAppointmentBookedWebhook(Integration $integration, array $appointmentData): array
    {
        $payload = [
            'event' => 'appointment_booked',
            'timestamp' => now()->toISOString(),
            'data' => [
                'appointment_id' => $appointmentData['id'],
                'customer_name' => $appointmentData['customer_name'],
                'customer_email' => $appointmentData['customer_email'],
                'customer_phone' => $appointmentData['customer_phone'] ?? null,
                'appointment_date' => $appointmentData['appointment_date'],
                'appointment_time' => $appointmentData['appointment_time'],
                'service' => $appointmentData['service'] ?? null,
                'notes' => $appointmentData['notes'] ?? null,
                'card_id' => $appointmentData['card_id'],
                'card_title' => $appointmentData['card_title'],
            ]
        ];

        return $this->sendWebhook($integration, $payload);
    }

    /**
     * Send webhook for contact form submitted event
     */
    public function sendContactFormSubmittedWebhook(Integration $integration, array $formData): array
    {
        $payload = [
            'event' => 'contact_form_submitted',
            'timestamp' => now()->toISOString(),
            'data' => [
                'form_id' => $formData['id'],
                'name' => $formData['name'],
                'email' => $formData['email'],
                'phone' => $formData['phone'] ?? null,
                'subject' => $formData['subject'] ?? null,
                'message' => $formData['message'],
                'card_id' => $formData['card_id'],
                'card_title' => $formData['card_title'],
                'ip_address' => $formData['ip_address'] ?? null,
            ]
        ];

        return $this->sendWebhook($integration, $payload);
    }

    /**
     * Send webhooks for all active integrations that have the specified event enabled
     */
    public function sendEventToAllIntegrations(string $event, array $eventData): array
    {
        $activeIntegrations = Integration::active()
            ->ofType('webhook')
            ->whereJsonContains('events', $event)
            ->get();

        $results = [];

        foreach ($activeIntegrations as $integration) {
            $methodName = 'send' . str_replace('_', '', ucwords($event, '_')) . 'Webhook';
            
            if (method_exists($this, $methodName)) {
                $result = $this->$methodName($integration, $eventData);
                $results[$integration->id] = $result;
            } else {
                // Fallback to generic webhook
                $payload = [
                    'event' => $event,
                    'timestamp' => now()->toISOString(),
                    'data' => $eventData
                ];
                $results[$integration->id] = $this->sendWebhook($integration, $payload);
            }
        }

        return $results;
    }

    /**
     * Validate webhook URL
     */
    public function validateWebhookUrl(string $url): bool
    {
        // Basic URL validation
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if it's a supported protocol
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'])) {
            return false;
        }

        return true;
    }

    /**
     * Test webhook URL connectivity
     */
    public function testWebhookUrl(string $url): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'MeishiCard-Webhook-Test/1.0',
                ])
                ->get($url);

            return [
                'success' => $response->successful(),
                'response_code' => $response->status(),
                'response_body' => $response->body(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'response_code' => null,
                'response_body' => null,
                'error_message' => $e->getMessage(),
            ];
        }
    }
} 