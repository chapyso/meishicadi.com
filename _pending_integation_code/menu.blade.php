@php
    $users = \Auth::user();
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $company_logo = Utility::getValByName('company_logo');
    $company_small_logo = Utility::getValByName('company_small_logo');
    $currantLang = $users->currentLanguage();
    $fullLang = \App\Models\Languages::where('code', $currantLang)->first();
    $languages = Utility::languages();
    
    $businesses = App\Models\Business::allBusiness();
    $currantBusiness = $users->currentBusiness();
    //$bussiness_id = !empty($users->current_business)?$users->current_business:'';
    $bussiness_id = $users->current_business;
@endphp

<!-- [ Header ] start -->
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
    @else
        <header class="dash-header">
@endif

<div class="header-wrapper">
    <div class="me-auto dash-mob-drp">
        <ul class="list-unstyled">
            <li class="dash-h-item mob-hamburger">
                <a href="#!" class="dash-head-link" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="dropdown dash-h-item drp-company">
                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="theme-avtar avatar avatar-sm rounded-circle">
                        <img
                            src="{{ (!empty($users->avatar) && file_exists(public_path('storage/uploads/avatar/' . $users->avatar))) ? asset('storage/uploads/avatar/' . $users->avatar) : asset('custom/img/logo-placeholder-image-2.png') }}" /></span>
                    <span class="hide-mob ms-2">{{ __('Welcome') }}, {{ \Auth::user()->name }}</span>
                    <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="ti ti-user"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                        <i class="ti ti-power"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>
        </ul>
    </div>
          <ul class="list-unstyled">
                          @if (Auth::user()->hasBulkTransferAccess())
                    <li class="dropdown dash-h-item drp-bulk-transfer me-2">
                        <a class="dash-head-link" href="{{ route('bulk-transfer.index') }}" 
                           data-bs-toggle="tooltip" title="{{ __('Bulk Transfer') }}">
                            <i class="ti ti-share nocolor"></i>
                            <span class="drp-text hide-mob">{{ __('Bulk Transfer') }}</span>
                        </a>
                    </li>
                @endif
          @if (\Auth::user()->can('manage plan'))
            <li class="dropdown dash-h-item drp-support me-2">
                <a class="dash-head-link" href="https://chapysocial.com/support/" 
                   data-bs-toggle="tooltip" title="{{ __('Contact Support') }}" target="_blank">
                    <i class="ti ti-phone nocolor"></i>
                    <span class="drp-text hide-mob">{{ __('Support') }}</span>
                </a>
            </li>
        @endif
                        @if (Auth::user()->hasWalletAccess())
                <li class="dropdown dash-h-item drp-wallet me-2">
                    <a class="dash-head-link" href="{{ route('wallet.index') }}" 
                       data-bs-toggle="tooltip" title="{{ __('Wallet') }}">
                        <i class="ti ti-device-mobile nocolor"></i>
                        <span class="drp-text hide-mob">{{ __('Wallet') }}</span>
                    </a>
                </li>
                @endif
                        @if (Auth::user()->hasIntegrationsAccess())
                <li class="dropdown dash-h-item drp-integrations me-2">
                    <a class="dash-head-link" href="{{ route('integrations.index') }}" 
                       data-bs-toggle="tooltip" title="{{ __('Integrations') }}">
                        <i class="ti ti-plug nocolor"></i>
                        <span class="drp-text hide-mob">{{ __('Integrations') }}</span>
                    </a>
                </li>
                @endif
        @if(Auth::user()->hasAnalyticsAccess())
            @if(Auth::user()->type == 'super admin')
                <li class="dropdown dash-h-item drp-analytics me-2">
                    <a class="dash-head-link" href="{{ route('tap-analytics.index') }}" 
                       data-bs-toggle="tooltip" title="{{ __('Global Tap Analytics') }}">
                        <i class="ti ti-brand-google-analytics nocolor"></i>
                        <span class="drp-text hide-mob">{{ __('Analytics') }}</span>
                    </a>
                </li>
            @else
                @php
                    $businessId = Auth::user()->current_business;
                    if (!$businessId || !\App\Models\Business::find($businessId)) {
                        $businessId = \App\Models\Business::where('created_by', Auth::id())->value('id');
                    }
                @endphp
                @if($businessId)
                <li class="dropdown dash-h-item drp-analytics me-2">
                    <a class="dash-head-link" href="{{ route('business.analytics', $businessId) }}" 
                       data-bs-toggle="tooltip" title="{{ __('Tap Analytics') }}">
                        <i class="ti ti-brand-google-analytics nocolor"></i>
                        <span class="drp-text hide-mob">{{ __('Analytics') }}</span>
                    </a>
                </li>
                @endif
            @endif
        @endif
        @if (\Auth::user()->can('manage company setting') || \Auth::user()->can('manage system setting'))
            <li class="dropdown dash-h-item drp-settings me-2">
                <a class="dash-head-link" href="{{ route('systems.index') }}" 
                   data-bs-toggle="tooltip" title="{{ __('Settings') }}">
                    <i class="ti ti-settings nocolor"></i>
                    <span class="drp-text hide-mob">{{ __('Settings') }}</span>
                </a>
            </li>
        @endif
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <i class="ti ti-world nocolor"></i>
                <span class="drp-text hide-mob">{{ ucFirst($fullLang->fullName) }}</span>
                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach (App\Models\Utility::languages() as $code => $lang)
                    <a href="{{ route('change.language', $code) }}"
                        class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                        <span>{{ ucFirst($lang) }}</span>
                    </a>
                @endforeach
                <div class="dropdown-divider m-0"></div>
                @if (Auth::user()->type == 'super admin')
                    <a href="#" data-size="md" data-url="{{ route('create.language') }}" data-ajax-popup="true"
                        data-bs-toggle="tooltip" title="{{ __('Create') }}"
                        data-title="{{ __('Create New Language') }}" class="dropdown-item text-primary">
                        {{ __('Create Language') }}
                    </a>
                @endif
                @if (Auth::user()->type == 'super admin')
                    <a class="dropdown-item text-primary"
                        href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Language') }}</a>
                @endif
            </div>
        </li>
    </ul>
</div>
</header>
<!-- [ Header ] end -->
