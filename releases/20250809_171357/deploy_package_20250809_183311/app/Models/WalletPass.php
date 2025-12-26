<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletPass extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'wallet_type',
        'pass_id',
        'serial_number',
        'status',
        'pass_data',
        'file_path',
        'google_wallet_object_id',
        'expires_at',
        'download_count',
        'view_count',
    ];

    protected $casts = [
        'pass_data' => 'array',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the business that owns the wallet pass.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Get the user that owns the wallet pass.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the pass is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the pass is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Generate a unique pass ID.
     */
    public static function generatePassId(): string
    {
        return 'pass_' . uniqid() . '_' . time();
    }

    /**
     * Generate a unique serial number.
     */
    public static function generateSerialNumber(): string
    {
        return 'serial_' . uniqid() . '_' . time();
    }
} 