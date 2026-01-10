<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $transfer->title ?: 'File Transfer' }} - Bulk Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .transfer-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .transfer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .transfer-body {
            padding: 2rem;
        }
        .file-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .file-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-uploading {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-pending {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .file-icon {
            font-size: 2rem;
            color: #667eea;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .expired-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($transfer->is_expired)
                    <div class="transfer-card">
                        <div class="transfer-header">
                            <i class="bx bx-time-five" style="font-size: 3rem;"></i>
                            <h2 class="mt-3">Transfer Expired</h2>
                        </div>
                        <div class="transfer-body">
                            <div class="expired-message">
                                <h4>This file transfer has expired</h4>
                                <p class="mb-0">The transfer was created on {{ $transfer->created_at->format('M d, Y \a\t g:i A') }} and expired on {{ $transfer->expires_at->format('M d, Y \a\t g:i A') }}.</p>
                                <p class="mt-3 mb-0">Please contact the sender to request a new transfer.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="transfer-card">
                        <div class="transfer-header">
                            <i class="bx bx-share-alt" style="font-size: 3rem;"></i>
                            <h2 class="mt-3">{{ $transfer->title ?: 'File Transfer' }}</h2>
                            @if($transfer->description)
                                <p class="mb-0 mt-2">{{ $transfer->description }}</p>
                            @endif
                        </div>
                        
                        <div class="transfer-body">
                            <!-- Transfer Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title text-muted">Transfer Information</h6>
                                            <div class="info-item">
                                                <span>Status:</span>
                                                <span class="status-badge status-{{ $transfer->status }}">
                                                    {{ ucfirst($transfer->status) }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span>Files:</span>
                                                <span>{{ $transfer->uploaded_files }}/{{ $transfer->total_files }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span>Total Size:</span>
                                                <span>{{ $transfer->formatted_total_size }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span>Created:</span>
                                                <span>{{ $transfer->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span>Expires:</span>
                                                <span>{{ $transfer->expires_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title text-muted">Download Options</h6>
                                            @if($transfer->isComplete())
                                                <a href="{{ $transfer->getDownloadUrl() }}" class="btn download-btn w-100 mb-3">
                                                    <i class="bx bx-download me-2"></i>
                                                    Download All Files (ZIP)
                                                </a>
                                                <p class="text-muted small mb-0">
                                                    All files will be downloaded as a single ZIP archive.
                                                </p>
                                            @else
                                                <div class="text-center">
                                                    <div class="progress mb-3">
                                                        <div class="progress-bar" style="width: {{ $transfer->progress_percentage }}%"></div>
                                                    </div>
                                                    <p class="text-muted mb-0">
                                                        Upload in progress... {{ $transfer->progress_percentage }}% complete
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Files List -->
                            <h5 class="mb-3">Files ({{ $transfer->files->count() }})</h5>
                            
                            @if($transfer->files->count() === 0)
                                <div class="text-center py-4">
                                    <i class="bx bx-folder-open file-icon"></i>
                                    <p class="text-muted mt-2">No files uploaded yet.</p>
                                </div>
                            @else
                                <div class="files-list">
                                    @foreach($transfer->files as $file)
                                        <div class="file-item">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bx bx-file file-icon me-3"></i>
                                                        <div>
                                                            <h6 class="mb-1">{{ $file->original_name }}</h6>
                                                            <small class="text-muted">
                                                                {{ $file->formatted_size }} • 
                                                                <span class="status-badge status-{{ $file->status }}">
                                                                    {{ ucfirst($file->status) }}
                                                                </span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    @if($file->isComplete())
                                                        <span class="text-success">
                                                            <i class="bx bx-check-circle"></i> Ready
                                                        </span>
                                                    @else
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar" style="width: {{ $file->progress_percentage }}%"></div>
                                                        </div>
                                                        <small class="text-muted">{{ $file->progress_percentage }}%</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Footer -->
                            <div class="text-center mt-4 pt-4 border-top">
                                <p class="text-muted mb-0">
                                    Powered by <strong>Bulk Transfer System</strong>
                                </p>
                                <small class="text-muted">
                                    This transfer will expire on {{ $transfer->expires_at->format('M d, Y \a\t g:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 