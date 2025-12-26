<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Walletobjects;
use App\Models\Vcard;
use Illuminate\Support\Facades\Log;

class GoogleWalletController extends Controller
{
    protected $client;
    protected $service;

    public function __construct()
    {
        // Initialize client and service lazily to avoid issues during app bootstrap
        $this->client = null;
        $this->service = null;
    }

    protected function getClient()
    {
        if ($this->client === null) {
            try {
                $this->client = new Google_Client();
                $this->client->setAuthConfig(storage_path('app/google-wallet-credentials.json'));
                $this->client->addScope(Google_Service_Walletobjects::WALLET_OBJECT_ISSUER);
            } catch (\Exception $e) {
                Log::error('Google Wallet Client Error: ' . $e->getMessage());
                throw new \Exception('Google Wallet service is not available');
            }
        }
        return $this->client;
    }

    protected function getService()
    {
        if ($this->service === null) {
            $this->service = new Google_Service_Walletobjects($this->getClient());
        }
        return $this->service;
    }

    public function generatePass(Request $request, $vcardId)
    {
        try {
            $vcard = Vcard::findOrFail($vcardId);
            
            // Create pass object
            $passObject = [
                'id' => $vcard->id . '_' . time(),
                'classId' => 'vcard_class',
                'state' => 'ACTIVE',
                'heroImage' => [
                    'sourceUri' => [
                        'uri' => $vcard->profile_url ?? 'https://via.placeholder.com/600x300'
                    ]
                ],
                'textModulesData' => [
                    [
                        'header' => 'Contact Info',
                        'body' => "Name: {$vcard->name}\nEmail: {$vcard->email}\nPhone: {$vcard->phone}"
                    ]
                ],
                'linksModuleData' => [
                    'uris' => [
                        [
                            'uri' => route('vcard.show', $vcard->url_alias),
                            'description' => 'View Full Profile'
                        ]
                    ]
                ]
            ];

            $result = $this->getService()->genericobject->insert($passObject);
            
            return response()->json([
                'success' => true,
                'pass_id' => $result->getId(),
                'message' => 'Pass generated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Google Wallet Pass Generation Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate pass: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveToWallet(Request $request, $vcardId)
    {
        try {
            $vcard = Vcard::findOrFail($vcardId);
            
            // Generate pass first
            $passResponse = $this->generatePass($request, $vcardId);
            $passData = json_decode($passResponse->getContent(), true);
            
            if (!$passData['success']) {
                return $passResponse;
            }

            // Create save URL for Google Wallet
            $saveUrl = "https://pay.google.com/gp/v/save/{$passData['pass_id']}";
            
            return response()->json([
                'success' => true,
                'save_url' => $saveUrl,
                'message' => 'Pass ready to save to Google Wallet'
            ]);

        } catch (\Exception $e) {
            Log::error('Google Wallet Save Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save to Google Wallet: ' . $e->getMessage()
            ], 500);
        }
    }
}
