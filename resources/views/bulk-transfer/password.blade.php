<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Password Protected File') }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .password-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        
        .file-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .file-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            text-align: left;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 8px;
            padding: 12px;
            margin: 16px 0;
            display: none;
        }
    </style>
</head>
<body>
    <div class="password-card">
        <div class="file-icon">
            <i class="ti ti-lock"></i>
        </div>
        
        <h4 class="mb-3">{{ __('Password Protected File') }}</h4>
        <p class="text-muted mb-4">{{ __('This file is protected with a password. Please enter the password to download.') }}</p>
        
        <div class="file-info">
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-file me-2 text-primary"></i>
                <strong>{{ $transfer->original_name }}</strong>
            </div>
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-database me-2 text-muted"></i>
                <small class="text-muted">{{ $transfer->file_size_formatted }}</small>
            </div>
            <div class="d-flex align-items-center">
                <i class="ti ti-clock me-2 text-muted"></i>
                <small class="text-muted">{{ __('Expires') }} {{ $transfer->expires_at->format('M d, Y H:i') }}</small>
            </div>
        </div>
        
        <div class="error-message" id="errorMessage"></div>
        
        <form id="passwordForm">
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="{{ __('Enter password') }}" required autofocus>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-download me-2"></i>{{ __('Download File') }}
            </button>
        </form>
        
        <div class="mt-4">
            <small class="text-muted">
                {{ __('Don\'t have the password?') }} <br>
                {{ __('Contact the file owner to get the password.') }}
            </small>
        </div>
    </div>

    <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#passwordForm').submit(function(e) {
                e.preventDefault();
                
                const password = $('#password').val();
                const downloadUrl = '{{ $transfer->download_url }}' + '?password=' + encodeURIComponent(password);
                
                // Try to download the file
                $.ajax({
                    url: downloadUrl,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        // Check if we got an error response
                        if (xhr.getResponseHeader('content-type') && 
                            xhr.getResponseHeader('content-type').includes('application/json')) {
                            // This is an error response
                            const reader = new FileReader();
                            reader.onload = function() {
                                const error = JSON.parse(reader.result);
                                showError(error.message || 'Invalid password');
                            };
                            reader.readAsText(data);
                        } else {
                            // Success - trigger download
                            const blob = new Blob([data]);
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = '{{ $transfer->original_name }}';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            showError('Invalid password');
                        } else if (xhr.status === 410) {
                            showError('File has expired');
                        } else {
                            showError('Download failed. Please try again.');
                        }
                    }
                });
            });
            
            function showError(message) {
                $('#errorMessage').text(message).show();
                $('#password').focus();
            }
            
            // Hide error when user starts typing
            $('#password').on('input', function() {
                $('#errorMessage').hide();
            });
        });
    </script>
</body>
</html> 