@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="analytics-dashboard">
    <!-- Header Section -->
    <div class="analytics-header">
        <div class="header-content">
            <h1 class="analytics-title">
                <i class="fas fa-chart-line"></i>
                Analytics Dashboard
            </h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="refreshAnalytics()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-success" onclick="exportAnalytics()">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="date-range-picker">
                    <select id="dateRange" onchange="updateDateRange()">
                        <option value="7">Last 7 Days</option>
                        <option value="15" selected>Last 15 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="metric-content">
                <h3 class="metric-value" id="totalCards">{{ number_format($analytics['total_cards']) }}</h3>
                <p class="metric-label">Total Cards</p>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="metric-content">
                <h3 class="metric-value" id="totalAppointments">{{ number_format($analytics['total_appointments']) }}</h3>
                <p class="metric-label">Total Appointments</p>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <h3 class="metric-value" id="totalUsers">{{ number_format($analytics['total_users']) }}</h3>
                <p class="metric-label">Total Users</p>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15%</span>
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="metric-content">
                <h3 class="metric-value" id="activeCards">{{ number_format($analytics['active_cards']) }}</h3>
                <p class="metric-label">Active Cards</p>
                <div class="metric-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Card Views Timeline -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Card Views Timeline</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleChart('cardViews')">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="cardViewsChart"></canvas>
            </div>
        </div>

        <!-- Appointments Timeline -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Appointments Timeline</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleChart('appointments')">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="appointmentsChart"></canvas>
            </div>
        </div>

        <!-- Browser Usage -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Browser Usage</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleChart('browserUsage')">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="browserUsageChart"></canvas>
            </div>
        </div>

        <!-- Device Usage -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Device Usage</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="toggleChart('deviceUsage')">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="deviceUsageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics Section -->
    <div class="detailed-analytics">
        <!-- Top Performing Cards -->
        <div class="analytics-section">
            <div class="section-header">
                <h3>Top Performing Cards</h3>
                <button class="btn btn-sm btn-outline-secondary" onclick="viewAllCards()">
                    View All
                </button>
            </div>
            <div class="table-container">
                <table class="analytics-table">
                    <thead>
                        <tr>
                            <th>Card Name</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="topCardsTable">
                        @foreach($analytics['card_analytics']['top_cards'] as $card)
                        <tr>
                            <td>{{ $card->business_name }}</td>
                            <td>{{ number_format($card->views) }}</td>
                            <td>{{ \Carbon\Carbon::parse($card->created_at)->format('M d, Y') }}</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewCard({{ $card->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Storage Status -->
        <div class="analytics-section">
            <div class="section-header">
                <h3>Storage Status</h3>
                <span class="storage-info">{{ $analytics['storage_analytics']['used_storage'] }}MB / {{ $analytics['storage_analytics']['total_storage'] }}MB</span>
            </div>
            <div class="storage-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $analytics['storage_analytics']['usage_percentage'] }}%"></div>
                </div>
                <p class="storage-text">{{ $analytics['storage_analytics']['usage_percentage'] }}% of storage used</p>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="analytics-section">
            <div class="section-header">
                <h3>Performance Metrics</h3>
            </div>
            <div class="performance-grid">
                <div class="performance-item">
                    <div class="performance-label">Average Response Time</div>
                    <div class="performance-value">{{ $analytics['performance_analytics']['avg_response_time'] }}ms</div>
                </div>
                <div class="performance-item">
                    <div class="performance-label">Cache Hit Rate</div>
                    <div class="performance-value">{{ $analytics['performance_analytics']['cache_hit_rate'] }}%</div>
                </div>
                <div class="performance-item">
                    <div class="performance-label">Memory Usage</div>
                    <div class="performance-value">{{ number_format($analytics['performance_analytics']['memory_usage'] / 1024 / 1024, 2) }}MB</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Analytics -->
    <div class="realtime-analytics">
        <div class="section-header">
            <h3>Real-time Analytics</h3>
            <div class="realtime-indicator">
                <span class="pulse"></span>
                Live
            </div>
        </div>
        <div class="realtime-grid">
            <div class="realtime-item">
                <div class="realtime-label">Active Users</div>
                <div class="realtime-value" id="activeUsers">--</div>
            </div>
            <div class="realtime-item">
                <div class="realtime-label">Current Requests</div>
                <div class="realtime-value" id="currentRequests">--</div>
            </div>
            <div class="realtime-item">
                <div class="realtime-label">Server Load</div>
                <div class="realtime-value" id="serverLoad">--</div>
            </div>
            <div class="realtime-item">
                <div class="realtime-label">Response Time</div>
                <div class="realtime-value" id="responseTime">--</div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Global variables
let charts = {};
let realtimeInterval;

// Initialize analytics dashboard
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startRealtimeUpdates();
    loadAnalyticsData();
});

// Initialize all charts
function initializeCharts() {
    // Card Views Chart
    const cardViewsCtx = document.getElementById('cardViewsChart').getContext('2d');
    charts.cardViews = new Chart(cardViewsCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Card Views',
                data: [],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4
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
                    beginAtZero: true
                }
            }
        }
    });

    // Appointments Chart
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    charts.appointments = new Chart(appointmentsCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Appointments',
                data: [],
                backgroundColor: '#2196F3',
                borderColor: '#1976D2',
                borderWidth: 1
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
                    beginAtZero: true
                }
            }
        }
    });

    // Browser Usage Chart
    const browserCtx = document.getElementById('browserUsageChart').getContext('2d');
    charts.browserUsage = new Chart(browserCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chrome', 'Safari', 'Firefox', 'Edge', 'Others'],
            datasets: [{
                data: [45, 25, 15, 10, 5],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
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

    // Device Usage Chart
    const deviceCtx = document.getElementById('deviceUsageChart').getContext('2d');
    charts.deviceUsage = new Chart(deviceCtx, {
        type: 'pie',
        data: {
            labels: ['Desktop', 'Mobile', 'Tablet'],
            datasets: [{
                data: [60, 35, 5],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56'
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
}

// Load analytics data
function loadAnalyticsData() {
    fetch('/analytics/data')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateCharts(data.data);
                updateMetrics(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading analytics data:', error);
        });
}

// Update charts with new data
function updateCharts(data) {
    // Update card views chart
    if (data.card_analytics && data.card_analytics.views_timeline) {
        const labels = data.card_analytics.views_timeline.map(item => item.date);
        const values = data.card_analytics.views_timeline.map(item => item.count);
        
        charts.cardViews.data.labels = labels;
        charts.cardViews.data.datasets[0].data = values;
        charts.cardViews.update();
    }

    // Update appointments chart
    if (data.appointment_analytics && data.appointment_analytics.timeline) {
        const labels = data.appointment_analytics.timeline.map(item => item.date);
        const values = data.appointment_analytics.timeline.map(item => item.count);
        
        charts.appointments.data.labels = labels;
        charts.appointments.data.datasets[0].data = values;
        charts.appointments.update();
    }
}

// Update metrics
function updateMetrics(data) {
    document.getElementById('totalCards').textContent = number_format(data.total_cards);
    document.getElementById('totalAppointments').textContent = number_format(data.total_appointments);
    document.getElementById('totalUsers').textContent = number_format(data.total_users);
    document.getElementById('activeCards').textContent = number_format(data.active_cards);
}

// Start real-time updates
function startRealtimeUpdates() {
    updateRealtimeData();
    realtimeInterval = setInterval(updateRealtimeData, 5000); // Update every 5 seconds
}

// Update real-time data
function updateRealtimeData() {
    fetch('/analytics/realtime')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('activeUsers').textContent = data.data.active_users;
                document.getElementById('currentRequests').textContent = data.data.current_requests;
                document.getElementById('serverLoad').textContent = data.data.server_load + '%';
                document.getElementById('responseTime').textContent = data.data.response_time + 'ms';
            }
        })
        .catch(error => {
            console.error('Error updating real-time data:', error);
        });
}

// Refresh analytics
function refreshAnalytics() {
    loadAnalyticsData();
    updateRealtimeData();
}

// Export analytics
function exportAnalytics() {
    const format = prompt('Enter export format (json/csv):', 'json');
    if (format) {
        window.open(`/analytics/export?format=${format}`, '_blank');
    }
}

// Update date range
function updateDateRange() {
    const range = document.getElementById('dateRange').value;
    loadAnalyticsData();
}

// Toggle chart fullscreen
function toggleChart(chartName) {
    const chartContainer = document.getElementById(chartName + 'Chart').parentElement;
    chartContainer.classList.toggle('fullscreen');
}

// View all cards
function viewAllCards() {
    window.location.href = '/business';
}

// View specific card
function viewCard(cardId) {
    window.open(`/business/${cardId}/edit`, '_blank');
}

// Number formatting helper
function number_format(number) {
    return new Intl.NumberFormat().format(number);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (realtimeInterval) {
        clearInterval(realtimeInterval);
    }
});
</script>

<style>
.analytics-dashboard {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.analytics-header {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.analytics-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.analytics-title i {
    margin-right: 10px;
    color: #4CAF50;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.date-range-picker select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: white;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: transform 0.2s;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.metric-icon i {
    font-size: 24px;
    color: white;
}

.metric-content {
    flex: 1;
}

.metric-value {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 0 0 5px 0;
}

.metric-label {
    color: #666;
    margin: 0 0 5px 0;
}

.metric-trend {
    font-size: 12px;
    font-weight: 600;
}

.metric-trend.positive {
    color: #4CAF50;
}

.metric-trend.negative {
    color: #f44336;
}

.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.chart-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.chart-header h3 {
    margin: 0;
    color: #333;
    font-size: 18px;
}

.chart-container {
    height: 300px;
    position: relative;
}

.chart-container.fullscreen {
    height: 500px;
}

.detailed-analytics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.analytics-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.section-header h3 {
    margin: 0;
    color: #333;
    font-size: 18px;
}

.analytics-table {
    width: 100%;
    border-collapse: collapse;
}

.analytics-table th,
.analytics-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.analytics-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: #e8f5e8;
    color: #4CAF50;
}

.storage-progress {
    margin-top: 15px;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4CAF50, #45a049);
    transition: width 0.3s ease;
}

.storage-text {
    margin: 8px 0 0 0;
    color: #666;
    font-size: 14px;
}

.performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.performance-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.performance-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.performance-value {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.realtime-analytics {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.realtime-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4CAF50;
    font-weight: 600;
}

.pulse {
    width: 8px;
    height: 8px;
    background: #4CAF50;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.realtime-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 15px;
}

.realtime-item {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.realtime-label {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.realtime-value {
    font-size: 24px;
    font-weight: 700;
    color: #333;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-primary {
    background: #4CAF50;
    color: white;
}

.btn-primary:hover {
    background: #45a049;
}

.btn-success {
    background: #2196F3;
    color: white;
}

.btn-success:hover {
    background: #1976D2;
}

.btn-outline-primary {
    background: transparent;
    color: #4CAF50;
    border: 1px solid #4CAF50;
}

.btn-outline-primary:hover {
    background: #4CAF50;
    color: white;
}

.btn-outline-secondary {
    background: transparent;
    color: #666;
    border: 1px solid #ddd;
}

.btn-outline-secondary:hover {
    background: #666;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

@media (max-width: 768px) {
    .analytics-dashboard {
        padding: 10px;
    }
    
    .header-content {
        flex-direction: column;
        gap: 15px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .detailed-analytics {
        grid-template-columns: 1fr;
    }
    
    .realtime-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection 