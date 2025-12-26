<?php

namespace App\Services;

use App\Models\Business;
use App\Models\WalletPass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleWalletService
{
    private $serviceAccountEmail;
    private $privateKey;
    private $issuerId;
    private $classId;

    public function __construct()
    {
        $this->serviceAccountEmail = config('wallet.google.service_account_email');
        $this->privateKey = config('wallet.google.private_key');
        $this->issuerId = config('wallet.google.issuer_id');
        $this->classId = config('wallet.google.class_id');
    }

    /**
     * Generate Google Wallet pass for a business
     */
    public function generatePass(Business $business, WalletPass $walletPass): bool
    {
        try {
            // Create or get pass class
            $classId = $this->createPassClass($business);
            
            // Create pass object
            $objectId = $this->createPassObject($business, $walletPass, $classId);
            
            // Update wallet pass record
            $walletPass->update([
                'google_wallet_object_id' => $objectId,
                'pass_data' => [
                    'class_id' => $classId,
                    'object_id' => $objectId
                ]
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Google Wallet pass generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create or get pass class
     */
    private function createPassClass(Business $business): string
    {
        $classId = "{$this->issuerId}.{$this->classId}";
        
        $classData = [
            'id' => $classId,
            'issuerName' => $business->title,
            'reviewStatus' => 'UNDER_REVIEW',
            'genericType' => 'GENERIC_TYPE_UNSPECIFIED',
            'genericClass' => [
                'title' => $business->title,
                'subtitle' => $business->designation ?? '',
                'logo' => [
                    'sourceUri' => [
                        'uri' => $this->getImageUrl($business->logo)
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json'
        ])->put("https://walletobjects.googleapis.com/walletobjects/v1/genericClass/{$classId}", $classData);

        if ($response->successful()) {
            return $classId;
        }

        // If class already exists, return the class ID
        if ($response->status() === 409) {
            return $classId;
        }

        throw new \Exception('Failed to create pass class: ' . $response->body());
    }

    /**
     * Create pass object
     */
    private function createPassObject(Business $business, WalletPass $walletPass, string $classId): string
    {
        $links = json_decode($business->links, true) ?? [];
        $objectId = "{$this->issuerId}.{$walletPass->pass_id}";
        
        $objectData = [
            'id' => $objectId,
            'classId' => $classId,
            'genericType' => 'GENERIC_TYPE_UNSPECIFIED',
            'genericObject' => [
                'title' => [
                    'defaultValue' => [
                        'language' => 'en-US',
                        'value' => $business->title
                    ]
                ],
                'subtitle' => [
                    'defaultValue' => [
                        'language' => 'en-US',
                        'value' => $business->designation ?? ''
                    ]
                ],
                'header' => [
                    'defaultValue' => [
                        'language' => 'en-US',
                        'value' => 'Business Card'
                    ]
                ],
                'primaryFields' => [
                    [
                        'id' => 'name',
                        'label' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => 'NAME'
                            ]
                        ],
                        'value' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => $business->title
                            ]
                        ]
                    ]
                ],
                'secondaryFields' => [
                    [
                        'id' => 'phone',
                        'label' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => 'PHONE'
                            ]
                        ],
                        'value' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => $links['phone'] ?? ''
                            ]
                        ]
                    ],
                    [
                        'id' => 'email',
                        'label' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => 'EMAIL'
                            ]
                        ],
                        'value' => [
                            'defaultValue' => [
                                'language' => 'en-US',
                                'value' => $links['email'] ?? ''
                            ]
                        ]
                    ]
                ],
                'barcode' => [
                    'type' => 'QR_CODE',
                    'value' => url('/' . $business->slug),
                    'alternateText' => $business->title
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json'
        ])->put("https://walletobjects.googleapis.com/walletobjects/v1/genericObject/{$objectId}", $objectData);

        if ($response->successful()) {
            return $objectId;
        }

        // If object already exists, return the object ID
        if ($response->status() === 409) {
            return $objectId;
        }

        throw new \Exception('Failed to create pass object: ' . $response->body());
    }

    /**
     * Get access token for Google Wallet API
     */
    private function getAccessToken(): string
    {
        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $payload = [
            'iss' => $this->serviceAccountEmail,
            'scope' => 'https://www.googleapis.com/auth/wallet_object.issuer',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => time() + 3600,
            'iat' => time()
        ];

        $jwt = $this->createJWT($header, $payload);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        throw new \Exception('Failed to get access token: ' . $response->body());
    }

    /**
     * Create JWT token
     */
    private function createJWT(array $header, array $payload): string
    {
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        
        $signature = '';
        openssl_sign(
            $headerEncoded . '.' . $payloadEncoded,
            $signature,
            $this->privateKey,
            'SHA256'
        );
        
        $signatureEncoded = $this->base64UrlEncode($signature);
        
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * Base64 URL encode
     */
    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Get image URL for Google Wallet
     */
    private function getImageUrl(?string $imagePath): string
    {
        if (!$imagePath) {
            return url('/images/default-logo.png');
        }
        
        return url('/storage/' . $imagePath);
    }

    /**
     * Get pass save URL for Google Wallet
     */
    public function getPassSaveUrl(WalletPass $walletPass): string
    {
        return "https://pay.google.com/gp/v/save/{$walletPass->google_wallet_object_id}";
    }

    /**
     * Update pass object
     */
    public function updatePass(Business $business, WalletPass $walletPass): bool
    {
        try {
            $objectId = $walletPass->google_wallet_object_id;
            if (!$objectId) {
                return false;
            }

            $links = json_decode($business->links, true) ?? [];
            
            $objectData = [
                'genericObject' => [
                    'title' => [
                        'defaultValue' => [
                            'language' => 'en-US',
                            'value' => $business->title
                        ]
                    ],
                    'subtitle' => [
                        'defaultValue' => [
                            'language' => 'en-US',
                            'value' => $business->designation ?? ''
                        ]
                    ],
                    'secondaryFields' => [
                        [
                            'id' => 'phone',
                            'value' => [
                                'defaultValue' => [
                                    'language' => 'en-US',
                                    'value' => $links['phone'] ?? ''
                                ]
                            ]
                        ],
                        [
                            'id' => 'email',
                            'value' => [
                                'defaultValue' => [
                                    'language' => 'en-US',
                                    'value' => $links['email'] ?? ''
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json'
            ])->patch("https://walletobjects.googleapis.com/walletobjects/v1/genericObject/{$objectId}", $objectData);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Google Wallet pass update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test Google Wallet configuration
     */
    public function testConfiguration(): array
    {
        $results = [
            'enabled' => config('wallet.google.enabled'),
            'service_account_email_set' => !empty($this->serviceAccountEmail),
            'private_key_set' => !empty($this->privateKey),
            'issuer_id_set' => !empty($this->issuerId),
            'class_id_set' => !empty($this->classId),
        ];

        // Test private key format if it exists
        if ($results['private_key_set']) {
            try {
                $results['private_key_valid'] = openssl_pkey_get_private($this->privateKey) !== false;
            } catch (\Exception $e) {
                $results['private_key_valid'] = false;
                $results['private_key_error'] = $e->getMessage();
            }
        }

        // Test API connection if credentials are set
        if ($results['enabled'] && $results['service_account_email_set'] && $results['private_key_set'] && $results['issuer_id_set']) {
            try {
                $accessToken = $this->getAccessToken();
                $results['api_connection_valid'] = !empty($accessToken);
                
                if ($results['api_connection_valid']) {
                    // Test a simple API call
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json'
                    ])->get('https://walletobjects.googleapis.com/walletobjects/v1/issuer');
                    
                    $results['api_response_valid'] = $response->successful();
                    if (!$response->successful()) {
                        $results['api_error'] = $response->body();
                    }
                }
            } catch (\Exception $e) {
                $results['api_connection_valid'] = false;
                $results['api_error'] = $e->getMessage();
            }
        }

        // Check if all required settings are configured
        $results['fully_configured'] = $results['enabled'] && 
                                     $results['service_account_email_set'] && 
                                     $results['private_key_set'] && 
                                     $results['private_key_valid'] && 
                                     $results['issuer_id_set'] && 
                                     $results['class_id_set'] && 
                                     $results['api_connection_valid'];

        return $results;
    }
} 