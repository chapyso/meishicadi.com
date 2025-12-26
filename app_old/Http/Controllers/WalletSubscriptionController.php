<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WalletSubscriptionController extends Controller
{
    /**
     * Show the upgrade modal
     */
    public function showUpgradeModal()
    {
        return response()->json([
            'success' => true,
            'html' => view('wallet.upgrade-modal')->render()
        ]);
    }

    /**
     * Process wallet premium subscription
     */
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        
        // For now, we'll simulate a successful payment
        // In a real implementation, you would integrate with a payment gateway
        
        // Set wallet premium for 1 year (you can adjust this)
        $user->update([
            'wallet_premium' => true,
            'wallet_premium_expires_at' => Carbon::now()->addYear(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wallet premium activated successfully! You can now use Apple & Google Wallet features.',
            'redirect' => route('wallet.index')
        ]);
    }

    /**
     * Check if user has wallet premium
     */
    public function checkPremium()
    {
        $user = Auth::user();
        
        return response()->json([
            'has_premium' => $user->hasWalletPremium()
        ]);
    }
}
