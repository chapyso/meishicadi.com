<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ZohoService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $baseUrl = 'https://www.zohoapis.com';

    public function __construct()
    {
        $this->clientId = config('services.zoho.client_id', env('ZOHO_CLIENT_ID'));
        $this->clientSecret = config('services.zoho.client_secret', env('ZOHO_CLIENT_SECRET'));
        $this->redirectUri = config('services.zoho.redirect_uri', env('ZOHO_REDIRECT_URI'));
    }

    /**
     * Get OAuth authorization URL
     */
    public function getAuthorizationUrl(int $userId): string
    {
        $state = base64_encode(json_encode(['user_id' => $userId, 'timestamp' => time()]));
        
        // Store state in cache for validation
        Cache::put("zoho_oauth_state_{$userId}", $state, 300); // 5 minutes

        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => 'ZohoCRM.modules.ALL,ZohoCRM.settings.ALL',
            'state' => $state,
            'access_type' => 'offline',
        ];

        return 'https://accounts.zoho.com/oauth/v2/auth?' . http_build_query($params);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code, int $userId): array
    {
        try {
            // Validate state
            $state = request()->get('state');
            $cachedState = Cache::get("zoho_oauth_state_{$userId}");
            
            if (!$state || $state !== $cachedState) {
                throw new \Exception('Invalid OAuth state');
            }

            // Exchange code for tokens
            $response = Http::post('https://accounts.zoho.com/oauth/v2/token', [
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
            Cache::forget("zoho_oauth_state_{$userId}");

            return [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_at' => now()->addSeconds($tokens['expires_in']),
            ];

        } catch (\Exception $e) {
            Log::error('Zoho OAuth callback failed', [
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
            $response = Http::post('https://accounts.zoho.com/oauth/v2/token', [
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
            Log::error('Zoho token refresh failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Create a lead in Zoho CRM
     */
    public function createLead(string $accessToken, array $leadData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/crm/v2/Leads", [
                'data' => [
                    [
                        'Email' => $leadData['email'],
                        'First_Name' => $leadData['first_name'] ?? '',
                        'Last_Name' => $leadData['last_name'] ?? '',
                        'Phone' => $leadData['phone'] ?? '',
                        'Company' => $leadData['company'] ?? '',
                        'Lead_Source' => 'Website',
                        'Lead_Status' => 'New',
                        'Description' => $leadData['message'] ?? '',
                    ]
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create lead: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Zoho create lead failed', [
                'lead_data' => $leadData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Create a contact in Zoho CRM
     */
    public function createContact(string $accessToken, array $contactData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/crm/v2/Contacts", [
                'data' => [
                    [
                        'Email' => $contactData['email'],
                        'First_Name' => $contactData['first_name'] ?? '',
                        'Last_Name' => $contactData['last_name'] ?? '',
                        'Phone' => $contactData['phone'] ?? '',
                        'Account_Name' => $contactData['company'] ?? '',
                        'Lead_Source' => 'Website',
                        'Description' => $contactData['message'] ?? '',
                    ]
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create contact: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Zoho create contact failed', [
                'contact_data' => $contactData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Update a lead in Zoho CRM
     */
    public function updateLead(string $accessToken, string $leadId, array $leadData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
                'Content-Type' => 'application/json',
            ])->put("{$this->baseUrl}/crm/v2/Leads/{$leadId}", [
                'data' => [
                    [
                        'id' => $leadId,
                        'Email' => $leadData['email'],
                        'First_Name' => $leadData['first_name'] ?? '',
                        'Last_Name' => $leadData['last_name'] ?? '',
                        'Phone' => $leadData['phone'] ?? '',
                        'Company' => $leadData['company'] ?? '',
                        'Description' => $leadData['message'] ?? '',
                    ]
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to update lead: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Zoho update lead failed', [
                'lead_id' => $leadId,
                'lead_data' => $leadData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Find lead by email
     */
    public function findLeadByEmail(string $accessToken, string $email): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v2/Leads/search", [
                'email' => $email,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to find lead: ' . $response->body());
            }

            $data = $response->json();
            return $data['data'][0] ?? null;

        } catch (\Exception $e) {
            Log::error('Zoho find lead failed', [
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
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v2/Leads", [
                'per_page' => 1,
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Zoho connection test failed', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get lead fields
     */
    public function getLeadFields(string $accessToken): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
            ])->get("{$this->baseUrl}/crm/v2/settings/fields", [
                'module' => 'Leads',
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get lead fields: ' . $response->body());
            }

            return $response->json()['fields'] ?? [];

        } catch (\Exception $e) {
            Log::error('Zoho get lead fields failed', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Create a deal in Zoho CRM
     */
    public function createDeal(string $accessToken, array $dealData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Zoho-oauthtoken {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/crm/v2/Potentials", [
                'data' => [
                    [
                        'Potential_Name' => $dealData['name'],
                        'Amount' => $dealData['amount'] ?? '',
                        'Stage' => $dealData['stage'] ?? 'Qualification',
                        'Closing_Date' => $dealData['close_date'] ?? now()->addDays(30)->format('Y-m-d'),
                        'Lead_Source' => 'Website',
                    ]
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create deal: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Zoho create deal failed', [
                'deal_data' => $dealData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
} 