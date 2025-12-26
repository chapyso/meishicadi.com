@extends('layouts.admin')

@section('page-title')
    {{ __('Tap Analytics') }}
@endsection

@section('title')
    {{ __('Tap Analytics') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Tap Analytics') }}</li>
@endsection

@section('action-btn')
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
        <div class="d-flex gap-2">
            <select class="form-select" id="business-selector">
                @foreach($userBusinesses as $userBusiness)
                    <option value="{{ $userBusiness->id }}" {{ $business->id == $userBusiness->id ? 'selected' : '' }}>
                        {{ $userBusiness->title }}
                    </option>
                @endforeach
            </select>
            <select class="form-select" id="period-selector">
                <option value="7" {{ $period == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $period == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $period == 90 ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $period == 365 ? 'selected' : '' }}>Last year</option>
            </select>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-3 col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Analytics Navigation') }}</h5>
                </div>
                <div class="card-body">
                    <div class="nav flex-column nav-pills" id="analytics-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                            <i class="ti ti-dashboard me-2"></i>{{ __('Overview') }}
                        </button>
                        <button class="nav-link" id="timeline-tab" data-bs-toggle="pill" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">
                            <i class="ti ti-timeline me-2"></i>{{ __('Timeline') }}
                        </button>
                        <button class="nav-link" id="sources-tab" data-bs-toggle="pill" data-bs-target="#sources" type="button" role="tab" aria-controls="sources" aria-selected="false">
                            <i class="ti ti-source me-2"></i>{{ __('Tap Sources') }}
                        </button>
                        <button class="nav-link" id="devices-tab" data-bs-toggle="pill" data-bs-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false">
                            <i class="ti ti-device-mobile me-2"></i>{{ __('Device Types') }}
                        </button>
                        <button class="nav-link" id="countries-tab" data-bs-toggle="pill" data-bs-target="#countries" type="button" role="tab" aria-controls="countries" aria-selected="false">
                            <i class="ti ti-world me-2"></i>{{ __('Countries') }}
                        </button>
                        <button class="nav-link" id="browsers-tab" data-bs-toggle="pill" data-bs-target="#browsers" type="button" role="tab" aria-controls="browsers" aria-selected="false">
                            <i class="ti ti-brand-chrome me-2"></i>{{ __('Browsers') }}
                        </button>
                        <button class="nav-link" id="suspicious-tab" data-bs-toggle="pill" data-bs-target="#suspicious" type="button" role="tab" aria-controls="suspicious" aria-selected="false">
                            <i class="ti ti-alert-triangle me-2"></i>{{ __('Suspicious Activity') }}
                        </button>
                        <hr class="my-3">
                        <button class="nav-link" id="export-tab" data-bs-toggle="pill" data-bs-target="#export" type="button" role="tab" aria-controls="export" aria-selected="false">
                            <i class="ti ti-download me-2"></i>{{ __('Export Data') }}
                        </button>
                        <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                            <i class="ti ti-settings me-2"></i>{{ __('Settings') }}
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Sidebar -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Quick Stats') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Today') }}</span>
                        <span class="fw-bold" id="today-taps">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('This Week') }}</span>
                        <span class="fw-bold" id="week-taps">0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('This Month') }}</span>
                        <span class="fw-bold" id="month-taps">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ __('Average/Day') }}</span>
                        <span class="fw-bold" id="avg-taps">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="tab-content" id="analytics-tabContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
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
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3 class="mb-0">{{ $analytics['taps_by_country']->count() }}</h3>
                                            <p class="mb-0">{{ __('Countries') }}</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-globe fa-2x"></i>
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

                    <!-- Overview Charts -->
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Tap Activity Timeline') }}</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="timelineChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Tap Sources') }}</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="sourcesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Tab -->
                <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Detailed Timeline Analysis') }}</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="detailedTimelineChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sources Tab -->
                <div class="tab-pane fade" id="sources" role="tabpanel" aria-labelledby="sources-tab">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Tap Sources Distribution') }}</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="detailedSourcesChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Source Performance') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Source') }}</th>
                                                    <th>{{ __('Taps') }}</th>
                                                    <th>{{ __('Percentage') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['taps_by_source'] as $source)
                                                    <tr>
                                                        <td>{{ $source->tap_source }}</td>
                                                        <td>{{ number_format($source->count) }}</td>
                                                        <td>{{ number_format(($source->count / $analytics['total_taps']) * 100, 1) }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Devices Tab -->
                <div class="tab-pane fade" id="devices" role="tabpanel" aria-labelledby="devices-tab">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Device Types') }}</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="detailedDeviceChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Device Statistics') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Device') }}</th>
                                                    <th>{{ __('Taps') }}</th>
                                                    <th>{{ __('Percentage') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['taps_by_device'] as $device)
                                                    <tr>
                                                        <td>{{ ucfirst($device->device_type) }}</td>
                                                        <td>{{ number_format($device->count) }}</td>
                                                        <td>{{ number_format(($device->count / $analytics['total_taps']) * 100, 1) }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Countries Tab -->
                <div class="tab-pane fade" id="countries" role="tabpanel" aria-labelledby="countries-tab">
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
                                        @foreach($analytics['taps_by_country']->take(20) as $country)
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

                <!-- Browsers Tab -->
                <div class="tab-pane fade" id="browsers" role="tabpanel" aria-labelledby="browsers-tab">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Browser Statistics') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Browser') }}</th>
                                                    <th>{{ __('Taps') }}</th>
                                                    <th>{{ __('Percentage') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['browser_stats'] as $browser)
                                                    <tr>
                                                        <td>
                                                            <i class="fab fa-{{ strtolower($browser->browser) }} me-2"></i>
                                                            {{ $browser->browser }}
                                                        </td>
                                                        <td>{{ number_format($browser->count) }}</td>
                                                        <td>{{ number_format(($browser->count / $analytics['total_taps']) * 100, 1) }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Operating Systems') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('OS') }}</th>
                                                    <th>{{ __('Taps') }}</th>
                                                    <th>{{ __('Percentage') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['os_stats'] as $os)
                                                    <tr>
                                                        <td>
                                                            <i class="fab fa-{{ strtolower($os->device_os) }} me-2"></i>
                                                            {{ $os->device_os }}
                                                        </td>
                                                        <td>{{ number_format($os->count) }}</td>
                                                        <td>{{ number_format(($os->count / $analytics['total_taps']) * 100, 1) }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suspicious Activity Tab -->
                <div class="tab-pane fade" id="suspicious" role="tabpanel" aria-labelledby="suspicious-tab">
                    @if($analytics['suspicious_taps']->count() > 0)
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
                                                <th>{{ __('IP Address') }}</th>
                                                <th>{{ __('Source') }}</th>
                                                <th>{{ __('Device') }}</th>
                                                <th>{{ __('Reason') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($analytics['suspicious_taps']->take(20) as $tap)
                                                <tr>
                                                    <td>{{ $tap->created_at->format('M d, Y H:i') }}</td>
                                                    <td>{{ $tap->ip_address }}</td>
                                                    <td>{{ $tap->tap_source }}</td>
                                                    <td>{{ $tap->device_type }}</td>
                                                    <td>
                                                        <span class="badge bg-danger">{{ $tap->suspicious_reason }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-check fa-3x text-success mb-3"></i>
                                <h5>{{ __('No Suspicious Activity Detected') }}</h5>
                                <p class="text-muted">{{ __('All tap activities appear to be legitimate.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Export Tab -->
                <div class="tab-pane fade" id="export" role="tabpanel" aria-labelledby="export-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Export Analytics Data') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-csv fa-3x text-primary mb-3"></i>
                                            <h6>{{ __('Export as CSV') }}</h6>
                                            <p class="text-muted">{{ __('Download all tap analytics data in CSV format') }}</p>
                                            <a href="{{ route('tap-analytics.export') }}?business_id={{ $business->id }}&period={{ $period }}&format=csv" class="btn btn-primary">
                                                <i class="fas fa-download me-2"></i>{{ __('Download CSV') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                                            <h6>{{ __('Export as Excel') }}</h6>
                                            <p class="text-muted">{{ __('Download all tap analytics data in Excel format') }}</p>
                                            <a href="{{ route('tap-analytics.export') }}?business_id={{ $business->id }}&period={{ $period }}&format=xlsx" class="btn btn-success">
                                                <i class="fas fa-download me-2"></i>{{ __('Download Excel') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Analytics Settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{ __('Notification Settings') }}</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email-reports">
                                        <label class="form-check-label" for="email-reports">
                                            {{ __('Email weekly reports') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="suspicious-alerts">
                                        <label class="form-check-label" for="suspicious-alerts">
                                            {{ __('Alert on suspicious activity') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __('Display Settings') }}</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="real-time-updates" checked>
                                        <label class="form-check-label" for="real-time-updates">
                                            {{ __('Real-time updates') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="auto-refresh" checked>
                                        <label class="form-check-label" for="auto-refresh">
                                            {{ __('Auto-refresh data') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all charts
    initializeCharts();
    
    // Load quick stats
    loadQuickStats();
    
    // Business selector
    document.getElementById('business-selector').addEventListener('change', function() {
        const businessId = this.value;
        const period = document.getElementById('period-selector').value;
        window.location.href = `{{ route('tap-analytics.user') }}?business_id=${businessId}&period=${period}`;
    });

    // Period selector
    document.getElementById('period-selector').addEventListener('change', function() {
        const businessId = document.getElementById('business-selector').value;
        const period = this.value;
        window.location.href = `{{ route('tap-analytics.user') }}?business_id=${businessId}&period=${period}`;
    });

    function initializeCharts() {
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
        const deviceCtx = document.getElementById('detailedDeviceChart').getContext('2d');
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
    }

    function loadQuickStats() {
        // Calculate quick stats
        const totalTaps = {{ $analytics['total_taps'] }};
        const period = {{ $period }};
        
        // Simple calculations for demo - in real app, you'd fetch this data
        document.getElementById('today-taps').textContent = Math.floor(totalTaps / period);
        document.getElementById('week-taps').textContent = Math.floor(totalTaps / (period / 7));
        document.getElementById('month-taps').textContent = Math.floor(totalTaps / (period / 30));
        document.getElementById('avg-taps').textContent = Math.floor(totalTaps / period);
    }
});
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

.nav-pills .nav-link {
    color: #6c757d;
    border-radius: 0.375rem;
    margin-bottom: 0.25rem;
}

.nav-pills .nav-link.active {
    background-color: #3B82F6;
    color: white;
}

.nav-pills .nav-link:hover {
    background-color: #e9ecef;
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