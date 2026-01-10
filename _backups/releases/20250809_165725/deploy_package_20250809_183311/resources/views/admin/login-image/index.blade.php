@extends('layouts.admin')

@section('page-title')
    {{ __('Login Page Image Management') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Login Image Management') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Login Page Right Panel Image') }}</h5>
                    <span class="badge bg-primary">{{ __('Super Admin Only') }}</span>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Current Image Preview -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">{{ __('Current Image') }}</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="login-preview-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 2rem; margin-bottom: 1rem;">
                                    <img src="{{ $currentImage }}" 
                                         alt="Current Login Image" 
                                         class="img-fluid" 
                                         style="max-height: 300px; width: auto;"
                                         id="currentImagePreview">
                                </div>
                                
                                <div class="image-info">
                                    <p class="text-muted mb-1">
                                        <strong>{{ __('Status') }}:</strong>
                                        @if($currentImage === 'https://meishicadi.com/assets/images/auth/img-auth-3.svg')
                                            <span class="badge bg-info">{{ __('Default Image') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ __('Custom Image') }}</span>
                                        @endif
                                    </p>
                                    
                                    @if($imageUpdatedAt)
                                        <p class="text-muted mb-0">
                                            <strong>{{ __('Last Updated') }}:</strong>
                                            {{ \Carbon\Carbon::parse($imageUpdatedAt)->format('M d, Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">{{ __('Update Image') }}</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.login-image.update') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label for="login_image" class="form-label">{{ __('Select New Image') }}</label>
                                        <input type="file" 
                                               class="form-control @error('login_image') is-invalid @enderror" 
                                               id="login_image" 
                                               name="login_image" 
                                               accept="image/svg+xml,image/png,image/jpeg,image/jpg,image/webp"
                                               onchange="previewImage(this)">
                                        
                                        @error('login_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="form-text">
                                            <i class="ti ti-info-circle me-1"></i>
                                            {{ __('Supported formats: SVG, PNG, JPG, JPEG, WEBP. Max size: 2MB.') }}
                                        </div>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="mb-3" id="imagePreviewContainer" style="display: none;">
                                        <label class="form-label">{{ __('Preview') }}</label>
                                        <div class="login-preview-container" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1rem;">
                                            <img id="imagePreview" src="" alt="Preview" class="img-fluid" style="max-height: 200px; width: auto;">
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                                            <i class="ti ti-upload me-2"></i>
                                            {{ __('Update Login Image') }}
                                        </button>
                                        
                                        @if($currentImage !== 'https://meishicadi.com/assets/images/auth/img-auth-3.svg')
                                            <button type="button" class="btn btn-outline-warning" onclick="resetImage()">
                                                <i class="ti ti-refresh me-2"></i>
                                                {{ __('Reset to Default') }}
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guidelines -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="ti ti-lightbulb me-2"></i>
                                    {{ __('Image Guidelines') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>{{ __('Recommended Specifications') }}</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Aspect ratio: 4:3 or 16:9') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Minimum width: 800px') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('File size: Under 2MB') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Format: SVG (preferred), PNG, JPG, WEBP') }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>{{ __('Design Tips') }}</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Use light/white elements for better visibility') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Avoid dark backgrounds') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Keep it simple and professional') }}</li>
                                            <li><i class="ti ti-check text-success me-2"></i>{{ __('Test on mobile devices') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetModalLabel">{{ __('Reset to Default Image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to reset the login image to the default? This action cannot be undone.') }}</p>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    {{ __('The current custom image will be permanently deleted.') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form action="{{ route('admin.login-image.reset') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">{{ __('Reset to Default') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const container = document.getElementById('imagePreviewContainer');
    const uploadBtn = document.getElementById('uploadBtn');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
            uploadBtn.disabled = false;
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
        uploadBtn.disabled = true;
    }
}

function resetImage() {
    const modal = new bootstrap.Modal(document.getElementById('resetModal'));
    modal.show();
}

// Form submission handling
document.getElementById('imageUploadForm').addEventListener('submit', function(e) {
    const uploadBtn = document.getElementById('uploadBtn');
    const originalText = uploadBtn.innerHTML;
    
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="ti ti-loader me-2"></i>{{ __("Uploading...") }}';
    
    // Re-enable after 5 seconds if there's an error
    setTimeout(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
    }, 5000);
});

// Auto-refresh current image preview
setInterval(() => {
    fetch('{{ route("admin.login-image.current") }}')
        .then(response => response.json())
        .then(data => {
            const currentPreview = document.getElementById('currentImagePreview');
            if (currentPreview.src !== data.image_url) {
                currentPreview.src = data.image_url;
                location.reload(); // Refresh to update status badges
            }
        })
        .catch(error => console.error('Error fetching current image:', error));
}, 30000); // Check every 30 seconds
</script>
@endpush 