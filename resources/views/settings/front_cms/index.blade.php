@extends('layouts.app')
@section('title')
    {{__('messages.front_cms.front_cms')}}
@endsection

@section('styles')
<style>
    /* Social Media Style Navigation Buttons */
    .nav-link {
        transition: all 0.3s ease !important;
        border-radius: 8px !important;
        padding: 8px 16px !important;
        margin: 0 4px !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        color: white !important;
        border: none !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .nav-link:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        color: white !important;
        text-decoration: none !important;
    }

    .nav-link.active {
        box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
    }

    /* Front CMS Button - Blue */
    .nav-link[href*="front-cms"] {
        background: #1877f2 !important;
    }

    /* Notification Templates Button - Purple */
    .nav-link[href*="notification-templates"] {
        background: #8b5cf6 !important;
    }

    /* Subscribers Button - Green */
    .nav-link[href*="email-subscription"] {
        background: #10b981 !important;
    }

    /* Features Button - Orange */
    .nav-link[href*="features"] {
        background: #f97316 !important;
    }

    /* About Us Button - Red */
    .nav-link[href*="about-us"] {
        background: #ef4444 !important;
    }

    /* Testimonials Button - Pink */
    .nav-link[href*="frontTestimonial"] {
        background: #ec4899 !important;
    }

    /* FAQs Button - Indigo */
    .nav-link[href*="frontFaqs"] {
        background: #6366f1 !important;
    }

    /* Inquiries Button - Teal */
    .nav-link[href*="inquiries"] {
        background: #14b8a6 !important;
    }

    /* Theme Configuration Button - Gray */
    .nav-link[href*="theme-configuration"] {
        background: #6b7280 !important;
    }

    /* Banner Button - Yellow */
    .nav-link[href*="banner"] {
        background: #eab308 !important;
        color: #1f2937 !important;
    }

    /* App Download URL Button - Lime */
    .nav-link[href*="app-download"] {
        background: #84cc16 !important;
        color: #1f2937 !important;
    }

    /* Icon styling */
    .nav-link i {
        font-size: 14px !important;
        opacity: 0.9 !important;
    }

    /* Container styling */
    .navbar-nav {
        gap: 8px !important;
        flex-wrap: wrap !important;
    }

    .nav-item {
        margin: 0 !important;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors')
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['route' => 'setting.front.cms.update','enctype' => 'multipart/form-data']) !!}
                        @include('settings.front_cms.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection
