<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('Event') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Response Code') }}</th>
                <th>{{ __('Timestamp') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>
                        <span class="badge bg-secondary">{{ $log->event_type_label }}</span>
                    </td>
                    <td>{!! $log->status_badge !!}</td>
                    <td>
                        <span class="{{ $log->response_status_class }}">
                            {{ $log->formatted_response_code }}
                        </span>
                    </td>
                    <td>{{ $log->time_ago }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewLogDetails({{ $log->id }})">
                            <i class="fas fa-eye me-1"></i>{{ __('Details') }}
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        {{ __('No webhook logs found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->links() }}
    </div>
@endif

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">{{ __('Webhook Log Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <!-- Log details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewLogDetails(logId) {
    // This would typically make an AJAX call to get detailed log information
    // For now, we'll show a placeholder
    $('#logDetailsContent').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2 text-muted">{{ __('Loading log details...') }}</p>
        </div>
    `);
    $('#logDetailsModal').modal('show');
    
    // In a real implementation, you would make an AJAX call here
    // to fetch the detailed log information including payload and response
}
</script> 