<?php

namespace App\Http\Controllers;

use App\Models\TapAnalytics;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TapAnalyticsExport;

class TapAnalyticsController extends Controller
{
    /**
     * Display tap analytics for user dashboard
     */
    public function userAnalytics(Request $request)
    {
        $user = Auth::user();
        $businessId = $request->get('business_id', $user->current_business);
        $period = $request->get('period', '30'); // days
        
        $business = Business::where('id', $businessId)
                           ->where('created_by', $user->creatorId())
                           ->firstOrFail();
        
        $startDate = Carbon::now()->subDays($period);
        
        $analytics = [
            'total_taps' => TapAnalytics::getTotalTaps($businessId, $startDate),
            'taps_by_source' => TapAnalytics::getTapsBySource($businessId, $startDate),
            'taps_by_device' => TapAnalytics::getTapsByDevice($businessId, $startDate),
            'taps_by_country' => TapAnalytics::getTapsByCountry($businessId, $startDate),
            'daily_taps' => TapAnalytics::getDailyTaps($businessId, $period),
            'browser_stats' => TapAnalytics::getBrowserStats($businessId, $startDate),
            'os_stats' => TapAnalytics::getOSStats($businessId, $startDate),
            'suspicious_taps' => TapAnalytics::getSuspiciousTaps($businessId)
        ];
        
        $userBusinesses = Business::where('created_by', $user->creatorId())->get();
        
        return view('tap_analytics.user_dashboard', compact('analytics', 'business', 'userBusinesses', 'period'));
    }

    /**
     * Display admin analytics dashboard
     */
    public function adminAnalytics(Request $request)
    {
        $period = $request->get('period', '30');
        $userId = $request->get('user_id');
        $businessId = $request->get('business_id');
        
        $startDate = Carbon::now()->subDays($period);
        
        $query = TapAnalytics::with(['user', 'business']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        if ($period) {
            $query->where('created_at', '>=', $startDate);
        }
        
        $analytics = [
            'total_taps' => $query->count(),
            'taps_by_source' => $this->getAdminTapsBySource($userId, $businessId, $startDate),
            'taps_by_device' => $this->getAdminTapsByDevice($userId, $businessId, $startDate),
            'taps_by_country' => $this->getAdminTapsByCountry($userId, $businessId, $startDate),
            'top_performing_cards' => TapAnalytics::getTopPerformingCards(10, $startDate),
            'suspicious_taps' => TapAnalytics::getSuspiciousTaps(),
            'daily_taps' => $this->getAdminDailyTaps($userId, $businessId, $period)
        ];
        
        $users = User::where('type', 'company')->get();
        $businesses = Business::all();
        
        return view('tap_analytics.admin_dashboard', compact('analytics', 'users', 'businesses', 'period'));
    }

    /**
     * Record a new tap
     */
    public function recordTap(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'tap_source' => 'required|in:QR,NFC,Link,Direct',
            'card_id' => 'nullable|string'
        ]);
        
        $business = Business::findOrFail($request->business_id);
        $user = User::findOrFail($business->created_by);
        
        $tapData = [
            'user_id' => $user->id,
            'business_id' => $request->business_id,
            'card_id' => $request->card_id,
            'tap_source' => $request->tap_source,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'utm_source' => $request->get('utm_source'),
            'utm_medium' => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign')
        ];
        
        // Get device and browser info
        $deviceInfo = $this->parseUserAgent($request->userAgent());
        $tapData = array_merge($tapData, $deviceInfo);
        
        // Get location info
        $locationInfo = $this->getLocationInfo($request->ip());
        $tapData = array_merge($tapData, $locationInfo);
        
        $tap = TapAnalytics::recordTap($tapData);
        
        return response()->json([
            'success' => true,
            'tap_id' => $tap->id
        ]);
    }

    /**
     * Export analytics data
     */
    public function exportAnalytics(Request $request)
    {
        $period = $request->get('period', '30');
        $userId = $request->get('user_id');
        $businessId = $request->get('business_id');
        $format = $request->get('format', 'csv');
        
        $startDate = Carbon::now()->subDays($period);
        
        $query = TapAnalytics::with(['user', 'business']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        if ($period) {
            $query->where('created_at', '>=', $startDate);
        }
        
        $data = $query->get();
        
        $filename = 'tap_analytics_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        return Excel::download(new TapAnalyticsExport($data), $filename);
    }

    /**
     * Get analytics data for API
     */
    public function getAnalyticsData(Request $request)
    {
        $businessId = $request->get('business_id');
        $period = $request->get('period', '30');
        
        if (!$businessId) {
            return response()->json(['error' => 'Business ID required'], 400);
        }
        
        $startDate = Carbon::now()->subDays($period);
        
        $analytics = [
            'total_taps' => TapAnalytics::getTotalTaps($businessId, $startDate),
            'taps_by_source' => TapAnalytics::getTapsBySource($businessId, $startDate),
            'taps_by_device' => TapAnalytics::getTapsByDevice($businessId, $startDate),
            'taps_by_country' => TapAnalytics::getTapsByCountry($businessId, $startDate),
            'daily_taps' => TapAnalytics::getDailyTaps($businessId, $period),
            'browser_stats' => TapAnalytics::getBrowserStats($businessId, $startDate),
            'os_stats' => TapAnalytics::getOSStats($businessId, $startDate)
        ];
        
        return response()->json($analytics);
    }

    /**
     * Parse user agent to get device and browser info
     */
    private function parseUserAgent($userAgent)
    {
        $deviceType = 'desktop';
        $deviceOS = null;
        $browser = null;
        $browserVersion = null;
        
        // Detect device type
        if (preg_match('/(android|iphone|ipad|ipod)/i', $userAgent)) {
            $deviceType = 'mobile';
            if (preg_match('/ipad/i', $userAgent)) {
                $deviceType = 'tablet';
            }
        }
        
        // Detect OS
        if (preg_match('/windows/i', $userAgent)) {
            $deviceOS = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $deviceOS = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $deviceOS = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $deviceOS = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $deviceOS = 'iOS';
        }
        
        // Detect browser
        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
            if (preg_match('/chrome\/(\d+)/i', $userAgent, $matches)) {
                $browserVersion = $matches[1];
            }
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
            if (preg_match('/firefox\/(\d+)/i', $userAgent, $matches)) {
                $browserVersion = $matches[1];
            }
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
            if (preg_match('/version\/(\d+)/i', $userAgent, $matches)) {
                $browserVersion = $matches[1];
            }
        } elseif (preg_match('/edge/i', $userAgent)) {
            $browser = 'Edge';
            if (preg_match('/edge\/(\d+)/i', $userAgent, $matches)) {
                $browserVersion = $matches[1];
            }
        }
        
        return [
            'device_type' => $deviceType,
            'device_os' => $deviceOS,
            'browser' => $browser,
            'browser_version' => $browserVersion
        ];
    }

    /**
     * Get location info from IP
     */
    private function getLocationInfo($ip)
    {
        // For now, return empty values
        // In production, you would use a service like MaxMind GeoIP2 or ipapi.co
        return [
            'country' => null,
            'city' => null,
            'region' => null
        ];
    }

    /**
     * Helper methods for admin analytics
     */
    private function getAdminTapsBySource($userId, $businessId, $startDate)
    {
        $query = TapAnalytics::select('tap_source', \DB::raw('count(*) as count'))
                            ->groupBy('tap_source');
        
        if ($userId) $query->where('user_id', $userId);
        if ($businessId) $query->where('business_id', $businessId);
        if ($startDate) $query->where('created_at', '>=', $startDate);
        
        return $query->get();
    }

    private function getAdminTapsByDevice($userId, $businessId, $startDate)
    {
        $query = TapAnalytics::select('device_type', \DB::raw('count(*) as count'))
                            ->groupBy('device_type');
        
        if ($userId) $query->where('user_id', $userId);
        if ($businessId) $query->where('business_id', $businessId);
        if ($startDate) $query->where('created_at', '>=', $startDate);
        
        return $query->get();
    }

    private function getAdminTapsByCountry($userId, $businessId, $startDate)
    {
        $query = TapAnalytics::select('country', \DB::raw('count(*) as count'))
                            ->whereNotNull('country')
                            ->groupBy('country')
                            ->orderBy('count', 'desc');
        
        if ($userId) $query->where('user_id', $userId);
        if ($businessId) $query->where('business_id', $businessId);
        if ($startDate) $query->where('created_at', '>=', $startDate);
        
        return $query->get();
    }

    private function getAdminDailyTaps($userId, $businessId, $days)
    {
        $startDate = Carbon::now()->subDays($days);
        
        $query = TapAnalytics::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as count'))
                            ->where('created_at', '>=', $startDate)
                            ->groupBy('date')
                            ->orderBy('date');
        
        if ($userId) $query->where('user_id', $userId);
        if ($businessId) $query->where('business_id', $businessId);
        
        return $query->get();
    }

    /**
     * Resolve suspicious tap
     */
    public function resolveSuspiciousTap($id)
    {
        $tap = TapAnalytics::findOrFail($id);
        $tap->update([
            'is_suspicious' => false,
            'suspicious_reason' => null
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get tap count for a specific business
     */
    public function getTapCount($businessId)
    {
        $tapCount = TapAnalytics::where('business_id', $businessId)->count();
        
        return response()->json([
            'success' => true,
            'tap_count' => $tapCount
        ]);
    }

    /**
     * Increment tap count for a business
     */
    public function incrementTapCount(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'tap_source' => 'required|in:QR,NFC,Link,Direct,Button'
        ]);
        
        $business = Business::findOrFail($request->business_id);
        $user = User::findOrFail($business->created_by);
        
        $tapData = [
            'user_id' => $user->id,
            'business_id' => $request->business_id,
            'card_id' => $request->get('card_id'),
            'tap_source' => $request->tap_source,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'utm_source' => $request->get('utm_source'),
            'utm_medium' => $request->get('utm_medium'),
            'utm_campaign' => $request->get('utm_campaign')
        ];
        
        // Get device and browser info
        $deviceInfo = $this->parseUserAgent($request->userAgent());
        $tapData = array_merge($tapData, $deviceInfo);
        
        // Get location info
        $locationInfo = $this->getLocationInfo($request->ip());
        $tapData = array_merge($tapData, $locationInfo);
        
        $tap = TapAnalytics::recordTap($tapData);
        
        return response()->json([
            'success' => true,
            'tap_id' => $tap->id,
            'new_count' => TapAnalytics::where('business_id', $request->business_id)->count()
        ]);
    }
} 