@extends('layouts.admin')
@section('page-title')
    {{ __('Wallet Pass Management') }}
@endsection
@section('title')
    {{ __('Wallet Pass Management') }}
@endsection
@section('content')
    <!-- Analytics Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalPasses }}</h4>
                            <p class="mb-0">Total Passes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $activePasses }}</h4>
                            <p class="mb-0">Active Passes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalDownloads }}</h4>
                            <p class="mb-0">Total Downloads</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-download fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $expiredPasses }}</h4>
                            <p class="mb-0">Expired Passes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Type Distribution -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Wallet Type Distribution') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-apple fa-2x text-dark me-2"></i>
                            <span>Apple Wallet</span>
                        </div>
                        <span class="badge bg-dark">{{ $applePasses }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-google fa-2x text-primary me-2"></i>
                            <span>Google Wallet</span>
                        </div>
                        <span class="badge bg-primary">{{ $googlePasses }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Recent Activity') }}</h6>
                </div>
                <div class="card-body">
                    @foreach($recentActivity as $activity)
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                @if($activity->wallet_type === 'apple')
                                    <i class="fab fa-apple text-dark"></i>
                                @else
                                    <i class="fab fa-google text-primary"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                <div>{{ $activity->business->title }} - {{ $activity->user->name }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('Wallet Passes') }}</h5>
                            <p class="text-muted mb-0">{{ __('Manage all wallet passes generated by users.') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('systems.index') }}#wallet-settings" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-cog"></i> {{ __('Wallet Settings') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Business') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Wallet Type') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Downloads') }}</th>
                                    <th>{{ __('Created') }}</th>
                                    <th>{{ __('Expires') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($walletPasses as $pass)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $logo = \App\Models\Utility::get_file('card_logo/');
                                                @endphp
                                                @if($pass->business->logo)
                                                    <img src="{{ $logo . '/' . $pass->business->logo }}" 
                                                         alt="{{ $pass->business->title }}" 
                                                         class="rounded-circle me-2" 
                                                         width="40" height="40">
                                                @endif
                                                <div>
                                                    <strong>{{ $pass->business->title }}</strong><br>
                                                    <small class="text-muted">{{ $pass->business->slug }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $pass->user->name }}</strong><br>
                                                <small class="text-muted">{{ $pass->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($pass->wallet_type === 'apple')
                                                <span class="badge bg-dark">
                                                    <i class="fab fa-apple"></i> {{ __('Apple Wallet') }}
                                                </span>
                                            @else
                                                <span class="badge bg-primary">
                                                    <i class="fab fa-google"></i> {{ __('Google Wallet') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pass->status === 'active')
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @elseif($pass->status === 'expired')
                                                <span class="badge bg-warning">{{ __('Expired') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Revoked') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $pass->download_count }}</span>
                                        </td>
                                        <td>
                                            {{ $pass->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @if($pass->expires_at)
                                                @if($pass->expires_at->isPast())
                                                    <span class="text-danger">{{ __('Expired') }}</span>
                                                @else
                                                    {{ $pass->expires_at->format('M d, Y') }}
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('No expiry') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ __('Actions') }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="togglePassStatus('{{ $pass->id }}', '{{ $pass->status }}')">
                                                            @if($pass->status === 'active')
                                                                <i class="fas fa-ban text-warning"></i> {{ __('Revoke Pass') }}
                                                            @else
                                                                <i class="fas fa-check text-success"></i> {{ __('Activate Pass') }}
                                                            @endif
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="resendEmail('{{ $pass->id }}')">
                                                            <i class="fas fa-envelope text-info"></i> {{ __('Resend Email') }}
                                                        </a>
                                                    </li>
                                                    @if($pass->wallet_type === 'apple' && $pass->file_path)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('wallet.apple.download', $pass->pass_id) }}">
                                                                <i class="fas fa-download text-secondary"></i> {{ __('Download Pass') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#" 
                                                           onclick="deletePass('{{ $pass->id }}')">
                                                            <i class="fas fa-trash"></i> {{ __('Delete Pass') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No wallet passes found') }}</h5>
                                                <p class="text-muted">{{ __('Users will appear here once they generate wallet passes.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($walletPasses->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $walletPasses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $walletPasses->total() }}</h4>
                            <p class="mb-0">{{ __('Total Passes') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $walletPasses->where('status', 'active')->count() }}</h4>
                            <p class="mb-0">{{ __('Active Passes') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $walletPasses->where('wallet_type', 'apple')->count() }}</h4>
                            <p class="mb-0">{{ __('Apple Wallet') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fab fa-apple fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $walletPasses->where('wallet_type', 'google')->count() }}</h4>
                            <p class="mb-0">{{ __('Google Wallet') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fab fa-google fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
function togglePassStatus(passId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'revoked' : 'active';
    const actionText = currentStatus === 'active' ? '{{ __("revoke") }}' : '{{ __("activate") }}';
    const isActivation = currentStatus !== 'active';
    
    ModernNotification.show({
        title: isActivation ? '{{ __("Activate Wallet Pass") }}' : '{{ __("Revoke Wallet Pass") }}',
        message: `{{ __('Are you sure you want to') }} ${actionText} {{ __('this wallet pass?') }}`,
        type: isActivation ? 'success' : 'warning',
        confirmText: actionText.charAt(0).toUpperCase() + actionText.slice(1),
        confirmType: isActivation ? 'success' : 'danger'
    }).then(function(confirmed) {
        if (confirmed) {
        $.ajax({
            url: `/admin/wallet/${passId}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', response.error || '{{ __("An error occurred") }}');
                }
            },
            error: function(xhr) {
                let error = '{{ __("An error occurred") }}';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    error = xhr.responseJSON.error;
                }
                showAlert('error', error);
            }
        });
    }
}

function resendEmail(passId) {
    ModernNotification.show({
        title: '{{ __("Resend Email") }}',
        message: '{{ __("Are you sure you want to resend the wallet pass email?") }}',
        type: 'info',
        confirmText: '{{ __("Resend") }}',
        confirmType: 'info'
    }).then(function(confirmed) {
        if (confirmed) {
        $.ajax({
            url: `/admin/wallet/${passId}/resend-email`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                } else {
                    showAlert('error', response.error || '{{ __("An error occurred") }}');
                }
            },
            error: function(xhr) {
                let error = '{{ __("An error occurred") }}';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    error = xhr.responseJSON.error;
                }
                showAlert('error', error);
            }
        });
    }
}

function deletePass(passId) {
    ModernNotification.show({
        title: '{{ __("Delete Wallet Pass") }}',
        message: '{{ __("Are you sure you want to delete this wallet pass? This action cannot be undone.") }}',
        type: 'danger',
        confirmText: '{{ __("Delete") }}',
        confirmType: 'danger'
    }).then(function(confirmed) {
        if (confirmed) {
            // Implement delete functionality
            showAlert('warning', '{{ __("Delete functionality will be implemented in the next update.") }}');
        }
    });
}

function showAlert(type, message) {
    let alertClass = type === 'success' ? 'alert-success' : type === 'error' ? 'alert-danger' : 'alert-warning';
    let icon = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-exclamation-triangle';
    
    let alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="${icon}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.card-body').prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush 