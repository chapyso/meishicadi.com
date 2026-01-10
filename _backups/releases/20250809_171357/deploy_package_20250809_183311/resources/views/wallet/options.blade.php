@extends('layouts.admin')
@section('page-title')
    {{ __('Add to Wallet') }}
@endsection
@section('title')
    {{ __('Add to Wallet') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('Add Your Business Card to Digital Wallet') }}</h5>
                            <p class="text-muted mb-0">{{ __('Make your business card easily accessible on your phone with Apple Wallet or Google Wallet.') }}</p>
                        </div>
                        @if($user->isSuperAdmin())
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary d-flex align-items-center">
                                    <i class="fas fa-crown me-1"></i>
                                    {{ __('Super Admin Access') }}
                                </span>
                                @if($business->created_by != $user->id)
                                    <span class="badge bg-info d-flex align-items-center">
                                        <i class="fas fa-user me-1"></i>
                                        {{ __('Managing:') }} {{ \App\Models\User::find($business->created_by)->name ?? __('Unknown User') }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Use the new wallet buttons component -->
                    <x-wallet-buttons :business="$business" :existingPasses="$existingPasses" :showFallback="true" :googleWalletService="$googleWalletService" />
                    
                    <!-- Business Card Preview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-info"></i>
                                        {{ __('About Your Business Card') }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ __('Business Name:') }}</strong><br>
                                            {{ $business->title }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Designation:') }}</strong><br>
                                            {{ $business->designation ?? __('Not specified') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Card URL:') }}</strong><br>
                                            <a href="{{ url('/' . $business->slug) }}" target="_blank">
                                                {{ url('/' . $business->slug) }}
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Status:') }}</strong><br>
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pro Tips -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-lightbulb"></i>
                                    {{ __('Pro Tips') }}
                                </h6>
                                <ul class="mb-0">
                                    <li>{{ __('Your wallet pass will include your business name, contact information, and a QR code linking to your digital card.') }}</li>
                                    <li>{{ __('The pass will automatically update when you modify your business information.') }}</li>
                                    <li>{{ __('You can share the wallet pass with clients for easy access to your contact details.') }}</li>
                                    <li>{{ __('The pass will expire after one year and can be renewed anytime.') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
