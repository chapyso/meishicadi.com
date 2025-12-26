<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'crm_integration_id',
        'contact_id',
        'sync_type',
        'status',
        'details',
        'error_message',
        'started_at',
        'completed_at',
        'records_processed',
        'records_successful',
        'records_failed'
    ];

    protected $casts = [
        'details' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function crmIntegration()
    {
        return $this->belongsTo(CrmIntegration::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contacts::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySyncType($query, $syncType)
    {
        return $query->where('sync_type', $syncType);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('started_at', '>=', now()->subDays($days));
    }

    // Methods
    public function markCompleted($successCount = 0, $failedCount = 0)
    {
        $this->completed_at = now();
        $this->records_successful = $successCount;
        $this->records_failed = $failedCount;
        $this->save();
    }

    public function getDurationAttribute()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        return $this->started_at->diffInSeconds($this->completed_at);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->records_processed === 0) {
            return 0;
        }
        return round(($this->records_successful / $this->records_processed) * 100, 2);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'success' => 'bg-success',
            'failed' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }
}
