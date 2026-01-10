<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HubSpotService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $baseUrl = 'https://api.hubapi.com';

    public function __construct()
    {
        $this->clientId = config('services.hubspot.client_id', env('HUBSPOT_CLIENT_ID'));
        $this->clientSecret = config('services.hubspot.client_secret', env('HUBSPOT_CLIENT_SECRET'));
        $this->redirectUri = config('services.hubspot.redirect_uri', env('HUBSPOT_REDIRECT_URI'));
    }

    /**
     * Get OAuth authorization URL
     */
    public function getAuthorizationUrl(int $userId): string
    {
        $state = base64_encode(json_encode(['user_id' => $userId, 'timestamp' => time()]));
        
        // Store state in cache for validation
        Cache::put("hubspot_oauth_state_{$userId}", $state, 300); // 5 minutes

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'contacts',
            'state' => $state,
        ];

        return 'https://app.hubspot.com/oauth/authorize?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code, int $userId): array
    {
        try {
            // Validate state
            $state = request()->get('state');
            $cachedState = Cache::get("hubspot_oauth_state_{$userId}");
            
            if (!$state || $state !== $cachedState) {
                throw new \Exception('Invalid OAuth state');
            }

            // Exchange code for tokens
            $response = Http::post('https://api.hubapi.com/oauth/v1/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'code' => $code,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to exchange code for tokens: ' . $response->body());
            }

            $tokens = $response->json();

            // Clear the state from cache
            Cache::forget("hubspot_oauth_state_{$userId}");

            return [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_at' => now()->addSeconds($tokens['expires_in']),
            ];

        } catch (\Exception $e) {
            Log::error('HubSpot OAuth callback failed', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Refresh access token
     */
    public function refreshToken(string $refreshToken): array
    {
        try {
            $response = Http::post('https://api.hubapi.com/oauth/v1/token', [
                'grant_type' => 'refresh_token',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to refresh token: ' . $response->body());
            }

            $tokens = $response->json();

            return [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? $refreshToken,
                'expires_at' => now()->addSeconds($tokens['expires_in']),
            ];

        } catch (\Exception $e) {
            Log::error('HubSpot token refresh failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Create a contact in HubSpot
     */
    public function createContact(string $accessToken, array $contactData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/crm/v3/objects/contacts", [
                'properties' => [
                    'email' => $contactData['email'],
                    'firstname' => $contactData['first_name'] ?? '',
                    'lastname' => $contactData['last_name'] ?? '',
                    'phone' => $contactData['phone'] ?? '',
                    'company' => $contactData['company'] ?? '',
                    'jobtitle' => $contactData['job_title'] ?? '',
                    'lifecyclestage' => 'lead',
                    'lead_status' => 'NEW',
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create contact: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('HubSpot create contact failed', [
                'contact_data' => $contactData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Update a contact in HubSpot
     */
    public function updateContact(string $accessToken, string $contactId, array $contactData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->patch("{$this->baseUrl}/crm/v3/objects/contacts/{$contactId}", [
                'properties' => $contactData
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to update contact: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('HubSpot update contact failed', [
                'contact_id' => $contactId,
                'contact_data' => $contactData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Find contact by email
     */
    public function findContactByEmail(string $accessToken, string $email): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v3/objects/contacts", [
                'filter' => "email:EQ:{$email}",
                'limit' => 1,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to find contact: ' . $response->body());
            }

            $data = $response->json();
            return $data['results'][0] ?? null;

        } catch (\Exception $e) {
            Log::error('HubSpot find contact failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Test connection
     */
    public function testConnection(string $accessToken): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v3/objects/contacts", [
                'limit' => 1,
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('HubSpot connection test failed', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get contact properties
     */
    public function getContactProperties(string $accessToken): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v3/properties/contacts");

            if (!$response->successful()) {
                throw new \Exception('Failed to get contact properties: ' . $response->body());
            }

            return $response->json()['results'] ?? [];

        } catch (\Exception $e) {
            Log::error('HubSpot get contact properties failed', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Create a deal in HubSpot
     */
    public function createDeal(string $accessToken, array $dealData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/crm/v3/objects/deals", [
                'properties' => [
                    'dealname' => $dealData['name'],
                    'amount' => $dealData['amount'] ?? '',
                    'dealstage' => $dealData['stage'] ?? 'appointmentscheduled',
                    'pipeline' => 'default',
                    'closedate' => $dealData['close_date'] ?? now()->addDays(30)->toISOString(),
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create deal: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('HubSpot create deal failed', [
                'deal_data' => $dealData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
} 