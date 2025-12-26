@extends('layouts.admin')

@section('page-title')
    {{ __('Plan Expired') }}
@endsection

@section('title')
    {{ __('Plan Expired') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Plan Expired') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10 col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="ti ti-alert-triangle text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">{{ __('Your Plan Has Expired') }}</h4>
                        <p class="text-muted mb-0">{{ __('Please request a renewal to continue using our services.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ti ti-check-circle" style="font-size: 1.5rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">{{ __('Request Submitted Successfully!') }}</h6>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
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

                <!-- Current Plan Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="ti ti-info-circle me-2"></i>
                                {{ __('Current Plan Information') }}
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('Plan Name:') }}</strong> {{ $currentPlan ? $currentPlan->name : __('No Plan') }}</p>
                                    <p class="mb-1"><strong>{{ __('Expired Date:') }}</strong> {{ $user->plan_expire_date ? date('d-m-Y', strtotime($user->plan_expire_date)) : __('N/A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>{{ __('Duration:') }}</strong> {{ $currentPlan ? __(ucfirst($currentPlan->duration)) : __('N/A') }}</p>
                                    <p class="mb-1"><strong>{{ __('Price:') }}</strong> {{ $currentPlan ? (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') . $currentPlan->price : __('N/A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Renewal Form -->
                <form action="{{ route('plan.request.renewal') }}" method="POST">
                    @csrf
                    
                    <!-- Multiple Renewal Options -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="ti ti-refresh me-2"></i>
                                {{ __('Available Renewal Plans') }}
                            </h5>
                            
                            @if($renewalPlans && $renewalPlans->count() > 0)
                                <div class="row">
                                    @foreach($renewalPlans as $plan)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 {{ $plan->id == $renewalPlan->id ? 'border-primary' : 'border-light' }}">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h6 class="card-title mb-0">{{ $plan->name }}</h6>
                                                        @if($plan->id == $renewalPlan->id)
                                                            <span class="badge bg-primary">{{ __('Recommended') }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($plan->description)
                                                        <p class="card-text text-muted small mb-3">{{ $plan->description }}</p>
                                                    @endif
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <small class="text-muted">
                                                                <i class="ti ti-circle-plus me-1"></i>
                                                                {{ count($plan->getThemes()) }} {{ __('Themes') }}
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">
                                                                <i class="ti ti-building me-1"></i>
                                                                {{ $plan->business == '-1' ? 'Unlimited' : $plan->business }} {{ __('Business') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="h5 text-primary mb-0">
                                                                {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}
                                                            </span>
                                                            <small class="text-muted d-block">
                                                                {{ __('/ Duration : ') . __(ucfirst($plan->duration)) }}
                                                            </small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="renewal_plan_id" 
                                                                   id="renewal_plan_{{ $plan->id }}" 
                                                                   value="{{ $plan->id }}" {{ $plan->id == $renewalPlan->id ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="renewal_plan_{{ $plan->id }}">
                                                                {{ __('Select') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    {{ __('No renewal plans available at the moment. Please contact support.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="ti ti-message-circle me-2"></i>
                                {{ __('Additional Notes & Feature Requests') }}
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">
                                            {{ __('Please let us know if you would like to activate any additional features or have special requirements:') }}
                                        </label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" name="notes" rows="5" 
                                                  placeholder="{{ __('Tell us about any additional features you would like to activate, special requirements, or any other information we should know...') }}">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading mb-2">
                                            <i class="ti ti-lightbulb me-2"></i>
                                            {{ __('Available Features You Can Request:') }}
                                        </h6>
                                        <ul class="mb-0">
                                            <li>{{ __('Custom Domain') }}</li>
                                            <li>{{ __('Custom Subdomain') }}</li>
                                            <li>{{ __('Branding Removal') }}</li>
                                            <li>{{ __('PWA Business Features') }}</li>
                                            <li>{{ __('QR Code Generation') }}</li>
                                            <li>{{ __('ChatGPT Integration') }}</li>
                                            <li>{{ __('Digital Wallet Integration') }}</li>
                                            <li>{{ __('Additional Storage Space') }}</li>
                                            <li>{{ __('Priority Support') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-12 text-center">
                            @if($renewalPlans && $renewalPlans->count() > 0)
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="ti ti-send me-2"></i>
                                    {{ __('Submit Renewal Request') }}
                                </button>
                                
                                <!-- Processing Indicator (hidden by default) -->
                                <div class="d-none" id="processingIndicator">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="spinner-border text-primary me-3" role="status">
                                            <span class="visually-hidden">{{ __('Processing...') }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ __('Processing Your Request...') }}</h6>
                                            <p class="text-muted mb-0">{{ __('Please wait while we submit your renewal request.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <button type="button" class="btn btn-secondary btn-lg" disabled>
                                    <i class="ti ti-ban me-2"></i>
                                    {{ __('No Plans Available') }}
                                </button>
                            @endif
                            
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="ti ti-arrow-left me-2"></i>
                                {{ __('Back to Dashboard') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Information Cards -->
<div class="row mt-4">
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="theme-avtar bg-primary">
                    <i class="ti ti-clock"></i>
                </div>
                <h6 class="mt-3 mb-2">{{ __('Quick Processing') }}</h6>
                <p class="text-muted mb-0">{{ __('We typically process renewal requests within 24-48 hours.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="theme-avtar bg-success">
                    <i class="ti ti-shield-check"></i>
                </div>
                <h6 class="mt-3 mb-2">{{ __('Secure Process') }}</h6>
                <p class="text-muted mb-0">{{ __('Your information is secure and will be handled confidentially.') }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <div class="theme-avtar bg-info">
                    <i class="ti ti-headset"></i>
                </div>
                <h6 class="mt-3 mb-2">{{ __('Support Available') }}</h6>
                <p class="text-muted mb-0">{{ __('Need help? Contact our support team for assistance.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css-page')
<style>
    .theme-avtar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .theme-avtar i {
        font-size: 1.5rem;
        color: white;
    }
    
    .card.border-primary {
        border-width: 2px !important;
    }
    
    .form-check-input:checked {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@push('script-page')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route("plan.request.renewal") }}"]');
    const submitBtn = document.getElementById('submitBtn');
    const processingIndicator = document.getElementById('processingIndicator');
    
    if (form && submitBtn && processingIndicator) {
        form.addEventListener('submit', function() {
            // Hide submit button and show processing indicator
            submitBtn.classList.add('d-none');
            processingIndicator.classList.remove('d-none');
            
            // Disable form to prevent double submission
            const formElements = form.querySelectorAll('input, button, textarea, select');
            formElements.forEach(element => {
                element.disabled = true;
            });
        });
    }
});
</script>
@endpush 