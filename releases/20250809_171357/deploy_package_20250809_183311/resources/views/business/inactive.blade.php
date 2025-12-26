<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Business Unavailable') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            animation: slideIn 0.6s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .error-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #dc3545;
        }
        
        .error-details h3 {
            color: #dc3545;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        
        .error-details p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .back-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }
        
        .contact-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .contact-info p {
            color: #888;
            font-size: 0.9rem;
        }
        
        .contact-info a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-info a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🚫</div>
        <h1 class="error-title">{{ __('Business Temporarily Unavailable') }}</h1>
        <p class="error-message">
            {{ __('This business card is currently not available for viewing.') }}
        </p>
        
        <div class="error-details">
            <h3>{{ __('Why is this happening?') }}</h3>
            <p>{{ __('The business owner has temporarily deactivated this card. This could be due to maintenance, updates, or other administrative reasons.') }}</p>
        </div>
        
        <a href="javascript:history.back()" class="back-button">
            <i class="ti ti-arrow-left me-2"></i>{{ __('Go Back') }}
        </a>
        
        <div class="contact-info">
            <p>{{ __('If you believe this is an error, please contact the business owner directly.') }}</p>
            @if(isset($business) && $business->email)
                <p>{{ __('Email') }}: <a href="mailto:{{ $business->email }}">{{ $business->email }}</a></p>
            @endif
        </div>
    </div>
</body>
</html> 