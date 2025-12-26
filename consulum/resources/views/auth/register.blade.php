@extends('layouts.auth')
@section('page-title')
{{__('Register')}}
@endsection

@section('language-bar')
    <li class="nav-item">
        <select name="language" id="language" class="language-dropdown btn btn-primary mr-2 my-2 me-2" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            @foreach (App\Models\Utility::languages() as $code => $language)
                <option @if($lang == $code) selected @endif value="{{ route('register',$code) }}">{{Str::upper($language)}}</option>
            @endforeach
        </select>
    </li>
@endsection

@push('custom-scripts')
	@if(env('RECAPTCHA_MODULE') == 'yes')
			{!! NoCaptcha::renderJs() !!}
	@endif
@endpush
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
@endphp
@section('content')
<div class="card">
	<div class="row align-items-center">
		<div class="col-xl-6">
			{{Form::open(array('route'=>'register','method'=>'post','id'=>'loginForm'))}}
			<div class="card-body">
                @if (session('status'))
                    <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                        {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
                    </div>
                @endif
				<div class="">
					<h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
				</div>
				<div class="">
					<div class="form-group mb-3">
						<label class="form-label">{{ __('Full Name') }}</label>
						{{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Your Name')))}}
					</div>
					@error('name')
					<span class="error invalid-name text-danger" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
					<div class="form-group mb-3">
						<label class="form-label">{{ __('Email') }}</label>
						{{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
					</div>
					@error('email')
					<span class="error invalid-email text-danger" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
					<div class="form-group mb-3">
						<label class="form-label">{{ __('Password') }}</label>
						{{Form::password('password',array('class'=>'form-control','id'=>'input-password','placeholder'=>__('Enter Your Password')))}}
					</div>
					@error('password')
					<span class="error invalid-password text-danger" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
					<div class="form-group">
						<label class="form-label">{{__('Confirm Password')}}</label>
							{{Form::password('password_confirmation',array('class'=>'form-control','id'=>'confirm-input-password','placeholder'=>__('Confirm Your Password')))}}

						@error('password_confirmation')
						<span class="error invalid-password_confirmation text-danger" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					@if(env('RECAPTCHA_MODULE') == 'yes')
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
						<button class="btn btn-primary btn-block mt-2">{{ __('Register') }}</button>
					</div>

				</div>
				<p class="mb-2 my-4 text-center">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="f-w-400 text-primary">{{ __('Login') }}</a></p>
			</div>
		</div>
		<div class="col-xl-6 img-card-side">
			<div class="auth-img-content">
				<img src="{{asset('assets/images/auth/img-auth-3.svg')}}" alt="" class="img-fluid">
				<h3 class="text-white mb-4 mt-5">{{ __('“Attention is the new currency”') }}</h3>
				<p class="text-white">{{ __('The more effortless the writing looks, the more effort the writer
					actually put into the process.')}}</p>
			</div>
		</div>
	</div>
</div>
@endsection
