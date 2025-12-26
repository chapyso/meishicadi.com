@extends('layouts.app')
@section('title')
    {{ __('messages.vcard.edit_vcard') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <h1>{{ __('messages.vcard.edit_vcard') }}</h1>
            <a class="btn btn-outline-primary float-end"
                href="{{ route('vcards.index') }}">{{ __('messages.common.back') }}</a>
        </div>
        <div class="col-12">
            @if (Session::has('success'))
                <p class="alert alert-success">{{ getSuccessMessage(Request::query('part')) . Session::get('success') }}</p>
            @endif
            @if (Session::has('error'))
                <p class="alert alert-danger">{{ getSuccessMessage(Request::query('part')) . Session::get('error') }}</p>
            @endif
            @include('layouts.errors')
            @include('flash::message')
        </div>
        
        <!-- Wallet Management Section (Premium Feature) -->
        @if(Auth::check() && (Auth::user()->id == $vcard->user_id || Auth::user()->hasRole('super_admin') || empty($vcard->user_id)))
            @if(checkFeature('wallet_integration'))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-wallet me-2"></i>Wallet Management
                        <span class="badge bg-success ms-2">Premium</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fab fa-apple fa-2x me-3 text-dark"></i>
                                <div>
                                    <h6 class="mb-1">Apple Wallet</h6>
                                    <small class="text-muted">Add your vCard to Apple Wallet for easy sharing</small>
                                </div>
                                <button class="btn btn-outline-primary btn-sm ms-auto" id="generate-apple-pass">
                                    <i class="fas fa-plus me-1"></i>Generate Pass
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fab fa-google fa-2x me-3 text-success"></i>
                                <div>
                                    <h6 class="mb-1">Google Wallet</h6>
                                    <small class="text-muted">Add your vCard to Google Wallet for easy sharing</small>
                                </div>
                                <button class="btn btn-outline-success btn-sm ms-auto" id="generate-google-pass">
                                    <i class="fas fa-plus me-1"></i>Generate Pass
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Premium Wallet Features:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Double-tap to share via NFC or display</li>
                            <li>QR code scanning for quick access</li>
                            <li>Direct link to your full vCard profile</li>
                            <li>Professional appearance in wallet apps</li>
                            <li>Tap-to-share functionality</li>
                        </ul>
                    </div>
                </div>
            </div>
            @else
            <!-- Upgrade Prompt for Non-Premium Users -->
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-crown me-2"></i>Premium Feature: Wallet Integration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="text-warning mb-2">
                                <i class="fas fa-lock me-2"></i>Upgrade Required
                            </h6>
                            <p class="mb-3">Add your vCard to Apple Wallet and Google Wallet for seamless sharing and professional presentation.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fab fa-apple fa-lg me-2 text-muted"></i>
                                        <span>Apple Wallet Integration</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fab fa-google fa-lg me-2 text-muted"></i>
                                        <span>Google Wallet Integration</span>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-3">
                                <li><i class="fas fa-check text-success me-2"></i>Tap-to-share via NFC</li>
                                <li><i class="fas fa-check text-success me-2"></i>QR code scanning</li>
                                <li><i class="fas fa-check text-success me-2"></i>Professional wallet appearance</li>
                                <li><i class="fas fa-check text-success me-2"></i>Direct vCard profile access</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="{{ route('subscription.upgrade') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-arrow-up me-2"></i>Upgrade Now
                            </a>
                            <small class="d-block text-muted mt-2">Unlock premium features</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
        <!-- Debug Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Debug Info
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Auth Check:</strong> {{ Auth::check() ? 'Yes' : 'No' }}</p>
                @if(Auth::check())
                <p><strong>User ID:</strong> {{ Auth::user()->id }}</p>
                <p><strong>VCard User ID:</strong> {{ $vcard->user_id ?? 'NULL' }}</p>
                <p><strong>Is Super Admin:</strong> {{ Auth::user()->hasRole('super_admin') ? 'Yes' : 'No' }}</p>
                <p><strong>VCard Owner Check:</strong> {{ Auth::user()->id == $vcard->user_id ? 'Yes' : 'No' }}</p>
                <p><strong>Empty User ID Check:</strong> {{ empty($vcard->user_id) ? 'Yes' : 'No' }}</p>
                <p><strong>Wallet Feature Available:</strong> {{ checkFeature('wallet_integration') ? 'Yes' : 'No' }}</p>
                @endif
            </div>
        </div>
        @endif
        
        <div class="card">
            <div class="card-body d-sm-flex position-relative px-2">
                <div class="">
                    <div class="">
                        @include('vcards.sub_menu')
                    </div>
                </div>
                <div class="ps-sm-3 pt-lg-auto pt-0 w-100 overflow-auto px-1" id="main">
                    <button type="button"
                        class="btn px-0 aside-menu-container__aside-menubar d-block d-xl-none d-lg-none d-block edit-menu"
                        onclick="openNav()">
                        <i class="fa-solid fa-bars fs-1"></i>
                    </button>
                    {{ Form::hidden('is_true', Request::query('part') == 'business_hours', ['id' => 'vcardCreateEditIsTrue']) }}
                    @if (
                        $partName != 'services' &&
                        $partName != 'custom-links' &&
                            $partName != 'blogs' &&
                            $partName != 'testimonials' &&
                            $partName != 'products' &&
                            $partName != 'galleries' &&
                            $partName != 'instagram-embed' &&
                            $partName != 'banners' &&
                            $partName != 'iframes')
                        {!! Form::open([
                            'route' => ['vcards.update', $vcard->id],
                            'id' => 'editForm',
                            'method' => 'put',
                            'files' => 'true',
                        ]) !!}
                        @include('vcards.fields')
                        {{ Form::close() }}
                    @else
                        @if ($partName === 'blogs')
                            @include('vcards.blogs.index')
                        @elseif($partName === 'services')
                            @include('vcards.services.index')
                        @elseif($partName === 'products')
                            @include('vcards.products.index')
                        @elseif($partName === 'banners')
                            @include('vcards.banner.index')
                        @elseif($partName === 'galleries')
                            @include('vcards.gallery.index')
                        @elseif($partName === 'instagram-embed')
                            @include('vcards.instagram-embed.index')
                        @elseif($partName === 'iframes')
                            @include('vcards.iframes.index')
                        @elseif($partName === 'custom-links')
                            @include('vcards.custom-link.index')
                        @else
                            @include('vcards.testimonials.index')
                        @endif
                    @endif
                    {{-- @if ($partName !== 'services' && $partName !== 'products' && $partName !== 'testimonials' && $partName !== 'galleries' && $partName !== 'blogs' && $partName !== 'iframes') --}}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize date picker for DOB field with enhanced options
    $('#dob-input').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        endDate: '0d', // Cannot select future dates
        clearBtn: true,
        orientation: 'auto',
        startView: 2, // Start with month view
        minViewMode: 2, // Minimum view is month
        maxViewMode: 4, // Maximum view is decade
        keyboardNavigation: true,
        forceParse: false,
        calendarWeeks: true,
        todayBtn: 'linked',
        language: 'en',
        weekStart: 1, // Start week on Monday
        templates: {
            leftArrow: '<i class="fas fa-chevron-left"></i>',
            rightArrow: '<i class="fas fa-chevron-right"></i>'
        }
    });
    
    // Make the input field clickable to open calendar
    $('#dob-input').on('click', function() {
        $(this).datepicker('show');
    });
    
    // Make the calendar button clickable
    $('#dob-calendar-btn').on('click', function() {
        $('#dob-input').datepicker('show');
    });
    
    // Add hover effect to show it's clickable
    $('#dob-input, #dob-calendar-btn').hover(
        function() {
            $(this).css('cursor', 'pointer');
        },
        function() {
            $(this).css('cursor', 'default');
        }
    );
    
    // Add visual feedback when calendar opens
    $('#dob-input').on('show', function() {
        $(this).addClass('border-primary');
    });
    
    // Remove visual feedback when calendar closes
    $('#dob-input').on('hide', function() {
        $(this).removeClass('border-primary');
    });
    
    // Add keyboard shortcuts
    $('#dob-input').on('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).datepicker('show');
        }
    });
    
    // Wallet Management Functions
    $('#generate-apple-pass').on('click', async function() {
        const button = this;
        const originalText = button.innerHTML;
        
        try {
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generating...';
            button.disabled = true;
            
            const response = await fetch(`/wallet/apple/{{ $vcard->id }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = result.pass_url;
                button.innerHTML = '<i class="fas fa-check me-1"></i>Generated!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            } else {
                throw new Error(result.message);
            }
            
        } catch (error) {
            console.error('Apple Wallet Error:', error);
            button.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Error';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }
    });
    
    $('#generate-google-pass').on('click', async function() {
        const button = this;
        const originalText = button.innerHTML;
        
        try {
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generating...';
            button.disabled = true;
            
            const response = await fetch(`/wallet/google/{{ $vcard->id }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.open(result.pass_url, '_blank');
                button.innerHTML = '<i class="fas fa-check me-1"></i>Generated!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            } else {
                throw new Error(result.message);
            }
            
        } catch (error) {
            console.error('Google Wallet Error:', error);
            button.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Error';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        }
    });
});
</script>
@endpush
