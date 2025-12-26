@extends('layouts.admin')

@section('page-title')
    {{ $crmIntegration->name }}
@endsection

@section('title')
    {{ $crmIntegration->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('crm-integrations.index') }}">{{ __('CRM Integrations') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $crmIntegration->name }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Integration Details -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Integration Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">{{ __('CRM Type') }}</label>
                    <div class="d-flex align-items-center">
                        @if($crmIntegration->crm_type === 'hubspot')
                            <i class="fab fa-hubspot text-orange me-2"></i>
                        @elseif($crmIntegration->crm_type === 'zoho')
                            <i class="fas fa-cloud text-blue me-2"></i>
                        @endif
                        <span class="fw-bold">{{ ucfirst($crmIntegration->crm_type) }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">{{ __('Status') }}</label>
                    <div>
                        <span class="badge {{ $crmIntegration->is_active ? 'bg-success' : 'bg-secondary' }} me-2">
                            {{ $crmIntegration->is_active ? __('Active') : __('Inactive') }}
                        </span>
                        <span class="badge {{ $crmIntegration->auto_sync ? 'bg-info' : 'bg-light text-dark' }}">
                            {{ $crmIntegration->auto_sync ? __('Auto Sync') : __('Manual Sync') }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">{{ __('Last Sync') }}</label>
                    <div>
                        @if($crmIntegration->last_sync_at)
                            {{ $crmIntegration->last_sync_at->format('M j, Y g:i A') }}
                            <span class="badge {{ $crmIntegration->sync_status === 'idle' ? 'bg-success' : ($crmIntegration->sync_status === 'syncing' ? 'bg-warning' : 'bg-danger') }} ms-2">
                                {{ ucfirst($crmIntegration->sync_status) }}
                            </span>
                        @else
                            <span class="text-muted">{{ __('Never') }}</span>
                        @endif
                    </div>
                </div>

                @if($crmIntegration->business)
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('Business') }}</label>
                        <div class="fw-bold">{{ $crmIntegration->business->title }}</div>
                    </div>
                @endif

                @if($crmIntegration->error_message)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>{{ __('Error:') }}</strong> {{ $crmIntegration->error_message }}
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('crm-integrations.edit', $crmIntegration) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>{{ __('Edit Integration') }}
                    </a>
                    <button class="btn btn-primary sync-btn" 
                            data-integration-id="{{ $crmIntegration->id }}"
                            data-integration-name="{{ $crmIntegration->name }}">
                        <i class="fas fa-sync me-1"></i>{{ __('Sync Now') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Field Mapping -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Field Mapping') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Meishi Field') }}</th>
                                <th>{{ __('CRM Field') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crmIntegration->field_mapping as $meishiField => $crmField)
                                <tr>
                                    <td>{{ ucfirst($meishiField) }}</td>
                                    <td><code>{{ $crmField }}</code></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Logs -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Sync History') }}</h5>
            </div>
            <div class="card-body">
                @if($syncLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Records') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($syncLogs as $log)
                                    <tr>
                                        <td>
                                            <div>{{ $log->started_at->format('M j, Y') }}</div>
                                            <small class="text-muted">{{ $log->started_at->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst($log->sync_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $log->getStatusBadgeClass() }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($log->records_processed > 0)
                                                <div>{{ $log->records_successful }}/{{ $log->records_processed }}</div>
                                                <small class="text-muted">{{ $log->success_rate }}% success</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->duration)
                                                {{ $log->duration }}s
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->error_message)
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        title="{{ $log->error_message }}">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $syncLogs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('No Sync History') }}</h5>
                        <p class="text-muted">{{ __('Sync history will appear here after your first sync.') }}</p>
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
@endpush 