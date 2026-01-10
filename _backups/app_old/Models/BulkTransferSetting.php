<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkTransferSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'feature_enabled',
        'max_file_size_mb',
        'retention_hours',
        'password_protection_enabled',
        'daily_transfer_limit',
        'monthly_transfer_limit',
        'max_storage_gb'
    ];

    protected $casts = [
        'feature_enabled' => 'boolean',
        'password_protection_enabled' => 'boolean',
        'max_file_size_mb' => 'integer',
        'retention_hours' => 'integer',
        'daily_transfer_limit' => 'integer',
        'monthly_transfer_limit' => 'integer',
        'max_storage_gb' => 'integer'
    ];

    /**
     * Get the plan that owns the setting
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get global settings (where plan_id is null)
     */
    public static function getGlobalSettings()
    {
        return self::whereNull('plan_id')->first();
    }

    /**
     * Get settings for a specific plan
     */
    public static function getPlanSettings($planId)
    {
        return self::where('plan_id', $planId)->first();
    }

    /**
     * Get effective settings for a user
     */
    public static function getEffectiveSettings($user)
    {
        // First try to get plan-specific settings
        if ($user && $user->plan) {
            $planSettings = self::getPlanSettings($user->plan);
            if ($planSettings) {
                return $planSettings;
            }
        }

        // Fall back to global settings
        return self::getGlobalSettings();
    }

    /**
     * Check if feature is enabled for user
     */
    public static function isFeatureEnabled($user)
    {
        if (!$user) {
            return false;
        }
        
        $settings = self::getEffectiveSettings($user);
        return $settings && $settings->feature_enabled;
    }

    /**
     * Get max file size in bytes
     */
    public function getMaxFileSizeBytes()
    {
        return $this->max_file_size_mb * 1024 * 1024;
    }

    /**
     * Get max storage in bytes
     */
    public function getMaxStorageBytes()
    {
        return $this->max_storage_gb * 1024 * 1024 * 1024;
    }
}
