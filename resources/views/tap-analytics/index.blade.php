@extends('layouts.admin')

@section('page-title')
    {{ __('Global Tap Analytics') }}
@endsection

@section('title')
    {{ __('Platform Analytics Overview') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Global Tap Analytics') }}</li>
@endsection

@section('action-btn')
    <div class="d-flex align-items-center justify-content-end gap-2">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary" id="export-btn">
                <i class="ti ti-download me-1"></i>{{ __('Export Data') }}
            </button>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Overview Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-primary d-flex align-items-center justify-content-center">
                        <i class="ti ti-building text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">{{ number_format($totalBusinesses) }}</h5>
                        <p class="text-muted mb-0">{{ __('Total Businesses') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-success d-flex align-items-center justify-content-center">
                        <i class="ti ti-touch text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">{{ number_format($totalTaps) }}</h5>
                        <p class="text-muted mb-0">{{ __('Total Taps') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-info d-flex align-items-center justify-content-center">
                        <i class="ti ti-users text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">{{ number_format($totalUsers) }}</h5>
                        <p class="text-muted mb-0">{{ __('Total Users') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-warning d-flex align-items-center justify-content-center">
                        <i class="ti ti-chart-line text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1">{{ number_format($averageTapsPerBusiness, 1) }}</h5>
                        <p class="text-muted mb-0">{{ __('Avg Taps/Business') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Time Analytics Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Tap Activity Over Time') }}</h5>
                    <select class="form-select form-select-sm" id="period-filter" style="width: auto;">
                        <option value="7days">{{ __('Last 7 Days') }}</option>
                        <option value="30days" selected>{{ __('Last 30 Days') }}</option>
                        <option value="90days">{{ __('Last 90 Days') }}</option>
                        <option value="1year">{{ __('Last Year') }}</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="time-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Tap Types Chart -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Tap Types Distribution') }}</h5>
            </div>
            <div class="card-body">
                <div id="tap-types-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Performing Businesses -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Top Performing Businesses') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Business') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Taps') }}</th>
                                <th>{{ __('Created') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topBusinesses as $business)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-2">
                                            <i class="ti ti-building text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $business->title }}</h6>
                                            <small class="text-muted">{{ $business->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($business->creator)
                                        <span class="text-muted">{{ $business->creator->name }}</span>
                                    @else
                                        <span class="text-muted">{{ __('Unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ number_format($business->tap_count) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $business->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Geographic Distribution -->
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
                            @foreach($topCountries as $country)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-2">
                                            <i class="ti ti-map-pin text-info"></i>
                                        </div>
                                        <span>{{ $country->country ?: __('Unknown') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ number_format($country->count) }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ ($country->count / $totalTaps) * 100 }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format(($country->count / $totalTaps) * 100, 1) }}%</small>
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

<div class="row">
    <!-- Recent Activity -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Recent Tap Activity') }}</h5>
                    <button class="btn btn-sm btn-outline-primary" id="refresh-activity">
                        <i class="ti ti-refresh me-1"></i>{{ __('Refresh') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Time') }}</th>
                                <th>{{ __('Business') }}</th>
                                <th>{{ __('Tap Type') }}</th>
                                <th>{{ __('Location') }}</th>
                                <th>{{ __('Device') }}</th>
                                <th>{{ __('Browser') }}</th>
                            </tr>
                        </thead>
                        <tbody id="recent-activity-table">
                            @foreach($recentTaps as $tap)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $tap->created_at->format('M d, H:i') }}</small>
                                </td>
                                <td>
                                    @if($tap->business)
                                        <a href="{{ route('business.analytics', $tap->business->id) }}" class="text-primary">
                                            {{ $tap->business->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ __('Deleted Business') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $tap->tap_type == 'qr_scan' ? 'success' : ($tap->tap_type == 'share_link' ? 'info' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $tap->tap_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        @if($tap->country)
                                            {{ $tap->country }}{{ $tap->city ? ', ' . $tap->city : '' }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ ucfirst($tap->device_type ?: 'Unknown') }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $tap->browser ?: 'Unknown' }}</small>
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
@endsection

@push('css-page')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    .progress {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('script-page')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
$(document).ready(function() {
    let timeChart, tapTypesChart;
    let currentPeriod = '30days';

    // Initialize charts
    initializeCharts();

    // Event listeners
    $('#period-filter').change(function() {
        currentPeriod = $(this).val();
        loadTimeAnalytics();
    });

    $('#refresh-activity').click(function() {
        loadRecentActivity();
    });

    $('#export-btn').click(function() {
        exportData();
    });

    function initializeCharts() {
        // Time Chart
        timeChart = new ApexCharts(document.querySelector("#time-chart"), {
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Taps',
                data: @json($timeAnalytics->pluck('count'))
            }],
            xaxis: {
                categories: @json($timeAnalytics->pluck('date')),
                labels: {
                    rotate: -45
                }
            },
            colors: ['#3b82f6'],
            stroke: {
                curve: 'smooth'
            },
            grid: {
                borderColor: '#e5e7eb'
            }
        });
        timeChart.render();

        // Tap Types Chart
        const tapTypesData = @json($tapTypes->pluck('count'));
        const tapTypesLabels = @json($tapTypes->pluck('tap_type'));
        
        tapTypesChart = new ApexCharts(document.querySelector("#tap-types-chart"), {
            chart: {
                type: 'donut',
                height: 300
            },
            series: tapTypesData,
            labels: tapTypesLabels.map(label => label.replace('_', ' ').toUpperCase()),
            colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
            legend: {
                position: 'bottom'
            }
        });
        tapTypesChart.render();
    }

    function loadTimeAnalytics() {
        $.ajax({
            url: '{{ route("tap-analytics.time-analytics") }}',
            method: 'GET',
            data: { period: currentPeriod },
            success: function(response) {
                if (response.success) {
                    updateTimeChart(response.time_analytics);
                }
            },
            error: function(xhr) {
                console.error('Error loading time analytics:', xhr);
            }
        });
    }

    function updateTimeChart(data) {
        const dates = data.map(item => item.date);
        const counts = data.map(item => item.count);
        
        timeChart.updateOptions({
            xaxis: {
                categories: dates
            }
        });
        
        timeChart.updateSeries([{
            name: 'Taps',
            data: counts
        }]);
    }

    function loadRecentActivity() {
        $.ajax({
            url: '{{ route("tap-analytics.recent-activity") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateRecentActivityTable(response.recent_activity.data);
                }
            },
            error: function(xhr) {
                console.error('Error loading recent activity:', xhr);
            }
        });
    }

    function updateRecentActivityTable(data) {
        const tbody = $('#recent-activity-table');
        tbody.empty();
        
        data.forEach(function(tap) {
            const row = `
                <tr>
                    <td><small class="text-muted">${new Date(tap.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</small></td>
                    <td>
                        ${tap.business ? `<a href="/business/analytics/${tap.business.id}" class="text-primary">${tap.business.title}</a>` : '<span class="text-muted">Deleted Business</span>'}
                    </td>
                    <td>
                        <span class="badge bg-${tap.tap_type === 'qr_scan' ? 'success' : (tap.tap_type === 'share_link' ? 'info' : 'secondary')}">
                            ${tap.tap_type.replace('_', ' ').toUpperCase()}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">
                            ${tap.country ? `${tap.country}${tap.city ? ', ' + tap.city : ''}` : 'Unknown'}
                        </small>
                    </td>
                    <td>
                        <small class="text-muted">${tap.device_type ? tap.device_type.toUpperCase() : 'Unknown'}</small>
                    </td>
                    <td>
                        <small class="text-muted">${tap.browser || 'Unknown'}</small>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function exportData() {
        const period = $('#period-filter').val();
        window.location.href = `{{ route('tap-analytics.export') }}?period=${period}`;
    }

    // Auto-refresh every 30 seconds
    setInterval(function() {
        loadRecentActivity();
    }, 30000);
});
</script>
@endpush 