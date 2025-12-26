@extends('layouts.auth')

@section('page-title')
    {{ __('Login') }}
@endsection

@section('language-bar')
    <li class="nav-item ">
        <select name="language" id="language" class=" language-dropdown btn btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach (App\Models\Utility::languages() as $code => $language)
                <option @if($lang == $code) selected @endif value="{{ route('login',$code) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>
@endsection

@push('custom-scripts')
    @if (env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif
    <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                $("#saveBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
@endpush
@php
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
    $settings = Utility::settings();
@endphp

@section('content')
    <!-- Modern Navigation Header -->
    <nav class="modern-navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <div class="brand-logo">
                    <img
                        class="brand-image"
                        src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                        alt="Meishicadi"
                    />
                </div>
            </div>
            
            
            
            <div class="nav-actions">
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu (removed links) -->
        <div class="mobile-menu" id="mobileMenu"></div>
    </nav>

    <div class="login-container">
        <!-- Left Panel - Login Form -->
        <div class="login-form-panel">
            <!-- Welcome Text -->
            <div class="welcome-section">
                <div class="welcome-badge">
                    <svg class="welcome-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>Secure Login</span>
                </div>
                <h1 class="welcome-title">Welcome back to your workspace</h1>
                <p class="welcome-subtitle">Sign in to continue managing your digital business cards and presence</p>
            </div>
                    
            <!-- Login Form -->
            {{ Form::open(['route' => 'login', 'method' => 'post', 'id' => 'loginForm', 'class' => 'login-form']) }}
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                        Email address
                    </label>
                    {{ Form::email('email', null, [
                        'class' => 'form-input',
                        'placeholder' => __('Enter your email'),
                        'id' => 'email'
                    ]) }}
                    @error('email')
                        <div class="error-message">
                            <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                    
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Password
                    </label>
                    <div class="password-input-wrapper">
                        {{ Form::password('password', [
                            'class' => 'form-input password-input',
                            'placeholder' => __('Enter your password'),
                            'type' => 'password',
                            'id' => 'password'
                        ]) }}
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <svg class="toggle-icon" id="password-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">
                            <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                    
                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember" class="custom-checkbox">
                        <span class="checkmark"></span>
                        <span class="checkbox-label">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>
                    
                <!-- reCAPTCHA -->
                @if (env('RECAPTCHA_MODULE') == 'yes')
                    <div class="recaptcha-section">
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <div class="error-message">
                                <svg class="error-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                @endif
                    
                <!-- Sign In Button -->
                <button type="submit" id="saveBtn" class="signin-button">
                    <svg class="button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Sign In
                </button>
                    
            {{ Form::close() }}
                
            <!-- Sign Up Link -->
            @if (Utility::getValByName('signup_button') == 'on')
                <div class="signup-section">
                    <div class="divider">
                        <span>or</span>
                    </div>
                    <p class="signup-text">
                        Don't have an account?
                        <a href="{{ url('register') }}" class="signup-link">
                            Create one now
                        </a>
                    </p>
                </div>
            @endif
        </div>
            
        <!-- Right Panel - Image -->
        <div class="illustration-panel">
            <div class="illustration-content">
                <div class="floating-elements">
                    <div class="floating-card card-1">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="floating-card card-2">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="floating-card card-3">
                        <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                    </div>
                </div>
                <img src="{{ Utility::getValByName('login_right_image') ?: 'https://meishicadi.com/assets/images/auth/img-auth-3.svg' }}" 
                     alt="Authentication" 
                     class="illustration-image">
                <div class="illustration-title">
                    Your Digital Business Hub
                </div>
                <p class="illustration-subtitle">
                    Manage your business cards, digital presence, and professional networking all in one place
                </p>
                <div class="feature-list">
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Professional Business Cards</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Digital Networking</span>
                    </div>
                    <div class="feature-item">
                        <svg class="feature-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Analytics & Insights</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Modern Navigation */
        .modern-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            padding: 0;
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        
        .nav-brand {
            display: flex;
            align-items: center;
        }
        
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .brand-image {
            height: 32px;
            width: auto;
            display: block;
        }
        
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        
        .nav-link {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: #7c3aed;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #7c3aed;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .btn-secondary {
            background: transparent;
            color: #7c3aed;
            border: 2px solid #7c3aed;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #7c3aed;
            color: white;
            transform: translateY(-2px);
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
        }
        
        .menu-icon {
            width: 24px;
            height: 24px;
        }
        
        .mobile-menu {
            display: none;
            background: white;
            padding: 24px;
            border-top: 1px solid #e5e7eb;
        }
        
        .mobile-nav-link {
            display: block;
            color: #6b7280;
            text-decoration: none;
            padding: 12px 0;
            font-weight: 500;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .mobile-nav-link:last-child {
            border-bottom: none;
        }
        
        /* Main container */
        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            margin-top: 70px;
        }
        
        /* Left panel - Login form */
        .login-form-panel {
            width: 50%;
            background: white;
            padding: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        
        /* Right panel - Illustration */
        .illustration-panel {
            width: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }
        
        .illustration-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        /* Welcome section */
        .welcome-section {
            margin-bottom: 48px;
        }
        
        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            color: #7c3aed;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        
        .welcome-icon {
            width: 16px;
            height: 16px;
        }
        
        .welcome-title {
            font-size: 40px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
            line-height: 1.2;
        }
        
        .welcome-subtitle {
            font-size: 18px;
            color: #6b7280;
            line-height: 1.6;
        }
        
        /* Form styles */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .label-icon {
            width: 16px;
            height: 16px;
            color: #7c3aed;
        }
        
        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .form-input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
            transform: translateY(-1px);
        }
        
        /* Password input wrapper */
        .password-input-wrapper {
            position: relative;
        }
        
        .password-input {
            padding-right: 48px;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #7c3aed;
            background: #f3f4f6;
        }
        
        .toggle-icon {
            width: 20px;
            height: 20px;
        }
        
        /* Form options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }
        
        /* Custom checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }
        
        .custom-checkbox {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        .checkmark {
            height: 20px;
            width: 20px;
            background-color: white;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            margin-right: 12px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .checkbox-wrapper:hover .checkmark {
            border-color: #7c3aed;
            background: #f3f4f6;
        }
        
        .custom-checkbox:checked ~ .checkmark {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        
        .custom-checkbox:checked ~ .checkmark:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .checkbox-label {
            font-size: 14px;
            color: #374151;
            transition: color 0.3s ease;
        }
        
        .checkbox-wrapper:hover .checkbox-label {
            color: #1f2937;
        }
        
        .forgot-link {
            font-size: 14px;
            color: #7c3aed;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #6d28d9;
            text-decoration: underline;
        }
        
        /* Sign in button */
        .signin-button {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        }
        
        .signin-button:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.4);
        }
        
        .signin-button:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.3);
        }
        
        .button-icon {
            width: 20px;
            height: 20px;
        }
        
        /* Sign up section */
        .signup-section {
            text-align: center;
            margin-top: 32px;
            padding-top: 32px;
        }
        
        .divider {
            position: relative;
            margin-bottom: 24px;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 16px;
            color: #9ca3af;
            font-size: 14px;
            position: relative;
        }
        
        .signup-text {
            font-size: 14px;
            color: #6b7280;
        }
        
        .signup-link {
            color: #7c3aed;
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: color 0.3s ease;
        }
        
        .signup-link:hover {
            color: #6d28d9;
            text-decoration: underline;
        }
        
        /* Illustration panel */
        .illustration-content {
            text-align: center;
            max-width: 500px;
            position: relative;
            z-index: 2;
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }
        
        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 16px;
            animation: float 6s ease-in-out infinite;
        }
        
        .card-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .card-2 {
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .card-3 {
            bottom: 20%;
            left: 15%;
            animation-delay: 4s;
        }
        
        .card-icon {
            width: 24px;
            height: 24px;
            color: white;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .illustration-image {
            width: 100%;
            max-width: 400px;
            margin-bottom: 40px;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.1));
        }
        
        .illustration-title {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
        }
        
        .illustration-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 16px;
        }
        
        .feature-icon {
            width: 20px;
            height: 20px;
            color: #a78bfa;
        }
        
        /* Footer */
        .page-footer {
            background: #1f2937;
            color: white;
            padding: 60px 0 0 0;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .footer-section {
            max-width: 300px;
        }
        
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .footer-logo {
            width: 32px;
            height: 32px;
            color: #a78bfa;
        }
        
        .footer-brand-text {
            font-size: 20px;
            font-weight: 700;
        }
        
        .footer-description {
            color: #9ca3af;
            line-height: 1.6;
        }
        
        .footer-links {
            display: flex;
            gap: 60px;
        }
        
        .footer-link-group h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: white;
        }
        
        .footer-link {
            display: block;
            color: #9ca3af;
            text-decoration: none;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #a78bfa;
        }
        
        .footer-bottom {
            border-top: 1px solid #374151;
            padding: 24px 0;
        }
        
        .footer-bottom-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .copyright {
            color: #9ca3af;
            font-size: 14px;
        }
        
        .footer-bottom-links {
            display: flex;
            gap: 24px;
        }
        
        .footer-bottom-link {
            color: #9ca3af;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        
        .footer-bottom-link:hover {
            color: #a78bfa;
        }
        
        /* Error messages */
        .error-message {
            display: flex;
            align-items: center;
            color: #dc2626;
            font-size: 14px;
            margin-top: 8px;
        }
        
        .error-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }
        
        /* Responsive design */
        @media (max-width: 1024px) {
            .nav-menu {
                display: none;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .mobile-menu {
                display: none;
            }
            
            .mobile-menu.active {
                display: block;
            }
            
            .login-container {
                flex-direction: column;
            }
            
            .login-form-panel {
                width: 100%;
                padding: 60px 40px;
                min-height: auto;
            }
            
            .illustration-panel {
                display: none;
            }
            
            .welcome-title {
                font-size: 32px;
            }
            
            .welcome-subtitle {
                font-size: 16px;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 40px;
            }
            
            .footer-links {
                gap: 40px;
            }
        }
        
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 16px;
            }
            
            .login-form-panel {
                padding: 40px 24px;
            }
            
            .brand-text {
                font-size: 18px;
            }
            
            .welcome-title {
                font-size: 28px;
            }
            
            .footer-bottom-content {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
            
            .footer-bottom-links {
                gap: 16px;
            }
        }
        
        /* Override any conflicting styles */
        .auth-wrapper,
        .auth-content {
            min-height: 100vh !important;
            display: flex !important;
            align-items: stretch !important;
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .auth-wrapper .auth-content .card,
        .auth-wrapper .auth-content .auth-footer {
            display: none !important;
        }
    </style>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                toggleIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }
        
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
        }
    </script>
@endsection 