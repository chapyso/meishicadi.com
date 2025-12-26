@extends('layouts.app')

@section('page-title')
    {{ __('Admin Tap Analytics') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('System Tap Analytics') }}</h4>
                        <div class="d-flex gap-2">
                            <select class="form-select" id="user-filter">
                                <option value="">{{ __('All Users') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select class="form-select" id="business-filter">
                                <option value="">{{ __('All Businesses') }}</option>
                                @foreach($businesses as $business)
                                    <option value="{{ $business->id }}" {{ request('business_id') == $business->id ? 'selected' : '' }}>
                                        {{ $business->title }}
                                    </option>
                                @endforeach
                            </select>
                            <select class="form-select" id="period-filter">
                                <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
                            </select>
                            <button class="btn btn-success" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>{{ __('Export') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ number_format($analytics['total_taps']) }}</h3>
                            <p class="mb-0">{{ __('Total Taps') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-tap fa-2x"></i>
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
                            <h3 class="mb-0">{{ $analytics['taps_by_source']->sum('count') }}</h3>
                            <p class="mb-0">{{ __('This Period') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
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
                            <h3 class="mb-0">{{ $analytics['top_performing_cards']->count() }}</h3>
                            <p class="mb-0">{{ __('Active Cards') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-id-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $analytics['suspicious_taps']->count() }}</h3>
                            <p class="mb-0">{{ __('Suspicious') }}</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Timeline Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('System Tap Activity Timeline') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Tap Sources -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Tap Sources Distribution') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="sourcesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Cards -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Top Performing Cards') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Rank') }}</th>
                                    <th>{{ __('Business') }}</th>
                                    <th>{{ __('Card ID') }}</th>
                                    <th>{{ __('Total Taps') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['top_performing_cards'] as $index => $card)
                                    <tr>
                                        <td>
                                            @if($index < 3)
                                                <span class="badge bg-warning text-dark">{{ $index + 1 }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($card->business)
                                                <strong>{{ $card->business->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $card->business->user->name ?? 'N/A' }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $card->card_id ?? 'Main Card' }}</td>
                                        <td>
                                            <strong>{{ number_format($card->tap_count) }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('tap-analytics.user', ['business_id' => $card->business_id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>{{ __('View Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row">
        <!-- Device Types -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Device Types') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Countries -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Top Countries') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Taps') }}</th>
                                    <th>{{ __('Percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['taps_by_country']->take(10) as $country)
                                    <tr>
                                        <td>
                                            <i class="fas fa-flag me-2"></i>
                                            {{ $country->country ?? 'Unknown' }}
                                        </td>
                                        <td>{{ number_format($country->count) }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" style="width: {{ ($country->count / $analytics['total_taps']) * 100 }}%">
                                                    {{ number_format(($country->count / $analytics['total_taps']) * 100, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspicious Activity -->
    @if($analytics['suspicious_taps']->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">{{ __('Suspicious Activity') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Business') }}</th>
                                    <th>{{ __('IP Address') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Device') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['suspicious_taps']->take(20) as $tap)
                                    <tr>
                                        <td>{{ $tap->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($tap->business)
                                                <strong>{{ $tap->business->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $tap->business->user->name ?? 'N/A' }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $tap->ip_address }}</td>
                                        <td>{{ $tap->tap_source }}</td>
                                        <td>{{ $tap->device_type }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ $tap->suspicious_reason }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="flagAsResolved({{ $tap->id }})">
                                                <i class="fas fa-check me-1"></i>{{ __('Resolve') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Export Analytics Data') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Export Format') }}</label>
                    <select class="form-select" id="export-format">
                        <option value="csv">CSV</option>
                        <option value="xlsx">Excel (XLSX)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Date Range') }}</label>
                    <select class="form-select" id="export-period">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="365">Last year</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="confirmExport()">{{ __('Export') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    const timelineData = @json($analytics['daily_taps']);
    
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: timelineData.map(item => item.date),
            datasets: [{
                label: 'Daily Taps',
                data: timelineData.map(item => item.count),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Sources Chart
    const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
    const sourcesData = @json($analytics['taps_by_source']);
    
    new Chart(sourcesCtx, {
        type: 'doughnut',
        data: {
            labels: sourcesData.map(item => item.tap_source),
            datasets: [{
                data: sourcesData.map(item => item.count),
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    const deviceData = @json($analytics['taps_by_device']);
    
    new Chart(deviceCtx, {
        type: 'bar',
        data: {
            labels: deviceData.map(item => item.device_type),
            datasets: [{
                label: 'Taps',
                data: deviceData.map(item => item.count),
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Filters
    document.getElementById('user-filter').addEventListener('change', updateFilters);
    document.getElementById('business-filter').addEventListener('change', updateFilters);
    document.getElementById('period-filter').addEventListener('change', updateFilters);
});

function updateFilters() {
    const userId = document.getElementById('user-filter').value;
    const businessId = document.getElementById('business-filter').value;
    const period = document.getElementById('period-filter').value;
    
    const params = new URLSearchParams();
    if (userId) params.append('user_id', userId);
    if (businessId) params.append('business_id', businessId);
    if (period) params.append('period', period);
    
    window.location.href = `{{ route('tap-analytics.admin') }}?${params.toString()}`;
}

function exportData() {
    const modal = new bootstrap.Modal(document.getElementById('exportModal'));
    modal.show();
}

function confirmExport() {
    const format = document.getElementById('export-format').value;
    const period = document.getElementById('export-period').value;
    const userId = document.getElementById('user-filter').value;
    const businessId = document.getElementById('business-filter').value;
    
    const params = new URLSearchParams();
    params.append('format', format);
    params.append('period', period);
    if (userId) params.append('user_id', userId);
    if (businessId) params.append('business_id', businessId);
    
    window.location.href = `{{ route('tap-analytics.export') }}?${params.toString()}`;
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
}

function flagAsResolved(tapId) {
    if (confirm('{{ __("Are you sure you want to mark this as resolved?") }}')) {
        // Add AJAX call to mark as resolved
        fetch(`/admin/tap-analytics/resolve/${tapId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background-color: #3B82F6;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .card-header .d-flex .d-flex {
        width: 100%;
    }
    
    .form-select {
        width: 100%;
    }
}
</style>
@endpush 