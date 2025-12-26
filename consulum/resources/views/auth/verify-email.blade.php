@extends('layouts.auth')

@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
@endphp

@section('content')
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600 text-primary">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif
                   
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="row">
                            <div class="col-auto">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        {{ __('Resend Verification Email') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-auto">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"> {{ __('Logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="{{ asset('assets/images/auth/img-auth-3.svg') }}" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">{{ __('“Attention is the new currency”') }}</h3>
                    <p class="text-white">
                        {{ __('The more effortless the writing looks, the more effort the writer actually put into the process.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
@endsection
