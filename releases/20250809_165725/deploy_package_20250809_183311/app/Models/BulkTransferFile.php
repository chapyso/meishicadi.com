<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BulkTransferFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulk_transfer_id',
        'file_id',
        'original_name',
        'stored_name',
        'mime_type',
        'size',
        'path',
        'status',
        'chunks_total',
        'chunks_uploaded',
        'checksum',
        'chunks_status',
        'uploaded_at',
    ];

    protected $casts = [
        'chunks_status' => 'array',
        'uploaded_at' => 'datetime',
    ];

    protected $appends = [
        'progress_percentage',
        'formatted_size',
    ];

    /**
     * Boot the model and generate unique file ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->file_id)) {
                $model->file_id = 'FILE' . strtoupper(Str::random(12));
            }
        });
    }

    /**
     * Get the transfer that owns the file
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(BulkTransfer::class, 'bulk_transfer_id');
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->chunks_total === 0) {
            return 0;
        }
        return round(($this->chunks_uploaded / $this->chunks_total) * 100, 2);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        return $this->formatBytes($this->size);
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
     * Check if file is complete
     */
    public function isComplete(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark file as complete
     */
    public function markAsComplete(): void
    {
        $this->status = 'completed';
        $this->uploaded_at = now();
        $this->save();
    }

    /**
     * Update chunk status
     */
    public function updateChunkStatus(int $chunkIndex, bool $uploaded = true): void
    {
        $chunksStatus = $this->chunks_status ?? [];
        $chunksStatus[$chunkIndex] = $uploaded;
        $this->chunks_status = $chunksStatus;
        $this->chunks_uploaded = count(array_filter($chunksStatus));
        $this->save();
    }

    /**
     * Get full storage path
     */
    public function getFullPath(): string
    {
        return storage_path('app/' . $this->path);
    }

    /**
     * Get download URL
     */
    public function getDownloadUrl(): string
    {
        return route('bulk-transfer.file.download', [
            'transfer_id' => $this->transfer->transfer_id,
            'file_id' => $this->file_id
        ]);
    }
} 