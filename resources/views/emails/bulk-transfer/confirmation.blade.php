<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your files have been successfully sent</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .title {
            font-size: 28px;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .success-icon {
            font-size: 48px;
            color: #48bb78;
            margin-bottom: 20px;
        }
        .summary-box {
            background: #f0fff4;
            border: 1px solid #9ae6b4;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .files-section {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .recipient-info {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .warning {
            background: #fef5e7;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #c05621;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 14px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 14px;
            color: #718096;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Meishicadi</div>
            <div class="success-icon">✅</div>
            <h1 class="title">Your files have been successfully sent!</h1>
        </div>

        <div class="summary-box">
            <h3>📤 Transfer Summary</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $fileCount }}</div>
                    <div class="stat-label">Files Sent</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSize }}</div>
                    <div class="stat-label">Total Size</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $expirationHours }}h</div>
                    <div class="stat-label">Expires In</div>
                </div>
            </div>
        </div>

        <div class="recipient-info">
            <strong>Recipient:</strong> {{ $recipientEmail }}
            <br>
            <strong>Sent:</strong> {{ $transferDate }}
        </div>

        <div class="files-section">
            <h3>📁 Files Sent</h3>
            @foreach($files as $file)
            <div class="file-item">
                <div>
                    <strong>{{ $file['name'] }}</strong><br>
                    <small>{{ $file['size'] }}</small>
                </div>
            </div>
            @endforeach
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong> The download link will expire in <strong>{{ $expirationHours }} hours</strong> 
            ({{ $expirationDate }}). The recipient should download the files before they are automatically deleted.
        </div>

        <div class="footer">
            <p>This email was sent from <strong>no-reply@meishicadi.com</strong></p>
            <p>You can track your transfers in your Meishicadi dashboard.</p>
            <p>&copy; {{ date('Y') }} Meishicadi. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 