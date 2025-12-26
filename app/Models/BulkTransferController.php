<?php

namespace App\Http\Controllers;

use App\Models\BulkTransfer;
use App\Models\BulkTransferSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BulkTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the bulk transfer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has access to bulk transfer
        if (!BulkTransferSetting::isFeatureEnabled($user)) {
            return redirect()->back()->with('error', 'Bulk transfer feature is not available for your plan.');
        }

        $settings = BulkTransferSetting::getEffectiveSettings($user);
        $transfers = BulkTransfer::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        // Calculate user's storage usage
        $totalStorageUsed = BulkTransfer::where('user_id', $user->id)
                                       ->where('status', 'active')
                                       ->sum('file_size');

        return view('bulk-transfer.index', compact('transfers', 'settings', 'totalStorageUsed'));
    }

    /**
     * Show the upload form
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!BulkTransferSetting::isFeatureEnabled($user)) {
            return redirect()->back()->with('error', 'Bulk transfer feature is not available for your plan.');
        }

        $settings = BulkTransferSetting::getEffectiveSettings($user);
        
        return view('bulk-transfer.create', compact('settings'));
    }

    /**
     * Handle file upload
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!BulkTransferSetting::isFeatureEnabled($user)) {
            return response()->json(['error' => 'Feature not available'], 403);
        }

        $settings = BulkTransferSetting::getEffectiveSettings($user);

        // Validate request
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|max:' . $settings->getMaxFileSizeBytes(),
            'password' => 'nullable|string|min:4|max:50',
            'email_recipient' => 'nullable|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check daily/monthly limits
        $todayTransfers = BulkTransfer::where('user_id', $user->id)
                                     ->whereDate('created_at', Carbon::today())
                                     ->count();
        
        $monthlyTransfers = BulkTransfer::where('user_id', $user->id)
                                       ->whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->count();

        if ($todayTransfers >= $settings->daily_transfer_limit) {
            return response()->json(['error' => 'Daily transfer limit reached'], 429);
        }

        if ($monthlyTransfers >= $settings->monthly_transfer_limit) {
            return response()->json(['error' => 'Monthly transfer limit reached'], 429);
        }

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = 'bulk_transfers/' . $user->id . '/' . $fileName;
                
                // Store file
                Storage::disk('local')->put($filePath, file_get_contents($file));
                
                // Create transfer record
                $transfer = BulkTransfer::create([
                    'user_id' => $user->id,
                    'file_name' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                    'transfer_token' => BulkTransfer::generateToken(),
                    'password' => $request->password ? password_hash($request->password, PASSWORD_DEFAULT) : null,
                    'expires_at' => Carbon::now()->addHours($settings->retention_hours),
                    'status' => 'active'
                ]);

                $uploadedFiles[] = $transfer;

                // Send email if recipient provided
                if ($request->email_recipient) {
                    $this->sendTransferEmail($transfer, $request->email_recipient);
                }

            } catch (\Exception $e) {
                $errors[] = 'Failed to upload ' . $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'errors' => $errors
        ]);
    }

    /**
     * Download a file
     */
    public function download($token)
    {
        $transfer = BulkTransfer::where('transfer_token', $token)->first();
        
        if (!$transfer) {
            abort(404, 'File not found');
        }

        if ($transfer->is_expired) {
            abort(410, 'File has expired');
        }

        // Check password if required
        if ($transfer->hasPasswordProtection()) {
            if (!request()->has('password') || !$transfer->verifyPassword(request('password'))) {
                return view('bulk-transfer.password', compact('transfer'));
            }
        }

        // Check if file exists
        if (!Storage::disk('local')->exists($transfer->file_path)) {
            abort(404, 'File not found on server');
        }

        // Increment download count
        $transfer->incrementDownload();

        // Return file download
        return Storage::disk('local')->download($transfer->file_path, $transfer->original_name);
    }

    /**
     * Delete a transfer
     */
    public function destroy($id)
    {
        $transfer = BulkTransfer::where('id', $id)
                               ->where('user_id', Auth::id())
                               ->first();

        if (!$transfer) {
            return response()->json(['error' => 'Transfer not found'], 404);
        }

        // Delete file from storage
        if (Storage::disk('local')->exists($transfer->file_path)) {
            Storage::disk('local')->delete($transfer->file_path);
        }

        // Delete record
        $transfer->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Admin: Show all transfers
     */
    public function adminIndex()
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403);
        }

        $transfers = BulkTransfer::with('user')
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);

        $stats = [
            'total_transfers' => BulkTransfer::count(),
            'active_transfers' => BulkTransfer::active()->count(),
            'expired_transfers' => BulkTransfer::expired()->count(),
            'total_storage_used' => BulkTransfer::where('status', 'active')->sum('file_size')
        ];

        return view('bulk-transfer.admin.index', compact('transfers', 'stats'));
    }

    /**
     * Admin: Show settings
     */
    public function adminSettings()
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403);
        }

        $globalSettings = BulkTransferSetting::getGlobalSettings();
        $planSettings = BulkTransferSetting::whereNotNull('plan_id')->with('plan')->get();

        return view('bulk-transfer.admin.settings', compact('globalSettings', 'planSettings'));
    }

    /**
     * Admin: Update global settings
     */
    public function updateGlobalSettings(Request $request)
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'feature_enabled' => 'boolean',
            'max_file_size_mb' => 'required|integer|min:1|max:10240', // max 10GB
            'retention_hours' => 'required|integer|min:1|max:720', // max 30 days
            'password_protection_enabled' => 'boolean',
            'daily_transfer_limit' => 'required|integer|min:1|max:1000',
            'monthly_transfer_limit' => 'required|integer|min:1|max:10000',
            'max_storage_gb' => 'required|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $globalSettings = BulkTransferSetting::getGlobalSettings();
        
        if (!$globalSettings) {
            BulkTransferSetting::create([
                'plan_id' => null,
                'feature_enabled' => $request->feature_enabled,
                'max_file_size_mb' => $request->max_file_size_mb,
                'retention_hours' => $request->retention_hours,
                'password_protection_enabled' => $request->password_protection_enabled,
                'daily_transfer_limit' => $request->daily_transfer_limit,
                'monthly_transfer_limit' => $request->monthly_transfer_limit,
                'max_storage_gb' => $request->max_storage_gb
            ]);
        } else {
            $globalSettings->update($request->all());
        }

        return redirect()->back()->with('success', 'Global settings updated successfully');
    }

    /**
     * Admin: Bulk delete expired files
     */
    public function bulkDeleteExpired()
    {
        if (Auth::user()->type !== 'super admin') {
            abort(403);
        }

        $expiredTransfers = BulkTransfer::expired()->get();
        $deletedCount = 0;

        foreach ($expiredTransfers as $transfer) {
            if (Storage::disk('local')->exists($transfer->file_path)) {
                Storage::disk('local')->delete($transfer->file_path);
            }
            $transfer->delete();
            $deletedCount++;
        }

        return response()->json(['success' => true, 'deleted_count' => $deletedCount]);
    }

    /**
     * Send transfer email
     */
    private function sendTransferEmail($transfer, $email)
    {
        // This would integrate with your existing email system
        // For now, we'll just log it
        \Log::info("Transfer email would be sent to {$email} for file: {$transfer->original_name}");
    }

    /**
     * Get user's transfer statistics
     */
    public function getStats()
    {
        $user = Auth::user();
        
        $stats = [
            'total_transfers' => BulkTransfer::where('user_id', $user->id)->count(),
            'active_transfers' => BulkTransfer::where('user_id', $user->id)->active()->count(),
            'total_downloads' => BulkTransfer::where('user_id', $user->id)->sum('download_count'),
            'storage_used' => BulkTransfer::where('user_id', $user->id)->where('status', 'active')->sum('file_size')
        ];

        return response()->json($stats);
    }
}
