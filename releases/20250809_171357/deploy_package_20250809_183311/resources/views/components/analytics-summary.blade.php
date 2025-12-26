@props(['analytics'])

<div class="analytics-summary">
    <div class="summary-header">
        <h3><i class="fas fa-chart-bar"></i> Quick Analytics</h3>
        <a href="{{ route('analytics.dashboard') }}" class="view-all-link">
            View Full Analytics <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-icon">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="summary-content">
                <div class="summary-value">{{ number_format($analytics['total_cards'] ?? 0) }}</div>
                <div class="summary-label">Total Cards</div>
            </div>
        </div>
        
        <div class="summary-item">
            <div class="summary-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="summary-content">
                <div class="summary-value">{{ number_format($analytics['total_appointments'] ?? 0) }}</div>
                <div class="summary-label">Appointments</div>
            </div>
        </div>
        
        <div class="summary-item">
            <div class="summary-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="summary-content">
                <div class="summary-value">{{ number_format($analytics['total_users'] ?? 0) }}</div>
                <div class="summary-label">Users</div>
            </div>
        </div>
        
        <div class="summary-item">
            <div class="summary-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="summary-content">
                <div class="summary-value">{{ number_format($analytics['active_cards'] ?? 0) }}</div>
                <div class="summary-label">Active Cards</div>
            </div>
        </div>
    </div>
    
    @if(isset($analytics['storage_analytics']))
    <div class="storage-summary">
        <div class="storage-header">
            <span>Storage Usage</span>
            <span>{{ $analytics['storage_analytics']['usage_percentage'] }}%</span>
        </div>
        <div class="storage-bar">
            <div class="storage-fill" style="width: {{ $analytics['storage_analytics']['usage_percentage'] }}%"></div>
        </div>
        <div class="storage-text">
            {{ $analytics['storage_analytics']['used_storage'] }}MB / {{ $analytics['storage_analytics']['total_storage'] }}MB
        </div>
    </div>
    @endif
</div>

<style>
.analytics-summary {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.summary-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.summary-header h3 i {
    margin-right: 8px;
    color: #4CAF50;
}

.view-all-link {
    color: #4CAF50;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}

.view-all-link:hover {
    text-decoration: underline;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-icon i {
    font-size: 16px;
    color: white;
}

.summary-content {
    flex: 1;
}

.summary-value {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.summary-label {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}

.storage-summary {
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.storage-header {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #333;
    margin-bottom: 8px;
}

.storage-bar {
    width: 100%;
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 5px;
}

.storage-fill {
    height: 100%;
    background: linear-gradient(90deg, #4CAF50, #45a049);
    transition: width 0.3s ease;
}

.storage-text {
    font-size: 12px;
    color: #666;
    text-align: center;
}

@media (max-width: 768px) {
    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .summary-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}
</style> 