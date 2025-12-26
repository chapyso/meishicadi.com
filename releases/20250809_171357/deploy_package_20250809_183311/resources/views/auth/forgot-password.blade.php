@extends('layouts.auth')
@section('page-title')
    {{ __('Reset Password') }}
@endsection

@section('language-bar')
    <li class="nav-item ">
        <select name="language" id="language" class="btn btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
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
@section('content')
<div class="card">
    <div class="row align-items-center">
        <div class="col-xl-6">
            <div class="card-body">
                <div class="">
                    <h2 class="mb-3 f-w-600">{{ __('Forgot Password') }}</h2>
                    @if(session('status'))
                        <div class="alert alert-primary">
                            {{ session('status') }}
                        </div>
                    @endif
                  <p class="text-xs text-muted">{{__('We will send a link to reset your password')}}</p>
                </div>
                <form method="POST" action="{{ route('password.email') }}" id="form_data">
                    @csrf
                    <div class="">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Your Email">
                            @error('email')
                            <span class="error invalid-email text-danger" role="alert">
                                <small>{{ $message }}</small>
                            </span>
                            @enderror
                        </div>                      
                        
                        @if (env('RECAPTCHA_MODULE') == 'yes')
                            <div class="form-group col-lg-12 col-md-12 mt-3">
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                <span class="error small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        @endif

                        <div class="d-grid">
                            <button class="btn btn-primary btn-submit btn-block mt-2">{{ __('Send Password Reset Link') }}  </button>
                        </div>
                        <p class="my-4 text-center">{{__('Back to ')}}
                            <a href="{{route('login')}}" class="my-4 text-primary">{{ __('Login') }}</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xl-6 img-card-side">
            <div class="auth-img-content">
                <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                <p class="text-white">The more effortless the writing looks, the more effort the writer
                    actually put into the process.</p>
            </div>
        </div>
    </div>
</div>
@endsection
