<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Status Update</title>
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
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .status-confirmed {
            background-color: #28a745;
            color: white;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
        .appointment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $business->title }}</h1>
        <p>Appointment Status Update</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $appointment->name }},</h2>
        
        <p>Your appointment status has been updated:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span class="status-badge status-{{ $status }}">
                {{ ucfirst($status) }}
            </span>
        </div>
        
        <div class="appointment-details">
            <h3>Appointment Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span class="detail-value">{{ $appointment->time }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $appointment->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $appointment->email }}</span>
            </div>
            
            @if($appointment->phone)
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $appointment->phone }}</span>
            </div>
            @endif
            
            @if($appointment->note)
            <div class="detail-row">
                <span class="detail-label">Your Note:</span>
                <span class="detail-value">{{ $appointment->note }}</span>
            </div>
            @endif
            
            @if($appointment->admin_note)
            <div class="detail-row">
                <span class="detail-label">Admin Note:</span>
                <span class="detail-value">{{ $appointment->admin_note }}</span>
            </div>
            @endif
            
            @if($status === 'cancelled' && $appointment->cancellation_reason)
            <div class="detail-row">
                <span class="detail-label">Cancellation Reason:</span>
                <span class="detail-value">{{ $appointment->cancellation_reason }}</span>
            </div>
            @endif
        </div>
        
        @if($status === 'confirmed')
        <div class="contact-info">
            <h4>📅 Appointment Confirmed!</h4>
            <p>Your appointment has been confirmed. Please arrive 5 minutes before your scheduled time.</p>
        </div>
        @endif
        
        @if($status === 'cancelled')
        <div class="contact-info">
            <h4>❌ Appointment Cancelled</h4>
            <p>Your appointment has been cancelled. If you need to reschedule, please contact us.</p>
        </div>
        @endif
        
        <div class="contact-info">
            <h4>📞 Contact Information</h4>
            <p><strong>Business:</strong> {{ $business->title }}</p>
            @if($business->phone)
            <p><strong>Phone:</strong> {{ $business->phone }}</p>
            @endif
            @if($business->email)
            <p><strong>Email:</strong> {{ $business->email }}</p>
            @endif
            @if($business->address)
            <p><strong>Address:</strong> {{ $business->address }}</p>
            @endif
        </div>
        
        <div class="footer">
            <p>Thank you for choosing {{ $business->title }}!</p>
            <p>This email was sent from {{ $business->gmail_email ?? config('app.name') }}</p>
        </div>
    </div>
</body>
</html> 