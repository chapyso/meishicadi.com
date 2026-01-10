<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmIntegration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'crm_type',
        'name',
        'credentials',
        'field_mapping',
        'is_active',
        'auto_sync',
        'last_sync_at',
        'sync_status',
        'error_message'
    ];

    protected $casts = [
        'credentials' => 'array',
        'field_mapping' => 'array',
        'is_active' => 'boolean',
        'auto_sync' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function syncLogs()
    {
        return $this->hasMany(CrmSyncLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCrmType($query, $crmType)
    {
        return $query->where('crm_type', $crmType);
    }

    // Methods
    public function getCredential($key)
    {
        return $this->credentials[$key] ?? null;
    }

    public function setCredential($key, $value)
    {
        $credentials = $this->credentials ?? [];
        $credentials[$key] = $value;
        $this->credentials = $credentials;
        $this->save();
    }

    public function getFieldMapping($meishiField)
    {
        return $this->field_mapping[$meishiField] ?? null;
    }

    public function setFieldMapping($meishiField, $crmField)
    {
        $mapping = $this->field_mapping ?? [];
        $mapping[$meishiField] = $crmField;
        $this->field_mapping = $mapping;
        $this->save();
    }

    public function updateSyncStatus($status, $errorMessage = null)
    {
        $this->sync_status = $status;
        $this->error_message = $errorMessage;
        $this->last_sync_at = now();
        $this->save();
    }

    public function isConnected()
    {
        return !empty($this->credentials) && $this->is_active;
    }

    public function getCrmDisplayName()
    {
        return ucfirst($this->crm_type) . ' - ' . $this->name;
    }
}
