<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Models\WebhookLog;
use App\Services\WebhookService;
use App\Services\HubSpotService;
use App\Services\ZohoService;
use App\Services\SoftchapService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    protected $webhookService;
    protected $hubspotService;
    protected $zohoService;
    protected $softchapService;

    public function __construct(
        WebhookService $webhookService,
        HubSpotService $hubspotService,
        ZohoService $zohoService,
        SoftchapService $softchapService
    ) {
        $this->webhookService = $webhookService;
        $this->hubspotService = $hubspotService;
        $this->zohoService = $zohoService;
        $this->softchapService = $softchapService;
    }

    /**
     * Display the integrations dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        $integrations = Integration::forUser($user->id)
            ->with('webhookLogs')
            ->orderBy('created_at', 'desc')
            ->get();

        $webhookIntegrations = $integrations->where('type', 'webhook');
        $crmIntegrations = $integrations->whereIn('type', ['hubspot', 'zoho', 'softchap']);

        return view('integrations.index', compact('webhookIntegrations', 'crmIntegrations'));
    }

    /**
     * Store a new webhook integration
     */
    public function storeWebhook(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'webhook_url' => 'required|url',
            'events' => 'required|array|min:1',
            'events.*' => 'string|in:' . implode(',', array_keys(Integration::getAvailableEvents())),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();

            // Check if user already has a webhook with this name
            $existingIntegration = Integration::forUser($user->id)
                ->where('type', 'webhook')
                ->where('name', $request->name)
                ->first();

            if ($existingIntegration) {
                return response()->json([
                    'success' => false,
                    'message' => 'A webhook with this name already exists'
                ], 422);
            }

            $integration = Integration::create([
                'user_id' => $user->id,
                'type' => 'webhook',
                'name' => $request->name,
                'config' => [
                    'webhook_url' => $request->webhook_url,
                ],
                'events' => $request->events,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook integration created successfully',
                'integration' => $integration
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating webhook integration', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating webhook integration'
            ], 500);
        }
    }

    /**
     * Update a webhook integration
     */
    public function updateWebhook(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'webhook_url' => 'required|url',
            'events' => 'required|array|min:1',
            'events.*' => 'string|in:' . implode(',', array_keys(Integration::getAvailableEvents())),
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            if ($integration->type !== 'webhook') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid integration type'
                ], 422);
            }

            $integration->update([
                'name' => $request->name,
                'config' => [
                    'webhook_url' => $request->webhook_url,
                ],
                'events' => $request->events,
                'is_active' => $request->get('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook integration updated successfully',
                'integration' => $integration
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating webhook integration', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating webhook integration'
            ], 500);
        }
    }

    /**
     * Test webhook connection
     */
    public function testWebhook($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            if ($integration->type !== 'webhook') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid integration type'
                ], 422);
            }

            $testPayload = [
                'event' => 'test_connection',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'message' => 'This is a test webhook from MeishiCard',
                    'integration_name' => $integration->name,
                    'user_email' => $user->email,
                ]
            ];

            $response = $this->webhookService->sendWebhook($integration, $testPayload);

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test webhook sent successfully',
                    'response_code' => $response['response_code'],
                    'response_body' => $response['response_body']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Test webhook failed',
                    'error' => $response['error_message'],
                    'response_code' => $response['response_code']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Error testing webhook', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error testing webhook'
            ], 500);
        }
    }

    /**
     * Delete an integration
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            $integration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Integration deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting integration', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting integration'
            ], 500);
        }
    }

    /**
     * Toggle integration status
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            $integration->update([
                'is_active' => !$integration->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Integration status updated successfully',
                'is_active' => $integration->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling integration status', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating integration status'
            ], 500);
        }
    }

    /**
     * Get webhook logs for an integration
     */
    public function getWebhookLogs($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            $logs = WebhookLog::forIntegration($integration->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            $html = view('integrations.webhook-logs', compact('logs'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting webhook logs', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting webhook logs'
            ], 500);
        }
    }

    /**
     * Initiate OAuth flow for HubSpot
     */
    public function connectHubSpot(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Check if user already has a HubSpot integration
            $existingIntegration = Integration::forUser($user->id)
                ->where('type', 'hubspot')
                ->first();

            if ($existingIntegration) {
                return response()->json([
                    'success' => false,
                    'message' => 'HubSpot is already connected'
                ], 422);
            }

            // Check if HubSpot OAuth is configured
            if (empty(config('services.hubspot.client_id')) || empty(config('services.hubspot.client_secret'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'HubSpot OAuth is not configured. Please contact your administrator to set up HubSpot integration.'
                ], 422);
            }

            $authUrl = $this->hubspotService->getAuthorizationUrl($user->id);

            return response()->json([
                'success' => true,
                'auth_url' => $authUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Error initiating HubSpot OAuth', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error connecting to HubSpot: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle HubSpot OAuth callback
     */
    public function hubSpotCallback(Request $request)
    {
        try {
            $user = auth()->user();
            $code = $request->get('code');

            if (!$code) {
                return redirect()->route('integrations.index')
                    ->with('error', 'Authorization code not received');
            }

            $tokens = $this->hubspotService->handleCallback($code, $user->id);

            // Create or update integration
            Integration::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => 'hubspot'
                ],
                [
                    'name' => 'HubSpot CRM',
                    'config' => [
                        'access_token' => $tokens['access_token'],
                        'refresh_token' => $tokens['refresh_token'],
                        'expires_at' => $tokens['expires_at'],
                    ],
                    'events' => ['new_lead', 'contact_form_submitted'],
                    'is_active' => true,
                ]
            );

            return redirect()->route('integrations.index')
                ->with('success', 'HubSpot connected successfully!');

        } catch (\Exception $e) {
            Log::error('Error handling HubSpot callback', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('integrations.index')
                ->with('error', 'Error connecting to HubSpot');
        }
    }

    /**
     * Initiate OAuth flow for Zoho
     */
    public function connectZoho(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Check if user already has a Zoho integration
            $existingIntegration = Integration::forUser($user->id)
                ->where('type', 'zoho')
                ->first();

            if ($existingIntegration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Zoho CRM is already connected'
                ], 422);
            }

            // Check if Zoho OAuth is configured
            if (empty(config('services.zoho.client_id')) || empty(config('services.zoho.client_secret'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Zoho OAuth is not configured. Please contact your administrator to set up Zoho integration.'
                ], 422);
            }

            $authUrl = $this->zohoService->getAuthorizationUrl($user->id);

            return response()->json([
                'success' => true,
                'auth_url' => $authUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Error initiating Zoho OAuth', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error connecting to Zoho CRM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Zoho OAuth callback
     */
    public function zohoCallback(Request $request)
    {
        try {
            $user = auth()->user();
            $code = $request->get('code');

            if (!$code) {
                return redirect()->route('integrations.index')
                    ->with('error', 'Authorization code not received');
            }

            $tokens = $this->zohoService->handleCallback($code, $user->id);

            // Create or update integration
            Integration::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => 'zoho'
                ],
                [
                    'name' => 'Zoho CRM',
                    'config' => [
                        'access_token' => $tokens['access_token'],
                        'refresh_token' => $tokens['refresh_token'],
                        'expires_at' => $tokens['expires_at'],
                    ],
                    'events' => ['new_lead', 'contact_form_submitted'],
                    'is_active' => true,
                ]
            );

            return redirect()->route('integrations.index')
                ->with('success', 'Zoho CRM connected successfully!');

        } catch (\Exception $e) {
            Log::error('Error handling Zoho callback', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('integrations.index')
                ->with('error', 'Error connecting to Zoho CRM');
        }
    }

    /**
     * Connect to Softchap CRM
     */
    public function connectSoftchap(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = auth()->user();
            
            // Test the connection
            $testResult = $this->softchapService->testConnection(
                $request->api_key,
                $request->api_secret
            );

            if (!$testResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid API credentials'
                ], 422);
            }

            // Create or update integration
            Integration::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => 'softchap'
                ],
                [
                    'name' => 'Softchap CRM',
                    'config' => [
                        'api_key' => $request->api_key,
                        'api_secret' => $request->api_secret,
                    ],
                    'events' => ['new_lead', 'contact_form_submitted'],
                    'is_active' => true,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Softchap CRM connected successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error connecting to Softchap', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error connecting to Softchap CRM'
            ], 500);
        }
    }

    /**
     * Disconnect CRM integration
     */
    public function disconnectCrm($id): JsonResponse
    {
        try {
            $user = auth()->user();
            $integration = Integration::forUser($user->id)->findOrFail($id);

            if (!in_array($integration->type, ['hubspot', 'zoho', 'softchap'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid integration type'
                ], 422);
            }

            $integration->delete();

            return response()->json([
                'success' => true,
                'message' => 'CRM integration disconnected successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error disconnecting CRM', [
                'integration_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error disconnecting CRM'
            ], 500);
        }
    }

    /**
     * Get integration statistics for admin
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user->can('view admin statistics')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $statistics = [
                'total_integrations' => Integration::count(),
                'active_integrations' => Integration::active()->count(),
                'webhook_integrations' => Integration::ofType('webhook')->count(),
                'crm_integrations' => Integration::whereIn('type', ['hubspot', 'zoho', 'softchap'])->count(),
                'recent_webhook_calls' => WebhookLog::where('created_at', '>=', now()->subDays(7))->count(),
                'successful_webhook_calls' => WebhookLog::successful()->where('created_at', '>=', now()->subDays(7))->count(),
                'failed_webhook_calls' => WebhookLog::failed()->where('created_at', '>=', now()->subDays(7))->count(),
            ];

            return response()->json([
                'success' => true,
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting integration statistics', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting statistics'
            ], 500);
        }
    }
}
