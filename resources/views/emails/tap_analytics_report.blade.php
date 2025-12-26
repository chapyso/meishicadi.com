<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($type) }} Tap Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-card h3 {
            margin: 0;
            font-size: 2em;
            color: #667eea;
        }
        .summary-card p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .section {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h3 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #666;
            font-size: 0.9em;
        }
        .chart-placeholder {
            background: #f8f9fa;
            border: 2px dashed #ddd;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ ucfirst($type) }} Tap Analytics Report</h1>
        <p>{{ $report['period']['start'] }} - {{ $report['period']['end'] }}</p>
    </div>

    <div class="content">
        <p>Hello {{ $admin->name }},</p>
        
        <p>Here's your {{ $type }} tap analytics report for the business card platform. This report covers all tap interactions across all business cards during the specified period.</p>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <h3>{{ number_format($report['summary']['total_taps']) }}</h3>
                <p>Total Taps</p>
            </div>
            <div class="summary-card">
                <h3>{{ number_format($report['summary']['unique_businesses']) }}</h3>
                <p>Active Businesses</p>
            </div>
            <div class="summary-card">
                <h3>{{ number_format($report['summary']['suspicious_taps']) }}</h3>
                <p>Suspicious Activity</p>
            </div>
        </div>

        <!-- Top Performing Cards -->
        <div class="section">
            <h3>🏆 Top Performing Cards</h3>
            @if($report['top_performing_cards']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Business</th>
                            <th>Owner</th>
                            <th>Card ID</th>
                            <th>Taps</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['top_performing_cards'] as $index => $card)
                            <tr>
                                <td>
                                    @if($index < 3)
                                        <span class="badge">🥇</span>
                                    @else
                                        <span class="badge">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td><strong>{{ $card->business_name }}</strong></td>
                                <td>{{ $card->user_name }}</td>
                                <td>{{ $card->card_id ?? 'Main Card' }}</td>
                                <td><strong>{{ number_format($card->tap_count) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tap activity recorded during this period.</p>
            @endif
        </div>

        <!-- Tap Sources -->
        <div class="section">
            <h3>📱 Tap Sources</h3>
            @if($report['taps_by_source']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Source</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['taps_by_source'] as $source)
                            <tr>
                                <td>
                                    @switch($source->tap_source)
                                        @case('QR')
                                            📱 QR Code
                                            @break
                                        @case('NFC')
                                            📲 NFC
                                            @break
                                        @case('Link')
                                            🔗 Direct Link
                                            @break
                                        @case('Direct')
                                            🌐 Direct Access
                                            @break
                                        @default
                                            {{ $source->tap_source }}
                                    @endswitch
                                </td>
                                <td>{{ number_format($source->count) }}</td>
                                <td>{{ number_format(($source->count / $report['summary']['total_taps']) * 100, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tap source data available.</p>
            @endif
        </div>

        <!-- Device Types -->
        <div class="section">
            <h3>💻 Device Types</h3>
            @if($report['taps_by_device']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Device Type</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['taps_by_device'] as $device)
                            <tr>
                                <td>
                                    @switch($device->device_type)
                                        @case('mobile')
                                            📱 Mobile
                                            @break
                                        @case('tablet')
                                            📱 Tablet
                                            @break
                                        @case('desktop')
                                            💻 Desktop
                                            @break
                                        @default
                                            {{ ucfirst($device->device_type) }}
                                    @endswitch
                                </td>
                                <td>{{ number_format($device->count) }}</td>
                                <td>{{ number_format(($device->count / $report['summary']['total_taps']) * 100, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No device data available.</p>
            @endif
        </div>

        <!-- Top Countries -->
        <div class="section">
            <h3>🌍 Top Countries</h3>
            @if($report['taps_by_country']->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Country</th>
                            <th>Taps</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report['taps_by_country'] as $country)
                            <tr>
                                <td>🏳️ {{ $country->country ?? 'Unknown' }}</td>
                                <td>{{ number_format($country->count) }}</td>
                                <td>{{ number_format(($country->count / $report['summary']['total_taps']) * 100, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No country data available.</p>
            @endif
        </div>

        <!-- Daily Activity Chart Placeholder -->
        <div class="section">
            <h3>📈 Daily Activity</h3>
            <div class="chart-placeholder">
                <p>📊 Daily tap activity chart would be displayed here</p>
                <p><small>Peak day: {{ $report['daily_taps']->sortByDesc('count')->first()->date ?? 'N/A' }}</small></p>
            </div>
        </div>

        <!-- Action Items -->
        <div class="section">
            <h3>🚨 Action Items</h3>
            @if($report['summary']['suspicious_taps'] > 0)
                <p><strong>⚠️ Suspicious Activity Detected:</strong> {{ $report['summary']['suspicious_taps'] }} suspicious taps were recorded during this period. Please review the admin dashboard for details.</p>
            @else
                <p>✅ No suspicious activity detected during this period.</p>
            @endif
            
            <p><strong>📊 Next Steps:</strong></p>
            <ul>
                <li>Review top performing cards for potential marketing opportunities</li>
                <li>Monitor suspicious activity patterns</li>
                <li>Consider optimizing for the most popular device types</li>
                <li>Analyze geographic trends for targeted marketing</li>
            </ul>
        </div>

        <div class="footer">
            <p>This report was automatically generated by the Tap Analytics system.</p>
            <p>To view detailed analytics, visit the <a href="{{ url('/admin/tap-analytics') }}">Admin Dashboard</a></p>
            <p>© {{ date('Y') }} Business Card Platform. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 