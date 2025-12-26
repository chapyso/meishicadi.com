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
    <!-- [ auth-signup ] start -->
    
    <div class="card">
        <div class="row align-items-center">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{ __('Login') }}</h2>
                    </div>
                    {{ Form::open(['route' => 'login', 'method' => 'post', 'id' => 'loginForm', 'class' => 'login-form']) }}
                    <div class="">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Email')]) }}
                            @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Your Password'), 'id' => 'input-password']) }}



                            @error('password')
                                <span class="error invalid-password text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        @if (Route::has('password.request'))
                            <div class="form-group mb-4">
                                <a href="{{ route('password.request') }}"
                                    class="small text-dark   text-underline--dashed  border-primary">
                                    {{ __('Forgot Your Password?') }}</a>
                            </div>
                        @endif

                        @if (env('RECAPTCHA_MODULE') == 'yes')
                            <div class="form-group col-lg-12 col-md-12 mt-3">
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <span class="small text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                        <div class="d-grid">
                            {{ Form::submit(__('Login'), ['class' => 'btn btn-primary btn-block mt-2', 'id' => 'saveBtn']) }}
                        </div>
                        {{ Form::close() }}
                        @if (Utility::getValByName('signup_button') == 'on')
                            <p class="my-4 text-center">{{ __("Don't have an account?") }}
                                <a href="{{ url('register') }}" class="my-4 text-primary">{{ __('Register') }}</a>
                            </p>
                        @endif
                    </div>

                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5"> {{ __('“Attention is the new currency”') }}</h3>
                    <p class="text-white">
                        {{ __('The more effortless the writing looks, the more effort the writer
                                                    actually put into the process.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- [ auth-signup ] end -->
@endsection

