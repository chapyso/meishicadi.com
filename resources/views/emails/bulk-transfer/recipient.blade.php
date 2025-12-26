<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files Received via Meishicadi Bulk Transfer</title>
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
        .sender-info {
            background: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
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
        .download-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: all 0.3s ease;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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
        .message-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Meishicadi</div>
            <h1 class="title">You've received files via Meishicadi Bulk Transfer</h1>
        </div>

        <div class="sender-info">
            <strong>From:</strong> {{ $senderName }} ({{ $senderEmail }})
            <br>
            <strong>Sent:</strong> {{ $transferDate }}
        </div>

        @if($message)
        <div class="message-box">
            <strong>Message from sender:</strong><br>
            {{ $message }}
        </div>
        @endif

        <div class="files-section">
            <h3>📁 Files Received ({{ count($files) }})</h3>
            @foreach($files as $file)
            <div class="file-item">
                <div>
                    <strong>{{ $file['name'] }}</strong><br>
                    <small>{{ $file['size'] }}</small>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align: center;">
            <a href="{{ $downloadUrl }}" class="download-btn">
                📥 Download Files
            </a>
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong> These files will expire in <strong>{{ $expirationHours }} hours</strong> 
            ({{ $expirationDate }}). Please download them before they are automatically deleted.
        </div>

        <div class="footer">
            <p>This email was sent from <strong>no-reply@meishicadi.com</strong></p>
            <p>If you have any questions, please contact the sender directly.</p>
            <p>&copy; {{ date('Y') }} Meishicadi. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 