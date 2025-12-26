@php
    $users = \Auth::check() ? \Auth::user() : null;
    $profile = \App\Models\Utility::get_file('uploads/avatar');
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = Utility::getValByName('company_logo');
    $company_small_logo = Utility::getValByName('company_small_logo');
    $currantLang = $users ? ($users->currentLanguage() ?? 'en') : 'en';
    $fullLang = \App\Models\Languages::where('code', $currantLang)->first();
    $languages = Utility::languages();
    
    $businesses = $users ? App\Models\Business::allBusiness() : collect();
    $currantBusiness = $users ? $users->currentBusiness() : null;
    $bussiness_id = $users ? ($users->current_business ?? '') : '';
@endphp

@auth

<!-- [ Header ] start -->
@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
    @else
        <header class="dash-header">
@endif

<div class="header-wrapper">
    @if(method_exists($users, 'isImpersonating') && $users->isImpersonating())
        <div class="alert alert-warning mb-0 p-3 text-center" style="border-radius: 0; background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border: 2px solid #ffc107;">
            <div class="d-flex align-items-center justify-content-center">
                <i class="ti ti-alert-triangle me-2" style="font-size: 1.2em; color: #856404;"></i>
                <strong style="color: #856404; font-size: 1.1em;">{{ __('IMPERSONATION MODE') }}: {{ __('You are logged in as') }} <span style="color: #d63384;">{{ $users->name }}</span></strong>
                <a href="{{ route('leave.impersonation') }}" class="btn btn-sm btn-danger ms-3" style="font-weight: bold;">
                    <i class="ti ti-logout me-1"></i>{{ __('Return to Super Admin') }}
                </a>
            </div>
            <div class="mt-2" style="font-size: 0.9em; color: #856404;">
                <i class="ti ti-info-circle me-1"></i>{{ __('All actions will be performed as this user. Click "Return to Super Admin" when finished.') }}
            </div>
        </div>
    @endif
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
            <li class="dash-h-item">
                <a class="dash-head-link me-0" href="{{ route('profile') }}" title="{{ __('Profile') }}">
                    <span class="theme-avtar avatar avatar-sm rounded-circle">
                        <img
                            src="{{ (!empty($users) && !empty($users->avatar)) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}" /></span>
                    <span class="hide-mob ms-2">{{ __('Welcome') }}, {{ $users ? $users->name : 'Guest' }}</span>
                </a>
            </li>
            <li class="dash-h-item">
                <a class="dash-head-link me-0" href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"
                   title="{{ __('Logout') }}">
                    <i class="ti ti-power"></i>
                    <span class="hide-mob ms-2">{{ __('Logout') }}</span>
                </a>
                <form id="frm-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
    @if ($users && $users->type != 'super admin')
        @can('create business')
            <div class="d-flex align-items-center justify-content-between justify-content-md-end" data-bs-placement="top">
                <a href="#" data-size="xl" data-url="{{ route('business.create') }}" data-ajax-popup="true"
                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                    data-bs-original-title="{{ __('Create your new bussiness') }}"
                    data-title="{{ __('Create New Business') }}"
                    class="btn cust-btn-creat  d-inline-flex align-items-center rounded  shadow-sm border border-success ">
                    <i class="ti ti-plus me-2"></i>
                    <span class="hide-mob">{{ __('Create Bussiness') }}</span>
                </a>
            </div>
        @endcan
        {{-- //business Display Start --}}
        <ul class="list-unstyled">
            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle arrow-none me-0 cust-btn shadow-sm border border-success"
                    data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"
                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                    data-bs-original-title="{{ __('Select your bussiness') }}">
                    <i class="ti ti-credit-card"></i>
                    <span class="drp-text hide-mob">{{ __(ucfirst($currantBusiness)) }}</span>
                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                    @foreach ($businesses as $key => $business)
                        <a href="{{ route('business.change', $key) }}" class="dropdown-item">
                            <i
                                class="@if ($bussiness_id == $key) ti ti-checks text-primary @elseif($currantBusiness == $business) ti ti-checks text-primary @endif "></i>
                            <span>{{ ucfirst($business) }}</span>
                        </a>
                    @endforeach
                    <div class="dropdown-divider m-0"></div>
                </div>
            </li>
        </ul>

        {{-- //business Display End --}}
    @endif
    <ul class="list-unstyled">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <i class="ti ti-world nocolor"></i>
                <span class="drp-text hide-mob">{{ ucfirst($fullLang->fullName) }}</span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach ($languages as $code => $fullName)
                    <a href="{{ route('change.language', $code) }}" class="dropdown-item @if ($code == $currantLang) text-primary @endif">
                        <i class="ti ti-checks text-{{ $currantLang == $code ? 'primary' : 'muted' }}"></i>
                        <span>{{ $fullName }}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </ul>
</div>
</header>
<!-- [ Header ] end -->
@else
    <script>window.location.href = "{{ route('login') }}";</script>
@endauth
