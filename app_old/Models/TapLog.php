<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TapLog extends Model
{
    protected $fillable = [
        'business_id',
        'ip_address',
        'country',
        'city',
        'region',
        'latitude',
        'longitude',
        'timezone',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'tap_type',
        'session_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the business that owns the tap log.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by tap type
     */
    public function scopeTapType($query, $type)
    {
        return $query->where('tap_type', $type);
    }

    /**
     * Scope to filter by country
     */
    public function scopeCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope to filter by device type
     */
    public function scopeDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Get formatted location string
     */
    public function getLocationAttribute()
    {
        $location = [];
        
        if ($this->city) {
            $location[] = $this->city;
        }
        
        if ($this->region) {
            $location[] = $this->region;
        }
        
        if ($this->country) {
            $location[] = $this->country;
        }
        
        return implode(', ', $location);
    }

    /**
     * Get formatted device info
     */
    public function getDeviceInfoAttribute()
    {
        $info = [];
        
        if ($this->device_type) {
            $info[] = ucfirst($this->device_type);
        }
        
        if ($this->browser) {
            $info[] = $this->browser;
        }
        
        if ($this->platform) {
            $info[] = $this->platform;
        }
        
        return implode(' • ', $info);
    }

    /**
     * Get UTM parameters as array
     */
    public function getUtmParamsAttribute()
    {
        $params = [];
        
        if ($this->utm_source) {
            $params['source'] = $this->utm_source;
        }
        
        if ($this->utm_medium) {
            $params['medium'] = $this->utm_medium;
        }
        
        if ($this->utm_campaign) {
            $params['campaign'] = $this->utm_campaign;
        }
        
        return $params;
    }

    /**
     * Check if this is a mobile device
     */
    public function getIsMobileAttribute()
    {
        return in_array($this->device_type, ['mobile', 'tablet']);
    }

    /**
     * Get time ago string
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get tap type label
     */
    public function getTapTypeLabelAttribute()
    {
        $labels = [
            'direct' => 'Direct Visit',
            'qr_scan' => 'QR Code Scan',
            'share_link' => 'Shared Link',
            'nfc' => 'NFC Tap'
        ];
        
        return $labels[$this->tap_type] ?? ucfirst($this->tap_type);
    }
}
