@extends('layouts.admin')
@section('page-title')
    {{ __('Bulk File Transfer') }}
@endsection
@section('title')
    {{ __('Bulk File Transfer') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Bulk Transfer') }}</li>
@endsection
@section('action-btn')
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
        <a href="{{ route('bulk-transfer.create') }}" class="btn btn-primary">
            <i class="ti ti-plus me-2"></i>{{ __('Upload Files') }}
        </a>
    </div>
@endsection

@push('css-page')
<style>
    .transfer-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .transfer-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .transfer-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-expired {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 18px;
    }
    
    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
    }
    
    .upload-zone.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .progress-bar {
        height: 6px;
        border-radius: 3px;
        background: #e5e7eb;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transition: width 0.3s ease;
    }
    
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
    }
    
    .copy-link {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .copy-link:hover {
        color: #3b82f6;
    }
    
    .countdown {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ti ti-upload" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1" id="total-transfers">{{ $transfers->total() }}</h4>
                        <p class="mb-0">{{ __('Total Transfers') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ti ti-clock" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1" id="active-transfers">{{ $transfers->where('status', 'active')->count() }}</h4>
                        <p class="mb-0">{{ __('Active Transfers') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ti ti-download" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1" id="total-downloads">{{ $transfers->sum('download_count') }}</h4>
                        <p class="mb-0">{{ __('Total Downloads') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="card stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ti ti-database" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ number_format($totalStorageUsed / (1024*1024*1024), 2) }} GB</h4>
                        <p class="mb-0">{{ __('Storage Used') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('My Transfers') }}</h5>
                <small class="text-muted">{{ __('Manage your file transfers') }}</small>
            </div>
            <div class="card-body">
                @if($transfers->count() > 0)
                    <div class="row">
                        @foreach($transfers as $transfer)
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                            <div class="card transfer-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon me-3" style="background: {{ $transfer->is_expired ? '#fee2e2' : '#dbeafe' }}; color: {{ $transfer->is_expired ? '#dc2626' : '#1d4ed8' }};">
                                                <i class="ti ti-file"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($transfer->original_name, 30) }}</h6>
                                                <small class="text-muted">{{ $transfer->file_size_formatted }}</small>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item copy-link" data-url="{{ $transfer->download_url }}">
                                                    <i class="ti ti-copy me-2"></i>{{ __('Copy Link') }}
                                                </a></li>
                                                @if(!$transfer->is_expired)
                                                <li><a class="dropdown-item" href="{{ $transfer->download_url }}" target="_blank">
                                                    <i class="ti ti-download me-2"></i>{{ __('Download') }}
                                                </a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger delete-transfer" data-id="{{ $transfer->id }}">
                                                    <i class="ti ti-trash me-2"></i>{{ __('Delete') }}
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="transfer-status {{ $transfer->is_expired ? 'status-expired' : 'status-active' }}">
                                            {{ $transfer->is_expired ? __('Expired') : __('Active') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="ti ti-download me-1"></i>{{ $transfer->download_count }} {{ __('downloads') }}
                                        </small>
                                    </div>
                                    
                                    @if(!$transfer->is_expired)
                                    <div class="mb-2">
                                        <small class="text-muted">{{ __('Expires in:') }}</small>
                                        <div class="countdown" data-expires="{{ $transfer->expires_at->timestamp }}">
                                            {{ $transfer->time_remaining }}
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($transfer->hasPasswordProtection())
                                    <div class="mb-2">
                                        <span class="badge bg-warning">
                                            <i class="ti ti-lock me-1"></i>{{ __('Password Protected') }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            {{ __('Uploaded') }} {{ $transfer->created_at->diffForHumans() }}
                                        </small>
                                        <small class="text-muted">
                                            {{ $transfer->file_type }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $transfers->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-upload" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h5 class="mt-3">{{ __('No transfers yet') }}</h5>
                        <p class="text-muted">{{ __('Start by uploading your first file') }}</p>
                        <a href="{{ route('bulk-transfer.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>{{ __('Upload Files') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Settings Info -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Your Plan Limits') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $settings->max_file_size_mb }} MB</h4>
                            <small class="text-muted">{{ __('Max File Size') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-success">{{ $settings->daily_transfer_limit }}</h4>
                            <small class="text-muted">{{ __('Daily Limit') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-info">{{ $settings->monthly_transfer_limit }}</h4>
                            <small class="text-muted">{{ __('Monthly Limit') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $settings->retention_hours }}h</h4>
                            <small class="text-muted">{{ __('Retention Period') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
$(document).ready(function() {
    // Copy link functionality
    $('.copy-link').on('click', function() {
        const url = $(this).data('url');
        navigator.clipboard.writeText(url).then(function() {
            toastrs('Success', 'Link copied to clipboard!', 'success');
        });
    });
    
    // Delete transfer
    $('.delete-transfer').on('click', function() {
        const transferId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this transfer?')) {
            $.ajax({
                url: '/bulk-transfer/' + transferId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastrs('Success', 'Transfer deleted successfully!', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function() {
                    toastrs('Error', 'Failed to delete transfer', 'error');
                }
            });
        }
    });
    
    // Update countdown timers
    function updateCountdowns() {
        $('.countdown').each(function() {
            const expiresTimestamp = $(this).data('expires');
            const now = Math.floor(Date.now() / 1000);
            const diff = expiresTimestamp - now;
            
            if (diff <= 0) {
                $(this).text('Expired');
                $(this).addClass('text-danger');
            } else {
                const days = Math.floor(diff / 86400);
                const hours = Math.floor((diff % 86400) / 3600);
                const minutes = Math.floor((diff % 3600) / 60);
                const seconds = diff % 60;
                
                let timeString = '';
                if (days > 0) timeString += days + 'd ';
                if (hours > 0) timeString += hours + 'h ';
                if (minutes > 0) timeString += minutes + 'm ';
                timeString += seconds + 's';
                
                $(this).text(timeString);
            }
        });
    }
    
    // Update countdown every second
    setInterval(updateCountdowns, 1000);
    updateCountdowns();
});
</script>
@endpush 