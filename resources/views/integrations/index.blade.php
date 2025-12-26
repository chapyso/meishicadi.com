@php
    $user = \Auth::user();
@endphp
@extends('layouts.admin')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Integrations') }}</li>
@endsection
@section('page-title')
    {{ __('Integrations') }}
@endsection
@section('title')
    {{ __('Integrations') }}
@endsection
@section('action-btn')
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
        data-bs-placement="top">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWebhookModal">
            <i class="ti ti-plus"></i>
            {{ __('Add Webhook') }}
        </button>
    </div>
@endsection
@section('content')
    <!-- User Integrations Overview -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">{{ __('Your Integrations') }}</h5>
                        <p class="text-muted mb-0">{{ __('Manage integrations for all your business cards') }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary p-2 px-3 rounded me-2">
                            <i class="ti ti-user me-1"></i>
                            {{ Auth::user()->name }}
                        </span>
                        <span class="badge bg-success p-2 px-3 rounded">
                            <i class="ti ti-credit-card me-1"></i>
                            {{ Auth::user()->totalBusiness(Auth::user()->id) }} {{ __('Business Cards') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Webhook Integrations Section -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5 class="mb-0">
                    <i class="ti ti-plug me-2"></i>
                    {{ __('Automation Tools (Zapier / Make)') }}
                </h5>
                <p class="text-muted mb-3">{{ __('Connect webhooks to automate your workflow with Zapier, Make (Integromat), and other automation platforms. All events from your business cards will be sent to these webhooks.') }}</p>
                
                <div class="table-responsive">
                    <table class="table" id="webhook-table">
                        <thead>
                            <tr>
                                <th>{{ __('Integration Name') }}</th>
                                <th>{{ __('Webhook URL') }}</th>
                                <th>{{ __('Events') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Sync') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($webhookIntegrations->count() > 0)
                                @foreach($webhookIntegrations as $integration)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <i class="ti ti-plug text-primary" style="font-size: 24px;"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0">{{ $integration->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($integration->webhook_url, 50) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info p-2 px-3 rounded">{{ count($integration->events) }} Events</span>
                                        </td>
                                        <td>
                                            {!! $integration->connection_status_badge !!}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $integration->last_sync_formatted }}</small>
                                        </td>
                                        <td class="text-end">
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="testWebhook({{ $integration->id }})" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Test Webhook') }}">
                                                    <span class="text-white"><i class="ti ti-player-play"></i></span>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="viewWebhookLogs({{ $integration->id }})" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('View Logs') }}">
                                                    <span class="text-white"><i class="ti ti-list"></i></span>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="editWebhook({{ $integration->id }})" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Edit') }}">
                                                    <span class="text-white"><i class="ti ti-edit"></i></span>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="deleteIntegration({{ $integration->id }})" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Delete') }}">
                                                    <span class="text-white"><i class="ti ti-trash"></i></span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <i class="ti ti-plug text-muted" style="font-size: 48px;"></i>
                                            <h5 class="text-muted mt-3">{{ __('No Webhook Integrations') }}</h5>
                                            <p class="text-muted">{{ __('Get started by adding your first webhook integration.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- CRM Integrations Section -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5 class="mb-0">
                    <i class="ti ti-users me-2"></i>
                    {{ __('CRM Integrations') }}
                </h5>
                <p class="text-muted mb-3">{{ __('Connect your MeishiCard with popular CRM platforms to automatically sync leads and contacts from all your business cards.') }}</p>
                
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-info-circle me-2"></i>
                        <div>
                            <strong>{{ __('Integration Types:') }}</strong>
                            <ul class="mb-0 mt-1">
                                <li><strong>{{ __('Softchap CRM:') }}</strong> {{ __('Uses API key authentication - ready to use') }}</li>
                                <li><strong>{{ __('HubSpot & Zoho:') }}</strong> {{ __('Use OAuth authentication - requires administrator setup') }}</li>
                            </ul>
                            <small class="text-muted mt-2 d-block">{{ __('Note: All integrations work across all your business cards. When someone interacts with any of your cards, the data will be sent to your connected integrations.') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table" id="crm-table">
                        <thead>
                            <tr>
                                <th>{{ __('CRM Platform') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Last Sync') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- HubSpot CRM -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <i class="ti ti-brand-hubspot text-orange" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ __('HubSpot CRM') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ __('Automatically sync leads and contacts to HubSpot CRM.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $hubspotIntegration = $crmIntegrations->where('type', 'hubspot')->first();
                                    @endphp
                                    @if($hubspotIntegration)
                                        {!! $hubspotIntegration->connection_status_badge !!}
                                    @else
                                        <span class="badge bg-secondary p-2 px-3 rounded">{{ __('Not Connected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hubspotIntegration)
                                        <small class="text-muted">{{ $hubspotIntegration->last_sync_formatted }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($hubspotIntegration)
                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="testCrmConnection('hubspot')" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Test Connection') }}">
                                                <span class="text-white"><i class="ti ti-check"></i></span>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="disconnectCrm({{ $hubspotIntegration->id }})" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Disconnect') }}">
                                                <span class="text-white"><i class="ti ti-unlink"></i></span>
                                            </a>
                                        </div>
                                    @else
                                        @php
                                            $hubspotConfigured = !empty(config('services.hubspot.client_id')) && !empty(config('services.hubspot.client_secret'));
                                        @endphp
                                        @if($hubspotConfigured)
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="connectHubSpot()" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Connect to HubSpot') }}">
                                                    <span class="text-white"><i class="ti ti-plug"></i></span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="showOAuthSetupModal('hubspot')" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Setup Required - Click for Instructions') }}">
                                                    <span class="text-white"><i class="ti ti-settings"></i></span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            <!-- Zoho CRM -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <i class="ti ti-brand-zoho text-blue" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ __('Zoho CRM') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ __('Sync leads and contacts to Zoho CRM automatically.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $zohoIntegration = $crmIntegrations->where('type', 'zoho')->first();
                                    @endphp
                                    @if($zohoIntegration)
                                        {!! $zohoIntegration->connection_status_badge !!}
                                    @else
                                        <span class="badge bg-secondary p-2 px-3 rounded">{{ __('Not Connected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($zohoIntegration)
                                        <small class="text-muted">{{ $zohoIntegration->last_sync_formatted }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($zohoIntegration)
                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="testCrmConnection('zoho')" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Test Connection') }}">
                                                <span class="text-white"><i class="ti ti-check"></i></span>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="disconnectCrm({{ $zohoIntegration->id }})" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Disconnect') }}">
                                                <span class="text-white"><i class="ti ti-unlink"></i></span>
                                            </a>
                                        </div>
                                    @else
                                        @php
                                            $zohoConfigured = !empty(config('services.zoho.client_id')) && !empty(config('services.zoho.client_secret'));
                                        @endphp
                                        @if($zohoConfigured)
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="connectZoho()" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Connect to Zoho') }}">
                                                    <span class="text-white"><i class="ti ti-plug"></i></span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                   onclick="showOAuthSetupModal('zoho')" data-bs-toggle="tooltip"
                                                   data-bs-original-title="{{ __('Setup Required - Click for Instructions') }}">
                                                    <span class="text-white"><i class="ti ti-settings"></i></span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            <!-- Softchap CRM -->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar">
                                            <i class="ti ti-settings text-green" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ __('Softchap CRM') }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ __('Connect to Softchap CRM using API credentials.') }}</span>
                                </td>
                                <td>
                                    @php
                                        $softchapIntegration = $crmIntegrations->where('type', 'softchap')->first();
                                    @endphp
                                    @if($softchapIntegration)
                                        {!! $softchapIntegration->connection_status_badge !!}
                                    @else
                                        <span class="badge bg-secondary p-2 px-3 rounded">{{ __('Not Connected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($softchapIntegration)
                                        <small class="text-muted">{{ $softchapIntegration->last_sync_formatted }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($softchapIntegration)
                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="testCrmConnection('softchap')" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Test Connection') }}">
                                                <span class="text-white"><i class="ti ti-check"></i></span>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               onclick="disconnectCrm({{ $softchapIntegration->id }})" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Disconnect') }}">
                                                <span class="text-white"><i class="ti ti-unlink"></i></span>
                                            </a>
                                        </div>
                                    @else
                                        <div class="action-btn bg-primary ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                               data-bs-toggle="modal" data-bs-target="#connectSoftchapModal" data-bs-toggle="tooltip"
                                               data-bs-original-title="{{ __('Connect to Softchap') }}">
                                                <span class="text-white"><i class="ti ti-plug"></i></span>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Add Webhook Modal -->
<div class="modal fade" id="addWebhookModal" tabindex="-1" aria-labelledby="addWebhookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWebhookModalLabel">{{ __('Add New Webhook Integration') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addWebhookForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="webhook_name" class="form-label">{{ __('Integration Name') }}</label>
                                <input type="text" class="form-control" id="webhook_name" name="name" required>
                                <div class="form-text">{{ __('Give your webhook a descriptive name (e.g., "Zapier Lead Sync")') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="webhook_url" class="form-label">{{ __('Webhook URL') }}</label>
                                <input type="url" class="form-control" id="webhook_url" name="webhook_url" required>
                                <div class="form-text">{{ __('Paste your Zapier or Make webhook URL here') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Trigger Events') }}</label>
                        <div class="row">
                            @foreach(App\Models\Integration::getAvailableEvents() as $event => $label)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="events[]" value="{{ $event }}" id="event_{{ $event }}">
                                        <label class="form-check-label" for="event_{{ $event }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text">{{ __('Select which events should trigger this webhook') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Add Webhook') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Connect Softchap Modal -->
<div class="modal fade" id="connectSoftchapModal" tabindex="-1" aria-labelledby="connectSoftchapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectSoftchapModalLabel">{{ __('Connect to Softchap CRM') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="connectSoftchapForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="softchap_api_key" class="form-label">{{ __('API Key') }}</label>
                        <input type="text" class="form-control" id="softchap_api_key" name="api_key" required>
                        <div class="form-text">{{ __('Enter your Softchap CRM API key') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="softchap_api_secret" class="form-label">{{ __('API Secret') }}</label>
                        <input type="password" class="form-control" id="softchap_api_secret" name="api_secret" required>
                        <div class="form-text">{{ __('Enter your Softchap CRM API secret') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Connect') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Webhook Logs Modal -->
<div class="modal fade" id="webhookLogsModal" tabindex="-1" aria-labelledby="webhookLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="webhookLogsModalLabel">{{ __('Webhook Logs') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="webhookLogsContent">
                    <!-- Logs will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OAuth Setup Instructions Modal -->
<div class="modal fade" id="oauthSetupModal" tabindex="-1" aria-labelledby="oauthSetupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="oauthSetupModalLabel">{{ __('OAuth Setup Instructions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="oauthSetupContent">
                    <!-- Instructions will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Toast Container for Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <!-- Toasts will be dynamically added here -->
</div>

@push('script-page')
<script>
$(document).ready(function() {
    // Add Webhook Form Submission
    $('#addWebhookForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        $.ajax({
            url: '{{ route("integrations.webhook.store") }}',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    $('#addWebhookModal').modal('hide');
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showNotification('error', response.message || 'An error occurred');
            }
        });
    });

    // Connect Softchap Form Submission
    $('#connectSoftchapForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        $.ajax({
            url: '{{ route("integrations.softchap.connect") }}',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    $('#connectSoftchapModal').modal('hide');
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showNotification('error', response.message || 'An error occurred');
            }
        });
    });
});

// Webhook Functions
function testWebhook(integrationId) {
    $.ajax({
        url: `/integrations/webhook/${integrationId}/test`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('success', 'Test webhook sent successfully!');
            } else {
                showNotification('error', response.message);
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showNotification('error', response.message || 'Test failed');
        }
    });
}

function viewWebhookLogs(integrationId) {
    $.ajax({
        url: `/integrations/webhook/${integrationId}/logs`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                $('#webhookLogsContent').html(response.html);
                $('#webhookLogsModal').modal('show');
            } else {
                showNotification('error', response.message);
            }
        },
        error: function() {
            showNotification('error', 'Failed to load logs');
        }
    });
}

function editWebhook(integrationId) {
    // Implement edit functionality
    showNotification('info', 'Edit functionality coming soon');
}

function toggleWebhookStatus(integrationId) {
    $.ajax({
        url: `/integrations/${integrationId}/toggle`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('success', 'Integration status updated');
            } else {
                showNotification('error', response.message);
            }
        },
        error: function() {
            showNotification('error', 'Failed to update status');
        }
    });
}

function deleteIntegration(integrationId) {
    if (confirm('Are you sure you want to delete this integration?')) {
        $.ajax({
            url: `/integrations/${integrationId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Failed to delete integration');
            }
        });
    }
}

// CRM Functions
function connectHubSpot() {
    $.ajax({
        url: '{{ route("integrations.hubspot.connect") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                window.location.href = response.auth_url;
            } else {
                showNotification('error', response.message);
            }
        },
        error: function() {
            showNotification('error', 'Failed to initiate HubSpot connection');
        }
    });
}

function connectZoho() {
    $.ajax({
        url: '{{ route("integrations.zoho.connect") }}',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                window.location.href = response.auth_url;
            } else {
                showNotification('error', response.message);
            }
        },
        error: function() {
            showNotification('error', 'Failed to initiate Zoho connection');
        }
    });
}

function testCrmConnection(type) {
    showNotification('info', `Testing ${type} connection...`);
    // Implement CRM connection test
}

function disconnectCrm(integrationId) {
    if (confirm('Are you sure you want to disconnect this CRM integration?')) {
        $.ajax({
            url: `/integrations/${integrationId}/disconnect`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Failed to disconnect CRM');
            }
        });
    }
}

function showOAuthSetupModal(platform) {
    let content = '';
    let title = '';
    
    if (platform === 'hubspot') {
        title = 'HubSpot OAuth Setup';
        content = `
            <div class="alert alert-info">
                <h6><i class="ti ti-info-circle me-2"></i>HubSpot OAuth Setup Required</h6>
                <p>To connect to HubSpot CRM, you need to configure OAuth credentials in your environment.</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Step 1: Create HubSpot App</h6>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Go to <a href="https://developers.hubspot.com/" target="_blank">HubSpot Developers</a></li>
                        <li>Create a new app or use an existing one</li>
                        <li>Configure OAuth settings with redirect URI: <code>http://localhost:8001/integrations/hubspot/callback</code></li>
                        <li>Copy your Client ID and Client Secret</li>
                    </ol>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Step 2: Add to Environment</h6>
                </div>
                <div class="card-body">
                    <p>Add these variables to your <code>.env</code> file:</p>
                    <pre><code>HUBSPOT_CLIENT_ID=your_client_id_here
HUBSPOT_CLIENT_SECRET=your_client_secret_here
HUBSPOT_REDIRECT_URI=http://localhost:8001/integrations/hubspot/callback</code></pre>
                </div>
            </div>
        `;
    } else if (platform === 'zoho') {
        title = 'Zoho CRM OAuth Setup';
        content = `
            <div class="alert alert-info">
                <h6><i class="ti ti-info-circle me-2"></i>Zoho CRM OAuth Setup Required</h6>
                <p>To connect to Zoho CRM, you need to configure OAuth credentials in your environment.</p>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6>Step 1: Create Zoho App</h6>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Go to <a href="https://api-console.zoho.com/" target="_blank">Zoho API Console</a></li>
                        <li>Create a new client application</li>
                        <li>Set redirect URI to: <code>http://localhost:8001/integrations/zoho/callback</code></li>
                        <li>Copy your Client ID and Client Secret</li>
                    </ol>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Step 2: Add to Environment</h6>
                </div>
                <div class="card-body">
                    <p>Add these variables to your <code>.env</code> file:</p>
                    <pre><code>ZOHO_CLIENT_ID=your_client_id_here
ZOHO_CLIENT_SECRET=your_client_secret_here
ZOHO_REDIRECT_URI=http://localhost:8001/integrations/zoho/callback</code></pre>
                </div>
            </div>
        `;
    }
    
    $('#oauthSetupModalLabel').text(title);
    $('#oauthSetupContent').html(content);
    $('#oauthSetupModal').modal('show');
}

// Utility Functions
function showNotification(type, message) {
    const toast = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    $('.toast-container').append(toast);
    $('.toast').toast('show');
}
</script>
@endpush 