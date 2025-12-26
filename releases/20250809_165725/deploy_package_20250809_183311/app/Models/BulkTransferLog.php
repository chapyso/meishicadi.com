<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkTransferLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulk_transfer_id',
        'user_id',
        'file_id',
        'action',
        'status',
        'message',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the transfer that owns the log
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(BulkTransfer::class, 'bulk_transfer_id');
    }

    /**
     * Get the user that created the log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful actions
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed actions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for specific actions
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Create a log entry
     */
    public static function createLog($transferId, $action, $status = 'success', $message = null, $metadata = [], $fileId = null, $userId = null): self
    {
        return self::create([
            'bulk_transfer_id' => $transferId,
            'user_id' => $userId ?? auth()->id(),
            'file_id' => $fileId,
            'action' => $action,
            'status' => $status,
            'message' => $message,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
} 