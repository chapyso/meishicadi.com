<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Expired - Bulk Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .expired-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .expired-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .expired-body {
            padding: 3rem 2rem;
        }
        .expired-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .info-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="expired-card">
                    <div class="expired-header">
                        <i class="bx bx-time-five expired-icon"></i>
                        <h2>Transfer Expired</h2>
                        <p class="mb-0">This file transfer is no longer available</p>
                    </div>
                    
                    <div class="expired-body">
                        <div class="text-center mb-4">
                            <h4 class="text-danger">Transfer Information</h4>
                        </div>
                        
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="info-item">
                                    <span><strong>Title:</strong></span>
                                    <span>{{ $transfer->title ?: 'Untitled Transfer' }}</span>
                                </div>
                                @if($transfer->description)
                                <div class="info-item">
                                    <span><strong>Description:</strong></span>
                                    <span>{{ $transfer->description }}</span>
                                </div>
                                @endif
                                <div class="info-item">
                                    <span><strong>Created:</strong></span>
                                    <span>{{ $transfer->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <div class="info-item">
                                    <span><strong>Expired:</strong></span>
                                    <span>{{ $transfer->expires_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <div class="info-item">
                                    <span><strong>Files:</strong></span>
                                    <span>{{ $transfer->uploaded_files }}/{{ $transfer->total_files }}</span>
                                </div>
                                <div class="info-item">
                                    <span><strong>Total Size:</strong></span>
                                    <span>{{ $transfer->formatted_total_size }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted">
                                This transfer has expired and is no longer accessible. 
                                Please contact the sender to request a new transfer.
                            </p>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-2"></i>
                                Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 