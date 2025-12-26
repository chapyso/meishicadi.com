@extends('layouts.admin')
@section('page-title')
    {{ __('Upload Files') }}
@endsection
@section('title')
    {{ __('Upload Files') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('bulk-transfer.index') }}">{{ __('Bulk Transfer') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Upload Files') }}</li>
@endsection

@push('css-page')
<style>
    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        cursor: pointer;
    }
    
    .upload-zone.dragover {
        border-color: #3b82f6;
        background: #eff6ff;
        transform: scale(1.02);
    }
    
    .upload-zone:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .file-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .file-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .file-item.success {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    
    .file-item.error {
        background: #fef2f2;
        border-color: #fecaca;
    }
    
    .progress-bar {
        height: 6px;
        border-radius: 3px;
        background: #e5e7eb;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        transition: width 0.3s ease;
        width: 0%;
    }
    
    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 18px;
        margin-right: 15px;
    }
    
    .upload-icon {
        font-size: 4rem;
        color: #6b7280;
        margin-bottom: 20px;
    }
    
    .upload-zone.dragover .upload-icon {
        color: #3b82f6;
    }
    
    .form-switch {
        padding-left: 2.5em;
    }
    
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        margin-left: -2.5em;
    }
    
    .settings-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Upload Files') }}</h5>
                <small class="text-muted">{{ __('Drag and drop files or click to browse') }}</small>
            </div>
            <div class="card-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Upload Zone -->
                    <div class="upload-zone" id="uploadZone">
                        <div class="upload-icon">
                            <i class="ti ti-upload"></i>
                        </div>
                        <h4 class="mb-3">{{ __('Drop files here') }}</h4>
                        <p class="text-muted mb-4">{{ __('or click to browse files') }}</p>
                        <input type="file" id="fileInput" multiple style="display: none;" accept="*/*">
                        <button type="button" class="btn btn-primary" id="chooseFilesBtn">
                            <i class="ti ti-folder me-2"></i>{{ __('Choose Files') }}
                        </button>
                        <p class="text-muted mt-3 mb-0">
                            <small>{{ __('Maximum file size:') }} <strong>{{ $settings->max_file_size_mb }} MB</strong></small>
                        </p>
                    </div>
                    
                    <!-- File List -->
                    <div id="fileList" class="mt-4" style="display: none;">
                        <h6 class="mb-3">{{ __('Selected Files') }}</h6>
                        <div id="fileItems"></div>
                    </div>
                    
                    <!-- Upload Options -->
                    <div class="mt-4" id="uploadOptions" style="display: none;">
                        <h6 class="mb-3">{{ __('Upload Options') }}</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="passwordProtection">
                                    <label class="form-check-label" for="passwordProtection">
                                        <i class="ti ti-lock me-2"></i>{{ __('Password Protection') }}
                                    </label>
                                </div>
                                
                                <div id="passwordField" style="display: none;">
                                    <div class="form-group">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="{{ __('Enter password for file protection') }}" minlength="4" maxlength="50">
                                        <small class="text-muted">{{ __('Min 4 characters') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emailRecipient">{{ __('Send to Email (Optional)') }}</label>
                                    <input type="email" class="form-control" id="emailRecipient" name="email_recipient" 
                                           placeholder="{{ __('recipient@example.com') }}">
                                    <small class="text-muted">{{ __('We\'ll send the download link to this email') }}</small>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="message">{{ __('Message (Optional)') }}</label>
                                    <textarea class="form-control" id="message" name="message" rows="3" 
                                              placeholder="{{ __('Add a personal message to include with the files...') }}" 
                                              maxlength="500"></textarea>
                                    <small class="text-muted">{{ __('Max 500 characters') }}</small>
                                    <div class="text-end">
                                        <small class="text-muted" id="charCount">0/500</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="uploadBtn">
                                <i class="ti ti-upload me-2"></i>{{ __('Upload Files') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="clearFiles()">
                                <i class="ti ti-x me-2"></i>{{ __('Clear All') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-4 col-md-12">
        <!-- Settings Info -->
        <div class="card settings-card">
            <div class="card-body">
                <h5 class="mb-3">{{ __('Your Plan Limits') }}</h5>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="mb-1">{{ $settings->max_file_size_mb }} MB</h4>
                        <small>{{ __('Max File Size') }}</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="mb-1">{{ $settings->daily_transfer_limit }}</h4>
                        <small>{{ __('Daily Limit') }}</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="mb-1">{{ $settings->monthly_transfer_limit }}</h4>
                        <small>{{ __('Monthly Limit') }}</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="mb-1">{{ $settings->retention_hours }}h</h4>
                        <small>{{ __('Retention') }}</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upload Tips -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">{{ __('Upload Tips') }}</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        {{ __('Support for all file types') }}
                    </li>
                    <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        {{ __('Multiple files at once') }}
                    </li>
                    <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        {{ __('Secure download links') }}
                    </li>
                    <li class="mb-2">
                        <i class="ti ti-check text-success me-2"></i>
                        {{ __('Optional password protection') }}
                    </li>
                    <li class="mb-0">
                        <i class="ti ti-check text-success me-2"></i>
                        {{ __('Auto-deletion after') }} {{ $settings->retention_hours }}h
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
let selectedFiles = [];

$(document).ready(function() {
    console.log('Document ready');
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    
    console.log('Upload zone:', uploadZone);
    console.log('File input:', fileInput);
    
    // Drag and drop functionality
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });
    
    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
    });
    
    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });
    
    uploadZone.addEventListener('click', function() {
        console.log('Upload zone clicked');
        fileInput.click();
    });
    
    // Choose files button click
    document.getElementById('chooseFilesBtn').addEventListener('click', function() {
        console.log('Choose files button clicked');
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function(e) {
        console.log('File input changed:', e.target.files);
        handleFiles(e.target.files);
    });
    
    // Password protection toggle
    $('#passwordProtection').change(function() {
        if (this.checked) {
            $('#passwordField').slideDown();
            $('#password').prop('required', true);
        } else {
            $('#passwordField').slideUp();
            $('#password').prop('required', false);
        }
    });
    
    // Form submission
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        uploadFiles();
    });
    
    // Character count for message
    $('#message').on('input', function() {
        const maxLength = 500;
        const currentLength = $(this).val().length;
        const remaining = maxLength - currentLength;
        
        $('#charCount').text(currentLength + '/' + maxLength);
        
        if (currentLength > maxLength) {
            $('#charCount').addClass('text-danger');
        } else {
            $('#charCount').removeClass('text-danger');
        }
    });
});

function handleFiles(files) {
    console.log('Handling files:', files);
    
    if (!files || files.length === 0) {
        console.log('No files selected');
        return;
    }
    
    // Set default max file size if settings not available
    const maxFileSize = {{ $settings->getMaxFileSizeBytes() ?? 104857600 }}; // 100MB default
    const maxFileSizeMB = {{ $settings->max_file_size_mb ?? 100 }}; // 100MB default
    
    for (let file of files) {
        console.log('Processing file:', file.name, file.size);
        
        // Check file size
        if (file.size > maxFileSize) {
            console.log('File too large:', file.name, file.size, '>', maxFileSize);
            alert(`File "${file.name}" is too large. Maximum size is ${maxFileSizeMB} MB.`);
            continue;
        }
        
        // Check if file already exists
        if (selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
            console.log('File already exists:', file.name);
            continue;
        }
        
        selectedFiles.push(file);
        console.log('Added file:', file.name);
    }
    
    console.log('Total selected files:', selectedFiles.length);
    updateFileList();
}

function updateFileList() {
    console.log('Updating file list. Selected files:', selectedFiles.length);
    
    const fileList = document.getElementById('fileList');
    const fileItems = document.getElementById('fileItems');
    const uploadOptions = document.getElementById('uploadOptions');
    
    if (selectedFiles.length === 0) {
        fileList.style.display = 'none';
        uploadOptions.style.display = 'none';
        console.log('No files, hiding sections');
        return;
    }
    
    fileList.style.display = 'block';
    uploadOptions.style.display = 'block';
    console.log('Showing file list and upload options');
    
    fileItems.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        console.log('Creating file item for:', file.name);
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="file-icon" style="background: #dbeafe; color: #1d4ed8;">
                    <i class="ti ti-file"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${file.name}</h6>
                    <small class="text-muted">${formatFileSize(file.size)}</small>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-${index}"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFile(${index})">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        `;
        fileItems.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileList();
}

function clearFiles() {
    selectedFiles = [];
    updateFileList();
    document.getElementById('fileInput').value = '';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function uploadFiles() {
    if (selectedFiles.length === 0) {
        toastrs('Error', 'Please select files to upload', 'error');
        return;
    }
    
    const formData = new FormData();
    
    // Add files
    selectedFiles.forEach(file => {
        formData.append('files[]', file);
    });
    
    // Add options
    if ($('#passwordProtection').is(':checked')) {
        formData.append('password', $('#password').val());
    }
    
    if ($('#emailRecipient').val()) {
        formData.append('email_recipient', $('#emailRecipient').val());
    }
    
    if ($('#message').val()) {
        formData.append('message', $('#message').val());
    }
    
    // Disable upload button
    $('#uploadBtn').prop('disabled', true).html('<i class="ti ti-loader ti-spin me-2"></i>Uploading...');
    
    // Upload files
    $.ajax({
        url: '{{ route("bulk-transfer.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    $('.progress-fill').css('width', percentComplete + '%');
                }
            });
            return xhr;
        },
        success: function(response) {
            toastrs('Success', 'Files uploaded successfully!', 'success');
            
            // Show uploaded files
            if (response.files && response.files.length > 0) {
                let linksHtml = '<div class="mt-3"><h6>Uploaded Files:</h6>';
                response.files.forEach(file => {
                    linksHtml += `<div class="mb-2"><a href="${file.download_url}" target="_blank" class="btn btn-sm btn-outline-primary">${file.original_name}</a></div>`;
                });
                linksHtml += '</div>';
                $('#fileItems').append(linksHtml);
            }
            
            // Clear form
            setTimeout(() => {
                window.location.href = '{{ route("bulk-transfer.index") }}';
            }, 2000);
        },
        error: function(xhr) {
            let errorMessage = 'Upload failed';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
            }
            toastrs('Error', errorMessage, 'error');
        },
        complete: function() {
            $('#uploadBtn').prop('disabled', false).html('<i class="ti ti-upload me-2"></i>Upload Files');
        }
    });
}
</script>
@endpush 