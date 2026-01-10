<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BulkTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_name',
        'original_name',
        'file_path',
        'file_size',
        'file_type',
        'transfer_token',
        'password',
        'message',
        'expires_at',
        'status',
        'download_count',
        'last_downloaded_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer'
    ];

    protected $appends = [
        'file_size_formatted',
        'time_remaining',
        'is_expired',
        'download_url'
    ];

    /**
     * Get the user that owns the transfer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique transfer token
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(32);
        } while (self::where('transfer_token', $token)->exists());

        return $token;
    }

    /**
     * Check if transfer is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Get formatted file size
     */
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get time remaining until expiration
     */
    public function getTimeRemainingAttribute()
    {
        if ($this->is_expired) {
            return 'Expired';
        }

        $now = Carbon::now();
        $expires = $this->expires_at;
        $diff = $now->diff($expires);

        if ($diff->days > 0) {
            return $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'm';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        } else {
            return $diff->i . 'm ' . $diff->s . 's';
        }
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute()
    {
        return route('bulk-transfer.download', $this->transfer_token);
    }

    /**
     * Scope for active transfers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope for expired transfers
     */
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'expired')
              ->orWhere('expires_at', '<=', Carbon::now());
        });
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => Carbon::now()]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Check if password protection is enabled
     */
    public function hasPasswordProtection()
    {
        return !empty($this->password);
    }

    /**
     * Verify password
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}
