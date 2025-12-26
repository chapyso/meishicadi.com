<?php

namespace App\Services;

use App\Models\Business;
use App\Models\WalletPass;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AppleWalletService
{
    private $certificatePath;
    private $certificatePassword;
    private $teamIdentifier;
    private $passTypeIdentifier;

    public function __construct()
    {
        $this->certificatePath = config('wallet.apple.certificate_path');
        $this->certificatePassword = config('wallet.apple.certificate_password');
        $this->teamIdentifier = config('wallet.apple.team_identifier');
        $this->passTypeIdentifier = config('wallet.apple.pass_type_identifier');
    }

    /**
     * Generate Apple Wallet pass for a business
     */
    public function generatePass(Business $business, WalletPass $walletPass): bool
    {
        try {
            // Create pass data structure
            $passData = $this->createPassData($business, $walletPass);
            
            // Create pass directory
            $passDir = storage_path("app/wallet_passes/apple/{$walletPass->pass_id}");
            if (!file_exists($passDir)) {
                mkdir($passDir, 0755, true);
            }

            // Create manifest.json
            $manifest = $this->createManifest($passData);
            file_put_contents("{$passDir}/manifest.json", json_encode($manifest));

            // Create pass.json
            file_put_contents("{$passDir}/pass.json", json_encode($passData));

            // Add images if they exist
            $this->addImages($business, $passDir);

            // Sign the pass
            $this->signPass($passDir, $walletPass);

            // Update wallet pass record
            $walletPass->update([
                'file_path' => "wallet_passes/apple/{$walletPass->pass_id}/{$walletPass->pass_id}.pkpass",
                'pass_data' => $passData
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Apple Wallet pass generation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create pass data structure
     */
    private function createPassData(Business $business, WalletPass $walletPass): array
    {
        $links = json_decode($business->links, true) ?? [];
        
        return [
            'formatVersion' => 1,
            'passTypeIdentifier' => $this->passTypeIdentifier,
            'teamIdentifier' => $this->teamIdentifier,
            'serialNumber' => $walletPass->serial_number,
            'organizationName' => $business->title,
            'description' => $business->description ?? 'Business Card',
            'generic' => [
                'primaryFields' => [
                    [
                        'key' => 'name',
                        'label' => 'NAME',
                        'value' => $business->title
                    ]
                ],
                'secondaryFields' => [
                    [
                        'key' => 'designation',
                        'label' => 'TITLE',
                        'value' => $business->designation ?? ''
                    ]
                ],
                'auxiliaryFields' => [
                    [
                        'key' => 'phone',
                        'label' => 'PHONE',
                        'value' => $links['phone'] ?? ''
                    ],
                    [
                        'key' => 'email',
                        'label' => 'EMAIL',
                        'value' => $links['email'] ?? ''
                    ]
                ]
            ],
            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => url('/' . $business->slug),
                'messageEncoding' => 'iso-8859-1'
            ],
            'webServiceURL' => url('/api/wallet/apple/webhook'),
            'authenticationToken' => $walletPass->pass_id
        ];
    }

    /**
     * Create manifest for signing
     */
    private function createManifest(array $passData): array
    {
        $manifest = [];
        
        // Add pass.json
        $manifest['pass.json'] = sha1(json_encode($passData));
        
        // Add images if they exist
        $imageFiles = ['icon.png', 'icon@2x.png', 'logo.png', 'logo@2x.png'];
        foreach ($imageFiles as $imageFile) {
            if (file_exists(storage_path("app/wallet_passes/temp/{$imageFile}"))) {
                $manifest[$imageFile] = sha1_file(storage_path("app/wallet_passes/temp/{$imageFile}"));
            }
        }
        
        return $manifest;
    }

    /**
     * Add images to pass
     */
    private function addImages(Business $business, string $passDir): void
    {
        // Copy logo if exists
        if ($business->logo) {
            $logoPath = storage_path("app/{$business->logo}");
            if (file_exists($logoPath)) {
                copy($logoPath, "{$passDir}/logo.png");
                copy($logoPath, "{$passDir}/logo@2x.png");
            }
        }
        
        // Copy banner if exists
        if ($business->banner) {
            $bannerPath = storage_path("app/{$business->banner}");
            if (file_exists($bannerPath)) {
                copy($bannerPath, "{$passDir}/icon.png");
                copy($bannerPath, "{$passDir}/icon@2x.png");
            }
        }
    }

    /**
     * Sign the pass using Apple's certificate
     */
    private function signPass(string $passDir, WalletPass $walletPass): void
    {
        $manifestPath = "{$passDir}/manifest.json";
        $signaturePath = "{$passDir}/signature";
        $pkpassPath = "{$passDir}/{$walletPass->pass_id}.pkpass";
        
        // Create signature
        $command = "openssl smime -binary -sign -certfile {$this->certificatePath} -signer {$this->certificatePath} -inkey {$this->certificatePath} -in {$manifestPath} -out {$signaturePath} -outform DER -passin pass:{$this->certificatePassword}";
        exec($command);
        
        // Create .pkpass file
        $zip = new \ZipArchive();
        $zip->open($pkpassPath, \ZipArchive::CREATE);
        
        // Add files to zip
        $files = ['manifest.json', 'pass.json', 'signature'];
        $imageFiles = ['icon.png', 'icon@2x.png', 'logo.png', 'logo@2x.png'];
        
        foreach ($files as $file) {
            if (file_exists("{$passDir}/{$file}")) {
                $zip->addFile("{$passDir}/{$file}", $file);
            }
        }
        
        foreach ($imageFiles as $imageFile) {
            if (file_exists("{$passDir}/{$imageFile}")) {
                $zip->addFile("{$passDir}/{$imageFile}", $imageFile);
            }
        }
        
        $zip->close();
        
        // Clean up temporary files
        unlink($manifestPath);
        unlink($signaturePath);
        foreach ($imageFiles as $imageFile) {
            if (file_exists("{$passDir}/{$imageFile}")) {
                unlink("{$passDir}/{$imageFile}");
            }
        }
    }

    /**
     * Get pass download URL
     */
    public function getPassDownloadUrl(WalletPass $walletPass): string
    {
        return url("/api/wallet/apple/download/{$walletPass->pass_id}");
    }

    /**
     * Validate pass webhook
     */
    public function validateWebhook(string $authenticationToken): ?WalletPass
    {
        return WalletPass::where('pass_id', $authenticationToken)
            ->where('wallet_type', 'apple')
            ->first();
    }
} 