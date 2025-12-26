<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use DB;

class TapAnalytics extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'card_id',
        'tap_source',
        'ip_address',
        'country',
        'city',
        'region',
        'device_type',
        'device_os',
        'browser',
        'browser_version',
        'user_agent',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'is_suspicious',
        'suspicious_reason'
    ];

    protected $casts = [
        'is_suspicious' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the tap analytics.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business that owns the tap analytics.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get total taps for a business
     */
    public static function getTotalTaps($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId);
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->count();
    }

    /**
     * Get taps by source
     */
    public static function getTapsBySource($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId);
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->select('tap_source', DB::raw('count(*) as count'))
                    ->groupBy('tap_source')
                    ->get();
    }

    /**
     * Get taps by device type
     */
    public static function getTapsByDevice($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId);
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->select('device_type', DB::raw('count(*) as count'))
                    ->groupBy('device_type')
                    ->get();
    }

    /**
     * Get taps by country
     */
    public static function getTapsByCountry($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId)
                    ->whereNotNull('country');
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->select('country', DB::raw('count(*) as count'))
                    ->groupBy('country')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get daily taps for timeline
     */
    public static function getDailyTaps($businessId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return self::where('business_id', $businessId)
                  ->where('created_at', '>=', $startDate)
                  ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                  ->groupBy('date')
                  ->orderBy('date')
                  ->get();
    }

    /**
     * Get weekly taps
     */
    public static function getWeeklyTaps($businessId, $weeks = 12)
    {
        $startDate = Carbon::now()->subWeeks($weeks);
        
        return self::where('business_id', $businessId)
                  ->where('created_at', '>=', $startDate)
                  ->select(DB::raw('YEARWEEK(created_at) as week'), DB::raw('count(*) as count'))
                  ->groupBy('week')
                  ->orderBy('week')
                  ->get();
    }

    /**
     * Get monthly taps
     */
    public static function getMonthlyTaps($businessId, $months = 12)
    {
        $startDate = Carbon::now()->subMonths($months);
        
        return self::where('business_id', $businessId)
                  ->where('created_at', '>=', $startDate)
                  ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
                  ->groupBy('year', 'month')
                  ->orderBy('year')
                  ->orderBy('month')
                  ->get();
    }

    /**
     * Get browser statistics
     */
    public static function getBrowserStats($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId)
                    ->whereNotNull('browser');
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->select('browser', DB::raw('count(*) as count'))
                    ->groupBy('browser')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get OS statistics
     */
    public static function getOSStats($businessId, $period = null)
    {
        $query = self::where('business_id', $businessId)
                    ->whereNotNull('device_os');
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->select('device_os', DB::raw('count(*) as count'))
                    ->groupBy('device_os')
                    ->orderBy('count', 'desc')
                    ->get();
    }

    /**
     * Get suspicious taps
     */
    public static function getSuspiciousTaps($businessId = null)
    {
        $query = self::where('is_suspicious', true);
        
        if ($businessId) {
            $query->where('business_id', $businessId);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get top performing cards
     */
    public static function getTopPerformingCards($limit = 10, $period = null)
    {
        $query = self::select('business_id', 'card_id', DB::raw('count(*) as tap_count'))
                    ->groupBy('business_id', 'card_id');
        
        if ($period) {
            $query->where('created_at', '>=', $period);
        }
        
        return $query->orderBy('tap_count', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Record a new tap
     */
    public static function recordTap($data)
    {
        // Check for suspicious activity
        $isSuspicious = self::checkSuspiciousActivity($data);
        
        $tapData = array_merge($data, [
            'is_suspicious' => $isSuspicious['is_suspicious'],
            'suspicious_reason' => $isSuspicious['reason']
        ]);
        
        return self::create($tapData);
    }

    /**
     * Check for suspicious activity
     */
    private static function checkSuspiciousActivity($data)
    {
        $isSuspicious = false;
        $reason = null;
        
        // Check for rapid taps from same IP
        $recentTaps = self::where('ip_address', $data['ip_address'])
                         ->where('business_id', $data['business_id'])
                         ->where('created_at', '>=', Carbon::now()->subMinutes(5))
                         ->count();
        
        if ($recentTaps > 10) {
            $isSuspicious = true;
            $reason = 'Rapid taps from same IP';
        }
        
        // Check for taps from known bot user agents
        $botPatterns = ['bot', 'crawler', 'spider', 'scraper'];
        if (isset($data['user_agent'])) {
            foreach ($botPatterns as $pattern) {
                if (stripos($data['user_agent'], $pattern) !== false) {
                    $isSuspicious = true;
                    $reason = 'Bot user agent detected';
                    break;
                }
            }
        }
        
        return [
            'is_suspicious' => $isSuspicious,
            'reason' => $reason
        ];
    }
} 