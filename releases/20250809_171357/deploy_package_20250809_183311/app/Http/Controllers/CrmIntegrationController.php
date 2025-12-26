<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrmIntegration;
use App\Models\CrmSyncLog;
use App\Models\Contacts;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrmIntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $integrations = CrmIntegration::where('user_id', $user->id)
            ->with(['business', 'syncLogs' => function($query) {
                $query->recent(7)->orderBy('started_at', 'desc');
            }])
            ->get();

        $recentSyncLogs = CrmSyncLog::whereHas('crmIntegration', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['crmIntegration', 'contact'])
            ->recent(7)
            ->orderBy('started_at', 'desc')
            ->limit(10)
            ->get();

        return view('crm-integrations.index', compact('integrations', 'recentSyncLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $businesses = Business::where('created_by', $user->creatorId())->get();
        
        return view('crm-integrations.create', compact('businesses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'crm_type' => 'required|in:hubspot,zoho',
            'name' => 'required|string|max:255',
            'business_id' => 'nullable|exists:businesses,id',
            'api_key' => 'required|string',
            'auto_sync' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Test connection before saving
        $testResult = $this->testCrmConnection($request->crm_type, $request->api_key);
        
        if (!$testResult['success']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['api_key' => 'Connection failed: ' . $testResult['message']]);
        }

        $integration = CrmIntegration::create([
            'user_id' => $user->id,
            'business_id' => $request->business_id,
            'crm_type' => $request->crm_type,
            'name' => $request->name,
            'credentials' => [
                'api_key' => $request->api_key,
                'connected_at' => now()->toISOString(),
            ],
            'field_mapping' => $this->getDefaultFieldMapping($request->crm_type),
            'auto_sync' => $request->boolean('auto_sync'),
            'sync_status' => 'idle',
        ]);

        return redirect()->route('crm-integrations.index')
            ->with('success', 'CRM integration created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmIntegration $crmIntegration)
    {
        $this->authorize('view', $crmIntegration);
        
        $syncLogs = $crmIntegration->syncLogs()
            ->with('contact')
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return view('crm-integrations.show', compact('crmIntegration', 'syncLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmIntegration $crmIntegration)
    {
        $this->authorize('update', $crmIntegration);
        
        $user = Auth::user();
        $businesses = Business::where('created_by', $user->creatorId())->get();
        
        return view('crm-integrations.edit', compact('crmIntegration', 'businesses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmIntegration $crmIntegration)
    {
        $this->authorize('update', $crmIntegration);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'business_id' => 'nullable|exists:businesses,id',
            'api_key' => 'required|string',
            'auto_sync' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Test connection if API key changed
        if ($request->api_key !== $crmIntegration->getCredential('api_key')) {
            $testResult = $this->testCrmConnection($crmIntegration->crm_type, $request->api_key);
            
            if (!$testResult['success']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['api_key' => 'Connection failed: ' . $testResult['message']]);
            }
        }

        $crmIntegration->update([
            'name' => $request->name,
            'business_id' => $request->business_id,
            'credentials' => [
                'api_key' => $request->api_key,
                'connected_at' => now()->toISOString(),
            ],
            'auto_sync' => $request->boolean('auto_sync'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('crm-integrations.index')
            ->with('success', 'CRM integration updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmIntegration $crmIntegration)
    {
        $this->authorize('delete', $crmIntegration);
        
        $crmIntegration->delete();
        
        return redirect()->route('crm-integrations.index')
            ->with('success', 'CRM integration deleted successfully!');
    }

    /**
     * Test connection to CRM
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'crm_type' => 'required|in:hubspot,zoho',
            'api_key' => 'required|string',
        ]);

        $result = $this->testCrmConnection($request->crm_type, $request->api_key);
        
        return response()->json($result);
    }

    /**
     * Sync contacts to CRM
     */
    public function syncContacts(Request $request, CrmIntegration $crmIntegration)
    {
        $this->authorize('update', $crmIntegration);
        
        $request->validate([
            'contact_ids' => 'nullable|array',
            'contact_ids.*' => 'exists:contacts,id',
            'sync_type' => 'required|in:manual,bulk,auto',
        ]);

        $user = Auth::user();
        $contactIds = $request->contact_ids;
        
        // If no specific contacts provided, sync all unsynced contacts
        if (empty($contactIds)) {
            $contacts = Contacts::where('created_by', $user->creatorId())
                ->whereDoesntHave('crmSyncLogs', function($query) use ($crmIntegration) {
                    $query->where('crm_integration_id', $crmIntegration->id)
                          ->where('status', 'success');
                })
                ->pluck('id')
                ->toArray();
        } else {
            $contacts = $contactIds;
        }

        if (empty($contacts)) {
            return response()->json([
                'success' => false,
                'message' => 'No contacts to sync'
            ]);
        }

        // Create sync log
        $syncLog = CrmSyncLog::create([
            'crm_integration_id' => $crmIntegration->id,
            'sync_type' => $request->sync_type,
            'status' => 'pending',
            'started_at' => now(),
            'records_processed' => count($contacts),
        ]);

        // Update integration status
        $crmIntegration->updateSyncStatus('syncing');

        // Dispatch sync job (you can implement this as a queue job)
        $this->performSync($crmIntegration, $syncLog, $contacts);

        return response()->json([
            'success' => true,
            'message' => 'Sync started successfully',
            'sync_log_id' => $syncLog->id
        ]);
    }

    /**
     * Get sync status
     */
    public function getSyncStatus(CrmSyncLog $syncLog)
    {
        return response()->json([
            'status' => $syncLog->status,
            'progress' => [
                'processed' => $syncLog->records_processed,
                'successful' => $syncLog->records_successful,
                'failed' => $syncLog->records_failed,
            ],
            'duration' => $syncLog->duration,
            'error_message' => $syncLog->error_message,
        ]);
    }

    /**
     * Update field mapping
     */
    public function updateFieldMapping(Request $request, CrmIntegration $crmIntegration)
    {
        $this->authorize('update', $crmIntegration);
        
        $request->validate([
            'field_mapping' => 'required|array',
        ]);

        $crmIntegration->field_mapping = $request->field_mapping;
        $crmIntegration->save();

        return response()->json([
            'success' => true,
            'message' => 'Field mapping updated successfully'
        ]);
    }

    /**
     * Private methods
     */
    private function testCrmConnection($crmType, $apiKey)
    {
        try {
            switch ($crmType) {
                case 'hubspot':
                    return $this->testHubSpotConnection($apiKey);
                case 'zoho':
                    return $this->testZohoConnection($apiKey);
                default:
                    return ['success' => false, 'message' => 'Unsupported CRM type'];
            }
        } catch (\Exception $e) {
            Log::error('CRM connection test failed', [
                'crm_type' => $crmType,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => 'Connection test failed: ' . $e->getMessage()];
        }
    }

    private function testHubSpotConnection($apiKey)
    {
        // Implement HubSpot API test
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->get('https://api.hubapi.com/crm/v3/objects/contacts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'query' => ['limit' => 1]
            ]);
            
            if ($response->getStatusCode() === 200) {
                return ['success' => true, 'message' => 'Connection successful'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Invalid API key or connection failed'];
        }
        
        return ['success' => false, 'message' => 'Connection failed'];
    }

    private function testZohoConnection($apiKey)
    {
        // Implement Zoho API test
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->get('https://www.zohoapis.com/crm/v2/Contacts', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'query' => ['per_page' => 1]
            ]);
            
            if ($response->getStatusCode() === 200) {
                return ['success' => true, 'message' => 'Connection successful'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Invalid API key or connection failed'];
        }
        
        return ['success' => false, 'message' => 'Connection failed'];
    }

    private function getDefaultFieldMapping($crmType)
    {
        $defaultMapping = [
            'name' => 'firstname',
            'email' => 'email',
            'phone' => 'phone',
            'message' => 'description',
        ];

        if ($crmType === 'hubspot') {
            return [
                'name' => 'firstname',
                'email' => 'email',
                'phone' => 'phone',
                'message' => 'description',
            ];
        } elseif ($crmType === 'zoho') {
            return [
                'name' => 'First_Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'message' => 'Description',
            ];
        }

        return $defaultMapping;
    }

    private function performSync($crmIntegration, $syncLog, $contactIds)
    {
        // This is a simplified sync implementation
        // In production, you should implement this as a queue job
        
        $successCount = 0;
        $failedCount = 0;
        
        foreach ($contactIds as $contactId) {
            try {
                $contact = Contacts::find($contactId);
                if (!$contact) continue;

                $result = $this->syncContactToCrm($crmIntegration, $contact);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $failedCount++;
                Log::error('Contact sync failed', [
                    'contact_id' => $contactId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update sync log
        $syncLog->markCompleted($successCount, $failedCount);
        $syncLog->status = ($failedCount === 0) ? 'success' : 'failed';
        $syncLog->save();

        // Update integration status
        $crmIntegration->updateSyncStatus(
            ($failedCount === 0) ? 'idle' : 'error',
            $failedCount > 0 ? "Failed to sync {$failedCount} contacts" : null
        );
    }

    private function syncContactToCrm($crmIntegration, $contact)
    {
        $mapping = $crmIntegration->field_mapping;
        $contactData = [];

        foreach ($mapping as $meishiField => $crmField) {
            if (isset($contact->$meishiField)) {
                $contactData[$crmField] = $contact->$meishiField;
            }
        }

        switch ($crmIntegration->crm_type) {
            case 'hubspot':
                return $this->syncToHubSpot($crmIntegration, $contactData);
            case 'zoho':
                return $this->syncToZoho($crmIntegration, $contactData);
            default:
                return ['success' => false, 'message' => 'Unsupported CRM type'];
        }
    }

    private function syncToHubSpot($crmIntegration, $contactData)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('https://api.hubapi.com/crm/v3/objects/contacts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $crmIntegration->getCredential('api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => ['properties' => $contactData]
            ]);
            
            return ['success' => $response->getStatusCode() === 201];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function syncToZoho($crmIntegration, $contactData)
    {
        $client = new \GuzzleHttp\Client();
        
        try {
            $response = $client->post('https://www.zohoapis.com/crm/v2/Contacts', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $crmIntegration->getCredential('api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => ['data' => [$contactData]]
            ]);
            
            return ['success' => $response->getStatusCode() === 201];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
