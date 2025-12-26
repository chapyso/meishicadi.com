<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\TapLog;
use App\Services\GeolocationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TapController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    /**
     * Track a tap/visit to a business card with detailed analytics
     */
    public function trackTap(Request $request, $slug): JsonResponse
    {
        try {
            $business = Business::where('slug', $slug)->first();
            
            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business card not found'
                ], 404);
            }

            // Get request data
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            $referrer = $request->header('referer');
            $sessionId = $request->session()->getId();

            // Determine tap type
            $tapType = $this->determineTapType($request);

            // Get location data
            $locationData = $this->geolocationService->getLocationFromIp($ipAddress);
            
            // Get device and browser info
            $deviceInfo = $this->geolocationService->getBrowserInfo($userAgent);
            $deviceType = $this->geolocationService->getDeviceType($userAgent);
            
            // Extract UTM parameters
            $utmParams = $this->geolocationService->extractUtmParams($referrer);

            // Create tap log entry
            $tapLog = TapLog::create([
                'business_id' => $business->id,
                'ip_address' => $ipAddress,
                'country' => $locationData['country'],
                'city' => $locationData['city'],
                'region' => $locationData['region'],
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'timezone' => $locationData['timezone'],
                'user_agent' => $userAgent,
                'device_type' => $deviceType,
                'browser' => $deviceInfo['browser'],
                'platform' => $deviceInfo['platform'],
                'referrer' => $referrer,
                'utm_source' => $utmParams['utm_source'] ?? null,
                'utm_medium' => $utmParams['utm_medium'] ?? null,
                'utm_campaign' => $utmParams['utm_campaign'] ?? null,
                'tap_type' => $tapType,
                'session_id' => $sessionId,
            ]);

            // Increment tap count
            $newCount = $business->incrementTapCount();

            return response()->json([
                'success' => true,
                'message' => 'Tap tracked successfully',
                'tap_count' => $newCount,
                'formatted_tap_count' => $business->getFormattedTapCount(),
                'location' => $locationData,
                'device_info' => $deviceInfo,
                'tap_type' => $tapType
            ]);

        } catch (\Exception $e) {
            Log::error('Error tracking tap', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error tracking tap'
            ], 500);
        }
    }

    /**
     * Determine the type of tap based on request data
     */
    protected function determineTapType(Request $request): string
    {
        $userAgent = strtolower($request->userAgent());
        $referrer = $request->header('referer');
        $queryParams = $request->query();

        // Check for QR scan indicators
        if (strpos($userAgent, 'qr') !== false || 
            strpos($userAgent, 'scanner') !== false ||
            isset($queryParams['qr_scan'])) {
            return 'qr_scan';
        }

        // Check for NFC indicators
        if (strpos($userAgent, 'nfc') !== false ||
            isset($queryParams['nfc'])) {
            return 'nfc';
        }

        // Check for shared link indicators
        if (isset($queryParams['utm_source']) || 
            isset($queryParams['utm_medium']) ||
            isset($queryParams['utm_campaign']) ||
            (strpos($referrer, 'facebook.com') !== false) ||
            (strpos($referrer, 'twitter.com') !== false) ||
            (strpos($referrer, 'linkedin.com') !== false) ||
            (strpos($referrer, 'whatsapp.com') !== false)) {
            return 'share_link';
        }

        return 'direct';
    }

    /**
     * Get tap count for a business card
     */
    public function getTapCount($slug): JsonResponse
    {
        try {
            $business = Business::where('slug', $slug)->first();
            
            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business card not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'tap_count' => $business->tap_count,
                'formatted_tap_count' => $business->getFormattedTapCount()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting tap count', [
                'slug' => $slug,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting tap count'
            ], 500);
        }
    }

    /**
     * Get tap statistics for admin dashboard
     */
    public function getTapStatistics(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get all businesses for the user
            $businesses = Business::where('created_by', $user->creatorId())
                ->select('id', 'title', 'slug', 'tap_count', 'created_at')
                ->orderBy('tap_count', 'desc')
                ->get();

            $totalTaps = $businesses->sum('tap_count');
            $totalCards = $businesses->count();
            $averageTaps = $totalCards > 0 ? round($totalTaps / $totalCards, 1) : 0;

            return response()->json([
                'success' => true,
                'statistics' => [
                    'total_taps' => $totalTaps,
                    'total_cards' => $totalCards,
                    'average_taps_per_card' => $averageTaps,
                    'top_performing_cards' => $businesses->take(5)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting tap statistics', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting tap statistics'
            ], 500);
        }
    }

    /**
     * Get detailed tap analytics for a specific business
     */
    public function getBusinessAnalytics(Request $request, $businessId): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $business = Business::where('id', $businessId)
                ->where('created_by', $user->creatorId())
                ->first();

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            $period = $request->get('period', '30days');
            $statistics = $business->getTapStatistics($period);

            // Get recent tap logs
            $recentLogs = $business->getRecentTapLogs(20);

            // Get geographic distribution
            $geographicData = TapLog::where('business_id', $business->id)
                ->selectRaw('country, city, COUNT(*) as count')
                ->whereNotNull('country')
                ->groupBy('country', 'city')
                ->orderBy('count', 'desc')
                ->limit(20)
                ->get();

            // Get time-based analytics
            $timeAnalytics = TapLog::where('business_id', $business->id)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get();

            return response()->json([
                'success' => true,
                'business' => [
                    'id' => $business->id,
                    'title' => $business->title,
                    'slug' => $business->slug,
                    'total_tap_count' => $business->tap_count
                ],
                'statistics' => [
                    'total_taps' => $statistics['total_taps'] ?? 0,
                    'unique_visitors' => $statistics['unique_visitors'] ?? 0,
                    'by_type' => $statistics['by_type'] ?? [],
                    'by_country' => $statistics['by_country'] ?? [],
                    'by_device' => $statistics['by_device'] ?? []
                ],
                'recent_logs' => $recentLogs,
                'geographic_data' => $geographicData,
                'time_analytics' => $timeAnalytics
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting business analytics', [
                'business_id' => $businessId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting business analytics: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get tap logs with filtering
     */
    public function getTapLogs(Request $request, $businessId): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $business = Business::where('id', $businessId)
                ->where('created_by', $user->creatorId())
                ->first();

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            $query = $business->tapLogs()->with('business');

            // Apply filters
            if ($request->has('tap_type')) {
                $query->tapType($request->tap_type);
            }

            if ($request->has('country')) {
                $query->country($request->country);
            }

            if ($request->has('device_type')) {
                $query->deviceType($request->device_type);
            }

            if ($request->has('date_from') && $request->has('date_to')) {
                $query->dateRange($request->date_from, $request->date_to);
            }

            $perPage = $request->get('per_page', 20);
            $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'logs' => $logs
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting tap logs', [
                'business_id' => $businessId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting tap logs'
            ], 500);
        }
    }

    /**
     * Export tap logs as CSV
     */
    public function exportTapLogs(Request $request, $businessId): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $business = Business::where('id', $businessId)
                ->where('created_by', $user->creatorId())
                ->first();

            if (!$business) {
                return response()->json([
                    'success' => false,
                    'message' => 'Business not found'
                ], 404);
            }

            $query = $business->tapLogs();

            // Apply filters
            if ($request->has('tap_type')) {
                $query->tapType($request->tap_type);
            }

            if ($request->has('date_from') && $request->has('date_to')) {
                $query->dateRange($request->date_from, $request->date_to);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            // Generate CSV
            $filename = "tap_logs_{$business->slug}_" . date('Y-m-d_H-i-s') . '.csv';
            $filepath = storage_path('app/public/exports/' . $filename);

            // Ensure directory exists
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            $handle = fopen($filepath, 'w');

            // CSV headers
            fputcsv($handle, [
                'Date/Time',
                'Tap Type',
                'Country',
                'City',
                'Device Type',
                'Browser',
                'Platform',
                'IP Address',
                'Referrer',
                'UTM Source',
                'UTM Medium',
                'UTM Campaign'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->tap_type_label,
                    $log->country,
                    $log->city,
                    $log->device_type,
                    $log->browser,
                    $log->platform,
                    $log->ip_address,
                    $log->referrer,
                    $log->utm_source,
                    $log->utm_medium,
                    $log->utm_campaign
                ]);
            }

            fclose($handle);

            return response()->json([
                'success' => true,
                'message' => 'Export completed successfully',
                'download_url' => asset('storage/exports/' . $filename)
            ]);

        } catch (\Exception $e) {
            Log::error('Error exporting tap logs', [
                'business_id' => $businessId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error exporting tap logs'
            ], 500);
        }
    }
}
