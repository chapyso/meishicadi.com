<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    protected $fillable = [
        'integration_id',
        'event_type',
        'payload',
        'response_code',
        'response_body',
        'success',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'response_body' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Get the integration that owns the webhook log.
     */
    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }

    /**
     * Get available event types
     */
    public static function getAvailableEventTypes(): array
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
     * Get event type label
     */
    public function getEventTypeLabelAttribute(): string
    {
        return self::getAvailableEventTypes()[$this->event_type] ?? $this->event_type;
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->success) {
            return '<span class="badge bg-success">Success</span>';
        }

        return '<span class="badge bg-danger">Failed</span>';
    }

    /**
     * Get response status class
     */
    public function getResponseStatusClassAttribute(): string
    {
        if ($this->success) {
            return 'text-success';
        }

        if ($this->response_code >= 400 && $this->response_code < 500) {
            return 'text-warning';
        }

        return 'text-danger';
    }

    /**
     * Get formatted response code
     */
    public function getFormattedResponseCodeAttribute(): string
    {
        if (!$this->response_code) {
            return 'No Response';
        }

        $statusText = match (true) {
            $this->response_code >= 200 && $this->response_code < 300 => 'OK',
            $this->response_code >= 300 && $this->response_code < 400 => 'Redirect',
            $this->response_code >= 400 && $this->response_code < 500 => 'Client Error',
            $this->response_code >= 500 => 'Server Error',
            default => 'Unknown'
        };

        return "{$this->response_code} {$statusText}";
    }

    /**
     * Get time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope to filter by success status
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope to filter by failed status
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope to filter by event type
     */
    public function scopeEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope to filter by integration
     */
    public function scopeForIntegration($query, $integrationId)
    {
        return $query->where('integration_id', $integrationId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get payload preview (truncated)
     */
    public function getPayloadPreviewAttribute(): string
    {
        $payload = json_encode($this->payload, JSON_PRETTY_PRINT);
        return strlen($payload) > 200 ? substr($payload, 0, 200) . '...' : $payload;
    }

    /**
     * Get response body preview (truncated)
     */
    public function getResponseBodyPreviewAttribute(): string
    {
        if (!$this->response_body) {
            return 'No response body';
        }

        $body = is_array($this->response_body) ? json_encode($this->response_body, JSON_PRETTY_PRINT) : $this->response_body;
        return strlen($body) > 200 ? substr($body, 0, 200) . '...' : $body;
    }
}
