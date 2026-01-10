@extends('layouts.signup')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="signup-card">
            <div class="signup-header">
                <h2 class="mb-2"><i class="ti ti-cards me-2"></i>{{ __('Digital Business Cards') }}</h2>
                <p class="mb-0">{{ __('Get started with your digital business card solution') }}</p>
            </div>
            <div class="signup-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('signup.submit') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }} *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }} *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="company" class="form-label">{{ __('Company Name') }} *</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                           id="company" name="company" value="{{ old('company') }}" required>
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="cards_required" class="form-label">{{ __('Number of Cards Required') }} *</label>
                                    <select class="form-control @error('cards_required') is-invalid @enderror" 
                                            id="cards_required" name="cards_required" required>
                                        <option value="">{{ __('Select number of cards') }}</option>
                                        <option value="1" {{ old('cards_required') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ old('cards_required') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ old('cards_required') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ old('cards_required') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ old('cards_required') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6-10" {{ old('cards_required') == '6-10' ? 'selected' : '' }}>6-10</option>
                                        <option value="11-20" {{ old('cards_required') == '11-20' ? 'selected' : '' }}>11-20</option>
                                        <option value="21-50" {{ old('cards_required') == '21-50' ? 'selected' : '' }}>21-50</option>
                                        <option value="50+" {{ old('cards_required') == '50+' ? 'selected' : '' }}>50+</option>
                                    </select>
                                    @error('cards_required')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="industry" class="form-label">{{ __('Industry/Business Type') }}</label>
                                    <input type="text" class="form-control @error('industry') is-invalid @enderror" 
                                           id="industry" name="industry" value="{{ old('industry') }}" 
                                           placeholder="{{ __('e.g., Technology, Healthcare, Finance') }}">
                                    @error('industry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="message" class="form-label">{{ __('Additional Information') }}</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="4" 
                                      placeholder="{{ __('Tell us about your business needs, special requirements, or any questions you have...') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" id="terms" name="terms" value="1" 
                                       {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="terms">
                                    {{ __('I agree to the') }} <a href="#" target="_blank">{{ __('Terms and Conditions') }}</a> 
                                    {{ __('and') }} <a href="{{ route('legal.privacy') }}" target="_blank">{{ __('Privacy Policy') }}</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="ti ti-send me-2"></i>{{ __('Submit Signup Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
