@extends('layouts.app')
@section('title')
    {{__('messages.about_us.about_us')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('layouts.errors')
            @include('flash::message')
            
            <!-- Modern Page Header -->
            <div class="modern-page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="modern-page-title">
                            <i class="fas fa-info-circle me-3 text-purple"></i>
                            {{__('messages.about_us.about_us')}}
                        </h1>
                        <p class="modern-page-subtitle">{{__('messages.about_us.manage_about_sections')}}</p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="modern-page-actions">
                            <button type="submit" form="aboutUsForm" class="btn btn-primary btn-lg modern-save-btn">
                                <i class="fas fa-save me-2"></i>
                                {{__('messages.common.save')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card modern-card">
                <div class="card-body p-0">
                    {!! Form::open(['route' => 'aboutUs.store','enctype' => 'multipart/form-data', 'id' => 'aboutUsForm']) !!}
                    <div class="row g-4 p-4">
                        @foreach($aboutUs as $about)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="about-section-card modern-about-card h-100">
                                    <div class="section-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-edit me-2 text-purple"></i>
                                            {{__('messages.about_us.section')}} {{ $loop->iteration }}
                                        </h6>
                                    </div>
                                    <div class="section-content p-4">
                                        @include('sadmin.aboutUs.about1')
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Bottom Actions -->
                    <div class="modern-bottom-actions p-4 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="" type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-undo me-2"></i>
                                {{__('messages.common.discard')}}
                            </a>
                            <div class="modern-action-info">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{__('messages.about_us.save_changes_info')}}
                                </small>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/about-us-image-upload.js') }}"></script>
@endpush

@push('styles')
    <style>
        :root {
            --purple-primary: #8b5cf6;
            --purple-light: #a855f7;
            --purple-dark: #7c3aed;
            --gray-dark: #1a1a1a;
            --gray-medium: #2d2d2d;
            --gray-light: #444;
            --white: #ffffff;
        }
        
        /* Modern Page Header */
        .modern-page-header {
            background: linear-gradient(135deg, var(--gray-dark) 0%, var(--gray-medium) 100%);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-left: 4px solid var(--purple-primary);
        }
        
        .modern-page-title {
            color: var(--white);
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .modern-page-subtitle {
            color: #cccccc;
            font-size: 1.1rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .text-purple {
            color: var(--purple-primary) !important;
        }
        
        /* Modern Card Styling */
        .modern-card {
            background: var(--white);
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            overflow: hidden;
        }
        
        .modern-about-card {
            background: var(--white);
            border: 2px solid #e9ecef;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
        }
        
        .modern-about-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.15);
            border-color: var(--purple-primary);
        }
        
        .modern-about-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-light));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .modern-about-card:hover::before {
            transform: scaleX(1);
        }
        
        /* Section Header */
        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
            position: relative;
        }
        
        .section-header h6 {
            margin: 0;
            color: var(--gray-dark);
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .modern-about-card:hover .section-header::after {
            transform: scaleX(1);
        }
        
        /* Image Picker Improvements */
        .image-picker {
            margin-bottom: 2rem;
        }
        
        .image-picker .previewImage {
            min-height: 400px !important;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 3px dashed #dee2e6;
            transition: all 0.4s ease;
            background-color: #f8f9fa;
            position: relative;
            overflow: hidden;
        }
        
        .image-picker .previewImage[style*="background-image"] {
            background-color: var(--white) !important;
            border: 3px solid var(--purple-primary) !important;
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.2);
        }
        
        .image-picker .previewImage:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        /* Modern Badge Styling */
        .modern-badge {
            background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-light) 100%) !important;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 12px;
            box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
            animation: badgePulse 2s infinite;
        }
        
        @keyframes badgePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Upload Button Improvements */
        .upload-btn {
            background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-light) 100%);
            border: none;
            color: var(--white);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(139, 92, 246, 0.2);
        }
        
        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            background: linear-gradient(135deg, var(--purple-light) 0%, var(--purple-primary) 100%);
        }
        
        /* Remove Button Improvements */
        .remove-image-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: var(--white);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }
        
        .remove-image-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        }
        
        /* Modern Buttons */
        .modern-save-btn {
            background: linear-gradient(135deg, var(--purple-primary) 0%, var(--purple-light) 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        
        .modern-save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
            background: linear-gradient(135deg, var(--purple-light) 0%, var(--purple-primary) 100%);
        }
        
        /* Bottom Actions */
        .modern-bottom-actions {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        
        .modern-action-info {
            text-align: center;
        }
        
        /* Form Improvements */
        .form-control, .form-control:focus {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            padding: 12px 16px;
        }
        
        .form-control:focus {
            border-color: var(--purple-primary);
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }
        
        .form-label.required::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }
        
        /* Character Counter Styling */
        .character-counter {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .character-counter.warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-color: #ffc107;
            color: #856404 !important;
        }
        
        .character-counter.danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
            color: #721c24 !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-page-title {
                font-size: 2rem;
            }
            
            .modern-page-header {
                padding: 1.5rem;
            }
            
            .modern-about-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endpush
