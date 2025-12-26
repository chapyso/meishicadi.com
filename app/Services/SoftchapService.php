<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SoftchapService
{
    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.softchap.base_url', env('SOFTCHAP_BASE_URL', 'https://api.softchap.com'));
        $this->apiKey = config('services.softchap.api_key', env('SOFTCHAP_API_KEY'));
        $this->apiSecret = config('services.softchap.api_secret', env('SOFTCHAP_API_SECRET'));
    }

    /**
     * Test connection with API credentials
     */
    public function testConnection(string $apiKey, string $apiSecret): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/api/v1/contacts", [
                'limit' => 1,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid API credentials',
                    'response_code' => $response->status()
                ];
            }

        } catch (\Exception $e) {
            Log::error('Softchap connection test failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a contact in Softchap CRM
     */
    public function createContact(string $apiKey, string $apiSecret, array $contactData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/contacts", [
                'first_name' => $contactData['first_name'] ?? '',
                'last_name' => $contactData['last_name'] ?? '',
                'email' => $contactData['email'],
                'phone' => $contactData['phone'] ?? '',
                'company' => $contactData['company'] ?? '',
                'job_title' => $contactData['job_title'] ?? '',
                'source' => 'MeishiCard',
                'notes' => $contactData['message'] ?? '',
                'status' => 'new_lead',
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create contact: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap create contact failed', [
                'contact_data' => $contactData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Update a contact in Softchap CRM
     */
    public function updateContact(string $apiKey, string $apiSecret, string $contactId, array $contactData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->put("{$this->baseUrl}/api/v1/contacts/{$contactId}", $contactData);

            if (!$response->successful()) {
                throw new \Exception('Failed to update contact: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap update contact failed', [
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
    public function findContactByEmail(string $apiKey, string $apiSecret, string $email): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
            ])->get("{$this->baseUrl}/api/v1/contacts/search", [
                'email' => $email,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to find contact: ' . $response->body());
            }

            $data = $response->json();
            return $data['contacts'][0] ?? null;

        } catch (\Exception $e) {
            Log::error('Softchap find contact failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Create a lead in Softchap CRM
     */
    public function createLead(string $apiKey, string $apiSecret, array $leadData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/leads", [
                'first_name' => $leadData['first_name'] ?? '',
                'last_name' => $leadData['last_name'] ?? '',
                'email' => $leadData['email'],
                'phone' => $leadData['phone'] ?? '',
                'company' => $leadData['company'] ?? '',
                'source' => 'MeishiCard',
                'status' => 'new',
                'description' => $leadData['message'] ?? '',
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create lead: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap create lead failed', [
                'lead_data' => $leadData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Update a lead in Softchap CRM
     */
    public function updateLead(string $apiKey, string $apiSecret, string $leadId, array $leadData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->put("{$this->baseUrl}/api/v1/leads/{$leadId}", $leadData);

            if (!$response->successful()) {
                throw new \Exception('Failed to update lead: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap update lead failed', [
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
    public function findLeadByEmail(string $apiKey, string $apiSecret, string $email): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
            ])->get("{$this->baseUrl}/api/v1/leads/search", [
                'email' => $email,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to find lead: ' . $response->body());
            }

            $data = $response->json();
            return $data['leads'][0] ?? null;

        } catch (\Exception $e) {
            Log::error('Softchap find lead failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Create a deal in Softchap CRM
     */
    public function createDeal(string $apiKey, string $apiSecret, array $dealData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/api/v1/deals", [
                'name' => $dealData['name'],
                'amount' => $dealData['amount'] ?? 0,
                'stage' => $dealData['stage'] ?? 'qualification',
                'close_date' => $dealData['close_date'] ?? now()->addDays(30)->format('Y-m-d'),
                'source' => 'MeishiCard',
                'description' => $dealData['description'] ?? '',
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create deal: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap create deal failed', [
                'deal_data' => $dealData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Get contact fields
     */
    public function getContactFields(string $apiKey, string $apiSecret): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
            ])->get("{$this->baseUrl}/api/v1/fields/contacts");

            if (!$response->successful()) {
                throw new \Exception('Failed to get contact fields: ' . $response->body());
            }

            return $response->json()['fields'] ?? [];

        } catch (\Exception $e) {
            Log::error('Softchap get contact fields failed', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get lead fields
     */
    public function getLeadFields(string $apiKey, string $apiSecret): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
            ])->get("{$this->baseUrl}/api/v1/fields/leads");

            if (!$response->successful()) {
                throw new \Exception('Failed to get lead fields: ' . $response->body());
            }

            return $response->json()['fields'] ?? [];

        } catch (\Exception $e) {
            Log::error('Softchap get lead fields failed', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }

    /**
     * Get account information
     */
    public function getAccountInfo(string $apiKey, string $apiSecret): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'X-API-Secret' => $apiSecret,
            ])->get("{$this->baseUrl}/api/v1/account");

            if (!$response->successful()) {
                throw new \Exception('Failed to get account info: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Softchap get account info failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Sync contact data to Softchap CRM
     */
    public function syncContact(string $apiKey, string $apiSecret, array $contactData): array
    {
        try {
            // First, try to find existing contact
            $existingContact = $this->findContactByEmail($apiKey, $apiSecret, $contactData['email']);

            if ($existingContact) {
                // Update existing contact
                return $this->updateContact($apiKey, $apiSecret, $existingContact['id'], $contactData);
            } else {
                // Create new contact
                return $this->createContact($apiKey, $apiSecret, $contactData);
            }

        } catch (\Exception $e) {
            Log::error('Softchap sync contact failed', [
                'contact_data' => $contactData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Sync lead data to Softchap CRM
     */
    public function syncLead(string $apiKey, string $apiSecret, array $leadData): array
    {
        try {
            // First, try to find existing lead
            $existingLead = $this->findLeadByEmail($apiKey, $apiSecret, $leadData['email']);

            if ($existingLead) {
                // Update existing lead
                return $this->updateLead($apiKey, $apiSecret, $existingLead['id'], $leadData);
            } else {
                // Create new lead
                return $this->createLead($apiKey, $apiSecret, $leadData);
            }

        } catch (\Exception $e) {
            Log::error('Softchap sync lead failed', [
                'lead_data' => $leadData,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
} 