<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Signup Request - MeishiCadi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
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
        .info-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            display: inline-block;
            width: 150px;
        }
        .value {
            color: #333;
        }
        .message-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎯 New Signup Request</h1>
        <p>Digital Business Card Interest</p>
    </div>

    <div class="content">
        <div class="highlight">
            <strong>📅 Submitted:</strong> {{ $submitted_at ?? 'N/A' }}
        </div>

        <div class="info-section">
            <h3>👤 Contact Information</h3>
            <p><span class="label">Name:</span> <span class="value">{{ $name ?? 'N/A' }}</span></p>
            <p><span class="label">Email:</span> <span class="value">{{ $email ?? 'N/A' }}</span></p>
            <p><span class="label">Phone:</span> <span class="value">{{ $phone ?? 'N/A' }}</span></p>
        </div>

        <div class="info-section">
            <h3>🏢 Business Information</h3>
            <p><span class="label">Company:</span> <span class="value">{{ $company ?? 'N/A' }}</span></p>
            @if(!empty($industry))
                <p><span class="label">Industry:</span> <span class="value">{{ $industry }}</span></p>
            @endif
            <p><span class="label">Cards Required:</span> <span class="value"><strong>{{ $cards_required ?? 'N/A' }}</strong></span></p>
        </div>

        @if(!empty($message))
            <div class="info-section">
                <h3>💬 Additional Information</h3>
                <div class="message-box">
                    {{ $message }}
                </div>
            </div>
        @endif

        <div class="highlight">
            <strong>📊 Summary:</strong><br>
            • {{ $name ?? 'N/A' }} from {{ $company ?? 'N/A' }} is interested in {{ $cards_required ?? 'N/A' }} digital business cards<br>
            • Contact: {{ $email ?? 'N/A' }} | {{ $phone ?? 'N/A' }}<br>
            • Industry: {{ !empty($industry) ? $industry : 'Not specified' }}
        </div>

        <div class="footer">
            <p>This email was sent from the MeishiCadi signup form.</p>
            <p>Please respond to this lead within 24 hours for best results.</p>
        </div>
    </div>
</body>
</html>
