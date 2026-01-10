<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Integration extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'config',
        'events',
        'is_active',
        'last_sync_at',
        'last_error_at',
        'last_error_message',
    ];

    protected $casts = [
        'config' => 'array',
        'events' => 'array',
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
        'last_error_at' => 'datetime',
    ];

    /**
     * Get the user that owns the integration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the webhook logs for this integration.
     */
    public function webhookLogs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    /**
     * Get encrypted config value
     */
    public function getConfigValue($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set encrypted config value
     */
    public function setConfigValue($key, $value)
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->config = $config;
    }

    /**
     * Check if an event is enabled
     */
    public function isEventEnabled($event): bool
    {
        return in_array($event, $this->events ?? []);
    }

    /**
     * Enable an event
     */
    public function enableEvent($event)
    {
        $events = $this->events ?? [];
        if (!in_array($event, $events)) {
            $events[] = $event;
            $this->events = $events;
        }
    }

    /**
     * Disable an event
     */
    public function disableEvent($event)
    {
        $events = $this->events ?? [];
        $this->events = array_filter($events, fn($e) => $e !== $event);
    }

    /**
     * Get available event types
     */
    public static function getAvailableEvents(): array
    {
        return [
            'card_tap' => 'Card Tap',
            'new_lead' => 'New Lead',
            'card_created' => 'Card Created',
            'appointment_booked' => 'Appointment Booked',
            'contact_form_submitted' => 'Contact Form Submitted',
        ];
    }

    /**
     * Get available integration types
     */
    public static function getAvailableTypes(): array
    {
        return [
            'webhook' => 'Webhook (Zapier/Make)',
            'hubspot' => 'HubSpot CRM',
            'zoho' => 'Zoho CRM',
            'softchap' => 'Softchap CRM',
        ];
    }

    /**
     * Get integration type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getAvailableTypes()[$this->type] ?? $this->type;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Disabled</span>';
        }

        if ($this->last_error_at && $this->last_error_at->diffInHours(now()) < 24) {
            return '<span class="badge bg-danger">Error</span>';
        }

        return '<span class="badge bg-success">Active</span>';
    }

    /**
     * Get last sync time formatted
     */
    public function getLastSyncFormattedAttribute(): string
    {
        if (!$this->last_sync_at) {
            return 'Never';
        }

        return $this->last_sync_at->diffForHumans();
    }

    /**
     * Get last error time formatted
     */
    public function getLastErrorFormattedAttribute(): string
    {
        if (!$this->last_error_at) {
            return 'None';
        }

        return $this->last_error_at->diffForHumans();
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by active status
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get webhook URL for webhook integrations
     */
    public function getWebhookUrlAttribute(): ?string
    {
        if ($this->type !== 'webhook') {
            return null;
        }

        return $this->getConfigValue('webhook_url');
    }

    /**
     * Get OAuth tokens for CRM integrations
     */
    public function getOAuthTokensAttribute(): ?array
    {
        if (!in_array($this->type, ['hubspot', 'zoho', 'softchap'])) {
            return null;
        }

        return [
            'access_token' => $this->getConfigValue('access_token'),
            'refresh_token' => $this->getConfigValue('refresh_token'),
            'expires_at' => $this->getConfigValue('expires_at'),
        ];
    }

    /**
     * Check if OAuth token is expired
     */
    public function isTokenExpired(): bool
    {
        $expiresAt = $this->getConfigValue('expires_at');
        if (!$expiresAt) {
            return true;
        }

        return now()->isAfter($expiresAt);
    }

    /**
     * Get connection status
     */
    public function getConnectionStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'disconnected';
        }

        if ($this->type === 'webhook') {
            return $this->webhook_url ? 'connected' : 'disconnected';
        }

        // For CRM integrations, check if we have valid tokens
        $tokens = $this->oauth_tokens;
        if (!$tokens || !$tokens['access_token']) {
            return 'disconnected';
        }

        if ($this->isTokenExpired()) {
            return 'expired';
        }

        return 'connected';
    }

    /**
     * Get connection status label
     */
    public function getConnectionStatusLabelAttribute(): string
    {
        $status = $this->connection_status;
        
        $labels = [
            'connected' => 'Connected',
            'disconnected' => 'Disconnected',
            'expired' => 'Token Expired',
        ];

        return $labels[$status] ?? 'Unknown';
    }

    /**
     * Get connection status badge
     */
    public function getConnectionStatusBadgeAttribute(): string
    {
        $status = $this->connection_status;
        
        $badges = [
            'connected' => '<span class="badge bg-success">Connected</span>',
            'disconnected' => '<span class="badge bg-secondary">Disconnected</span>',
            'expired' => '<span class="badge bg-warning">Token Expired</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
