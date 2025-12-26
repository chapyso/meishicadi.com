<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BulkTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'user_id',
        'title',
        'description',
        'status',
        'total_files',
        'total_size',
        'uploaded_size',
        'uploaded_files',
        'expires_at',
        'access_token',
        'download_token',
        'settings',
        'metadata',
    ];

    protected $casts = [
        'settings' => 'array',
        'metadata' => 'array',
        'expires_at' => 'datetime',
    ];

    protected $appends = [
        'progress_percentage',
        'is_expired',
        'formatted_total_size',
        'formatted_uploaded_size',
    ];

    /**
     * Boot the model and generate unique IDs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transfer_id)) {
                $model->transfer_id = 'TR' . strtoupper(Str::random(12));
            }
            if (empty($model->access_token)) {
                $model->access_token = Str::random(64);
            }
            if (empty($model->download_token)) {
                $model->download_token = Str::random(64);
            }
        });
    }

    /**
     * Get the user that owns the transfer
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files for this transfer
     */
    public function files(): HasMany
    {
        return $this->hasMany(BulkTransferFile::class);
    }

    /**
     * Get the logs for this transfer
     */
    public function logs(): HasMany
    {
        return $this->hasMany(BulkTransferLog::class);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->total_size === 0) {
            return 0;
        }
        return round(($this->uploaded_size / $this->total_size) * 100, 2);
    }

    /**
     * Check if transfer is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get formatted total size
     */
    public function getFormattedTotalSizeAttribute(): string
    {
        return $this->formatBytes($this->total_size);
    }

    /**
     * Get formatted uploaded size
     */
    public function getFormattedUploadedSizeAttribute(): string
    {
        return $this->formatBytes($this->uploaded_size);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Scope for active transfers
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'expired')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for expired transfers
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
              ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Update transfer progress
     */
    public function updateProgress(): void
    {
        $this->uploaded_files = $this->files()->where('status', 'completed')->count();
        $this->uploaded_size = $this->files()->where('status', 'completed')->sum('size');
        $this->save();
    }

    /**
     * Check if transfer is complete
     */
    public function isComplete(): bool
    {
        return $this->uploaded_files >= $this->total_files && $this->total_files > 0;
    }

    /**
     * Mark transfer as complete
     */
    public function markAsComplete(): void
    {
        $this->status = 'completed';
        $this->save();
    }

    /**
     * Get download URL
     */
    public function getDownloadUrl(): string
    {
        return route('bulk-transfer.download', ['token' => $this->download_token]);
    }

    /**
     * Get share URL
     */
    public function getShareUrl(): string
    {
        return route('bulk-transfer.share', ['token' => $this->access_token]);
    }
} 