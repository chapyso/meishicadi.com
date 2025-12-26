@extends('layouts.admin')

@section('page-title')
    {{ __('CRM Integrations') }}
@endsection

@section('title')
    {{ __('CRM Integrations') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('CRM Integrations') }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Integration Overview Cards -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-plug fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $integrations->count() }}</h4>
                                <p class="mb-0">{{ __('Active Integrations') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-sync fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $recentSyncLogs->where('status', 'success')->count() }}</h4>
                                <p class="mb-0">{{ __('Successful Syncs') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $recentSyncLogs->where('status', 'pending')->count() }}</h4>
                                <p class="mb-0">{{ __('Pending Syncs') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">{{ $recentSyncLogs->where('status', 'failed')->count() }}</h4>
                                <p class="mb-0">{{ __('Failed Syncs') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Integrations List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Your CRM Integrations') }}</h5>
                    <a href="{{ route('crm-integrations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>{{ __('Add Integration') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($integrations->count() > 0)
                    <div class="row">
                        @foreach($integrations as $integration)
                            <div class="col-md-6 mb-3">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    @if($integration->crm_type === 'hubspot')
                                                        <i class="fab fa-hubspot text-orange me-2"></i>
                                                    @elseif($integration->crm_type === 'zoho')
                                                        <i class="fas fa-cloud text-blue me-2"></i>
                                                    @endif
                                                    {{ $integration->name }}
                                                </h6>
                                                <small class="text-muted">{{ ucfirst($integration->crm_type) }}</small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('crm-integrations.show', $integration) }}">
                                                        <i class="fas fa-eye me-2"></i>{{ __('View Details') }}
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="{{ route('crm-integrations.edit', $integration) }}">
                                                        <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('crm-integrations.destroy', $integration) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirmDeleteIntegration()">
                                                                <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge {{ $integration->is_active ? 'bg-success' : 'bg-secondary' }} me-2">
                                                {{ $integration->is_active ? __('Active') : __('Inactive') }}
                                            </span>
                                            <span class="badge {{ $integration->auto_sync ? 'bg-info' : 'bg-light text-dark' }}">
                                                {{ $integration->auto_sync ? __('Auto Sync') : __('Manual Sync') }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">{{ __('Last Sync:') }}</small>
                                            <div>
                                                @if($integration->last_sync_at)
                                                    {{ $integration->last_sync_at->diffForHumans() }}
                                                    <span class="badge {{ $integration->sync_status === 'idle' ? 'bg-success' : ($integration->sync_status === 'syncing' ? 'bg-warning' : 'bg-danger') }} ms-2">
                                                        {{ ucfirst($integration->sync_status) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">{{ __('Never') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($integration->error_message)
                                            <div class="alert alert-danger alert-sm mb-3">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                {{ Str::limit($integration->error_message, 100) }}
                                            </div>
                                        @endif

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary btn-sm sync-btn" 
                                                    data-integration-id="{{ $integration->id }}"
                                                    data-integration-name="{{ $integration->name }}">
                                                <i class="fas fa-sync me-1"></i>{{ __('Sync Now') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-plug fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No CRM Integrations') }}</h5>
                        <p class="text-muted">{{ __('Connect your CRM to automatically sync leads from your business cards.') }}</p>
                        <a href="{{ route('crm-integrations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>{{ __('Add Your First Integration') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Sync Activity -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Recent Sync Activity') }}</h5>
            </div>
            <div class="card-body">
                @if($recentSyncLogs->count() > 0)
                    <div class="timeline">
                        @foreach($recentSyncLogs as $log)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $log->getStatusBadgeClass() }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $log->crmIntegration->name }}</h6>
                                            <p class="mb-1 small">{{ ucfirst($log->sync_type) }} sync</p>
                                            <small class="text-muted">{{ $log->started_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge {{ $log->getStatusBadgeClass() }}">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </div>
                                    @if($log->records_processed > 0)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                {{ $log->records_successful }}/{{ $log->records_processed }} successful
                                                @if($log->duration)
                                                    • {{ $log->duration }}s
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">{{ __('View All Activity') }}</a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">{{ __('No sync activity yet') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Sync Progress Modal -->
<div class="modal fade" id="syncProgressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Syncing Contacts') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                </div>
                <div class="text-center">
                    <p class="mb-1" id="syncStatus">{{ __('Initializing sync...') }}</p>
                    <small class="text-muted" id="syncDetails"></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
function confirmDeleteIntegration() {
    return ModernNotification.show({
        title: '{{ __("Delete Integration") }}',
        message: '{{ __("Are you sure you want to delete this integration?") }}',
        type: 'danger',
        confirmText: '{{ __("Delete") }}',
        confirmType: 'danger'
    }).then(function(confirmed) {
        if (confirmed) {
            return true; // Allow form submission
        }
        return false; // Prevent form submission
    });
}

$(document).ready(function() {
    // Sync button click handler
    $('.sync-btn').on('click', function() {
        const integrationId = $(this).data('integration-id');
        const integrationName = $(this).data('integration-name');
        
        // Show progress modal
        $('#syncProgressModal').modal('show');
        $('#syncProgressModal .modal-title').text(`Syncing to ${integrationName}`);
        
        // Start sync
        $.ajax({
            url: `/crm-integrations/${integrationId}/sync`,
            method: 'POST',
            data: {
                sync_type: 'manual',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    pollSyncStatus(response.sync_log_id);
                } else {
                    showSyncError(response.message);
                }
            },
            error: function() {
                showSyncError('{{ __("Failed to start sync") }}');
            }
        });
    });
    
    function pollSyncStatus(syncLogId) {
        const pollInterval = setInterval(function() {
            $.get(`/crm-integrations/sync/${syncLogId}/status`, function(data) {
                updateSyncProgress(data);
                
                if (data.status === 'success' || data.status === 'failed') {
                    clearInterval(pollInterval);
                    setTimeout(function() {
                        $('#syncProgressModal').modal('hide');
                        location.reload();
                    }, 2000);
                }
            });
        }, 2000);
    }
    
    function updateSyncProgress(data) {
        const progress = data.progress;
        const total = progress.processed;
        const successful = progress.successful;
        const failed = progress.failed;
        
        if (total > 0) {
            const percentage = (successful / total) * 100;
            $('.progress-bar').css('width', percentage + '%');
        }
        
        $('#syncStatus').text(`${successful} successful, ${failed} failed`);
        $('#syncDetails').text(`Processed ${total} contacts`);
    }
    
    function showSyncError(message) {
        $('#syncStatus').html(`<span class="text-danger">${message}</span>`);
        setTimeout(function() {
            $('#syncProgressModal').modal('hide');
        }, 3000);
    }
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 12px;
    width: 2px;
    height: calc(100% + 8px);
    background-color: #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}
</style>
@endpush 