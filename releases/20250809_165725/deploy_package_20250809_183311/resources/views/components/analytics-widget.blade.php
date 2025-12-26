@props(['title', 'value', 'icon', 'trend', 'trendValue', 'color' => 'primary'])

<div class="analytics-widget">
    <div class="widget-icon" style="background: var(--{{ $color }}-gradient)">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="widget-content">
        <h3 class="widget-value">{{ number_format($value) }}</h3>
        <p class="widget-label">{{ $title }}</p>
        @if($trend && $trendValue)
        <div class="widget-trend {{ $trend }}">
            <i class="fas fa-arrow-{{ $trend === 'up' ? 'up' : 'down' }}"></i>
            <span>{{ $trendValue }}</span>
        </div>
        @endif
    </div>
</div>

<style>
.analytics-widget {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: transform 0.2s;
    cursor: pointer;
}

.analytics-widget:hover {
    transform: translateY(-2px);
}

.widget-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.widget-icon i {
    font-size: 20px;
    color: white;
}

.widget-content {
    flex: 1;
}

.widget-value {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin: 0 0 5px 0;
}

.widget-label {
    color: #666;
    margin: 0 0 5px 0;
    font-size: 14px;
}

.widget-trend {
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.widget-trend.up {
    color: #4CAF50;
}

.widget-trend.down {
    color: #f44336;
}

:root {
    --primary-gradient: linear-gradient(135deg, #4CAF50, #45a049);
    --success-gradient: linear-gradient(135deg, #2196F3, #1976D2);
    --warning-gradient: linear-gradient(135deg, #FF9800, #F57C00);
    --danger-gradient: linear-gradient(135deg, #f44336, #d32f2f);
    --info-gradient: linear-gradient(135deg, #00BCD4, #0097A7);
}
</style> 