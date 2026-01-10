<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\WalletPass;
use App\Models\Plan;
use App\Services\AppleWalletService;
use App\Services\GoogleWalletService;
use App\Mail\WalletPassEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $appleWalletService;
    protected $googleWalletService;

    public function __construct(AppleWalletService $appleWalletService, GoogleWalletService $googleWalletService)
    {
        $this->appleWalletService = $appleWalletService;
        $this->googleWalletService = $googleWalletService;
    }

    /**
     * Show user's wallet page with all their businesses
     */
    public function userWalletIndex()
    {
        $user = Auth::user();
        $plan = Plan::find($user->plan);

        // Check if user has wallet feature enabled
        if (!$plan || $plan->enable_wallet !== 'on') {
            return redirect()->route('plans.index')->with('error', __('Wallet feature is only available for premium users.'));
        }

        // Get all user's businesses
        $businesses = Business::where('created_by', $user->creatorId())->get();
        
        // Get existing wallet passes for each business
        $walletPasses = WalletPass::where('user_id', $user->id)
            ->get()
            ->keyBy('business_id');

        return view('wallet.user.index', compact('businesses', 'walletPasses', 'plan'));
    }

    /**
     * Show wallet options for a business
     */
    public function showWalletOptions($businessId)
    {
        $business = Business::findOrFail($businessId);
        $user = Auth::user();
        $plan = Plan::find($user->plan);

        // Check if user has wallet feature enabled
        if (!$plan || $plan->enable_wallet !== 'on') {
            return redirect()->route('plans.index')->with('error', __('Wallet feature is only available for premium users.'));
        }

        // Check if user owns this business
        if ($business->created_by !== $user->creatorId()) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $existingPasses = WalletPass::where('business_id', $businessId)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('wallet_type');

        return view('wallet.options', compact('business', 'existingPasses'));
    }

    /**
     * Generate Apple Wallet pass
     */
    public function generateApplePass(Request $request, $businessId)
    {
        $business = Business::findOrFail($businessId);
        $user = Auth::user();
        $plan = Plan::find($user->plan);

        // Check if user has wallet feature enabled
        if (!$plan || $plan->enable_wallet !== 'on') {
            return response()->json(['error' => __('Wallet feature is only available for premium users.')], 403);
        }

        // Check if user owns this business
        if ($business->created_by !== $user->creatorId()) {
            return response()->json(['error' => __('Permission denied.')], 403);
        }

        try {
            // Check if pass already exists
            $existingPass = WalletPass::where('business_id', $businessId)
                ->where('user_id', $user->id)
                ->where('wallet_type', 'apple')
                ->first();

            if ($existingPass) {
                return response()->json([
                    'success' => true,
                    'message' => __('Apple Wallet pass already exists.'),
                    'download_url' => $this->appleWalletService->getPassDownloadUrl($existingPass)
                ]);
            }

            // Create new wallet pass
            $walletPass = WalletPass::create([
                'business_id' => $businessId,
                'user_id' => $user->id,
                'wallet_type' => 'apple',
                'pass_id' => WalletPass::generatePassId(),
                'serial_number' => WalletPass::generateSerialNumber(),
                'status' => 'active',
                'expires_at' => now()->addDays(config('wallet.pass.expires_after_days')),
            ]);

            // Generate the pass
            if ($this->appleWalletService->generatePass($business, $walletPass)) {
                // Send email notification
                $this->sendWalletPassEmail($business, $walletPass, 'apple');

                return response()->json([
                    'success' => true,
                    'message' => __('Apple Wallet pass generated successfully.'),
                    'download_url' => $this->appleWalletService->getPassDownloadUrl($walletPass)
                ]);
            } else {
                $walletPass->delete();
                return response()->json(['error' => __('Failed to generate Apple Wallet pass.')], 500);
            }
        } catch (\Exception $e) {
            Log::error('Apple Wallet pass generation error: ' . $e->getMessage());
            return response()->json(['error' => __('An error occurred while generating the pass.')], 500);
        }
    }

    /**
     * Generate Google Wallet pass
     */
    public function generateGooglePass(Request $request, $businessId)
    {
        $business = Business::findOrFail($businessId);
        $user = Auth::user();
        $plan = Plan::find($user->plan);

        // Check if user has wallet feature enabled
        if (!$plan || $plan->enable_wallet !== 'on') {
            return response()->json(['error' => __('Wallet feature is only available for premium users.')], 403);
        }

        // Check if user owns this business
        if ($business->created_by !== $user->creatorId()) {
            return response()->json(['error' => __('Permission denied.')], 403);
        }

        try {
            // Check if pass already exists
            $existingPass = WalletPass::where('business_id', $businessId)
                ->where('user_id', $user->id)
                ->where('wallet_type', 'google')
                ->first();

            if ($existingPass) {
                return response()->json([
                    'success' => true,
                    'message' => __('Google Wallet pass already exists.'),
                    'save_url' => $this->googleWalletService->getPassSaveUrl($existingPass)
                ]);
            }

            // Create new wallet pass
            $walletPass = WalletPass::create([
                'business_id' => $businessId,
                'user_id' => $user->id,
                'wallet_type' => 'google',
                'pass_id' => WalletPass::generatePassId(),
                'serial_number' => WalletPass::generateSerialNumber(),
                'status' => 'active',
                'expires_at' => now()->addDays(config('wallet.pass.expires_after_days')),
            ]);

            // Generate the pass
            if ($this->googleWalletService->generatePass($business, $walletPass)) {
                // Send email notification
                $this->sendWalletPassEmail($business, $walletPass, 'google');

                return response()->json([
                    'success' => true,
                    'message' => __('Google Wallet pass generated successfully.'),
                    'save_url' => $this->googleWalletService->getPassSaveUrl($walletPass)
                ]);
            } else {
                $walletPass->delete();
                return response()->json(['error' => __('Failed to generate Google Wallet pass.')], 500);
            }
        } catch (\Exception $e) {
            Log::error('Google Wallet pass generation error: ' . $e->getMessage());
            return response()->json(['error' => __('An error occurred while generating the pass.')], 500);
        }
    }

    /**
     * Download Apple Wallet pass
     */
    public function downloadApplePass($passId)
    {
        $walletPass = WalletPass::where('pass_id', $passId)
            ->where('wallet_type', 'apple')
            ->first();

        if (!$walletPass) {
            abort(404);
        }

        // Increment download count
        $walletPass->incrementDownloadCount();

        $filePath = storage_path('app/' . $walletPass->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $walletPass->pass_id . '.pkpass', [
            'Content-Type' => 'application/vnd.apple.pkpass',
            'Content-Disposition' => 'attachment; filename="' . $walletPass->pass_id . '.pkpass"'
        ]);
    }

    /**
     * Apple Wallet webhook
     */
    public function appleWebhook(Request $request)
    {
        $authenticationToken = $request->header('Authorization');
        
        if (!$authenticationToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $walletPass = $this->appleWalletService->validateWebhook($authenticationToken);
        
        if (!$walletPass) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Handle different webhook events
        $event = $request->input('event');
        
        switch ($event) {
            case 'pass-installed':
                $walletPass->incrementDownloadCount();
                break;
            case 'pass-updated':
                // Handle pass update
                break;
            case 'pass-deleted':
                $walletPass->update(['status' => 'revoked']);
                break;
        }

        return response()->json(['success' => true]);
    }

    /**
     * Send wallet pass email
     */
    private function sendWalletPassEmail(Business $business, WalletPass $walletPass, string $walletType): void
    {
        try {
            $user = Auth::user();
            
            if ($walletType === 'apple') {
                $downloadUrl = $this->appleWalletService->getPassDownloadUrl($walletPass);
            } else {
                $downloadUrl = $this->googleWalletService->getPassSaveUrl($walletPass);
            }

            Mail::to($user->email)->send(new WalletPassEmail($business, $walletPass, $walletType, $downloadUrl, $this->googleWalletService));
        } catch (\Exception $e) {
            Log::error('Failed to send wallet pass email: ' . $e->getMessage());
        }
    }

    /**
     * Admin: View all wallet passes
     */
    public function adminIndex()
    {
        if (!Auth::user()->can('manage wallet')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

        $walletPasses = WalletPass::with(['business', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('wallet.admin.index', compact('walletPasses'));
    }

    /**
     * Admin: Toggle wallet pass status
     */
    public function adminToggleStatus(Request $request, $passId)
    {
        if (!Auth::user()->can('manage wallet')) {
            return response()->json(['error' => __('Permission denied.')], 403);
        }

        $walletPass = WalletPass::findOrFail($passId);
        $newStatus = $walletPass->status === 'active' ? 'revoked' : 'active';
        
        $walletPass->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => __('Wallet pass status updated successfully.'),
            'new_status' => $newStatus
        ]);
    }

    /**
     * Admin: Resend wallet pass email
     */
    public function adminResendEmail(Request $request, $passId)
    {
        if (!Auth::user()->can('manage wallet')) {
            return response()->json(['error' => __('Permission denied.')], 403);
        }

        $walletPass = WalletPass::with(['business', 'user'])->findOrFail($passId);
        
        $this->sendWalletPassEmail($walletPass->business, $walletPass, $walletPass->wallet_type);

        return response()->json([
            'success' => true,
            'message' => __('Wallet pass email sent successfully.')
        ]);
    }
} 