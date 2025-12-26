@php
    // get theme color
    $setting = App\Models\Utility::settings();
    $layout_setting = App\Models\Utility::getLayoutsSetting();

    $company_logo = \App\Models\Utility::GetLogo();

    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_favicon = Utility::getValByName('company_favicon');
    $set_cookie = App\Models\Utility::cookie_settings();
    $lang = app()->getLocale('lang');
    if ($lang == 'ar' || $lang == 'he') {
        $setting['SITE_RTL'] = 'on';
    }
    $langSetting = App\Models\Utility::langSetting();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <style>
        :root {
            --color-customColor: <?= $color ?>;    
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'vCardGo SaaS') }}
        - @yield('page-title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Workdo" />

    <script>
        (function () {
            var blocked = [
                'envato.workdo.io/verify.js',
                'envato.workdo.io/check'
            ];
            function shouldBlock(url) {
                try { return !!url && blocked.some(function (p) { return String(url).indexOf(p) !== -1; }); } catch (e) { return false; }
            }
            // Intercept script element src
            var _create = Document.prototype.createElement;
            Document.prototype.createElement = function (name) {
                var el = _create.call(this, name);
                if (String(name).toLowerCase() === 'script') {
                    var _setAttr = el.setAttribute;
                    el.setAttribute = function (attr, val) {
                        if (attr === 'src' && shouldBlock(val)) { return; }
                        return _setAttr.call(this, attr, val);
                    };
                    Object.defineProperty(el, 'src', {
                        configurable: true,
                        get: function () { return this.getAttribute('src'); },
                        set: function (val) { if (!shouldBlock(val)) this.setAttribute('src', val); }
                    });
                }
                return el;
            };
            // Intercept DOM append of script tags
            var _append = Element.prototype.appendChild;
            Element.prototype.appendChild = function (child) {
                try {
                    if (child && child.tagName === 'SCRIPT') {
                        var src = child.getAttribute && child.getAttribute('src');
                        if (shouldBlock(src)) { return child; }
                    }
                } catch (e) {}
                return _append.call(this, child);
            };
            // Intercept jQuery.getScript if present later
            window.addEventListener('DOMContentLoaded', function () {
                if (window.jQuery && jQuery.getScript) {
                    var _orig = jQuery.getScript;
                    jQuery.getScript = function (url) {
                        if (shouldBlock(url)) { try { return Promise.resolve(); } catch (e) { return; } }
                        return _orig.apply(this, arguments);
                    };
                }
            });
            // Intercept fetch and XHR
            if (window.fetch) {
                var _fetch = window.fetch;
                window.fetch = function (input, init) {
                    var url = (typeof input === 'string') ? input : (input && input.url);
                    if (shouldBlock(url)) { return Promise.resolve(new Response('', { status: 204 })); }
                    return _fetch(input, init);
                };
            }
            var _open = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function (method, url) {
                if (shouldBlock(url)) { try { this.abort(); } catch (e) {} return; }
                return _open.apply(this, arguments);
            };
        })();
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->

    <link rel="icon" href="{{ $logo . '/favicon.png' }}" type="image/x-icon" />
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">

    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        @if ($setting['SITE_RTL'] == 'on')
            <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
        @else
            <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
        @endif
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">


    @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth-rtl.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/custom-auth.css') }}" id="main-style-link">
    @endif

    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}" id="main-style-link">
    @endif

    @stack('css-page')

    <style type="text/css">
        img.navbar-brand-img {
            width: 245px;
            height: 61px;
        }
    </style>
</head>


<body class="{{ $themeColor }}">

    <div class="custom-login">
        <div class="login-bg-img">
            @if($themeColor != $color)
            <img src="{{ asset('assets/images/auth/theme-3.svg') }}" class="login-bg-1">
            @else
            <img src="{{ asset('assets/images/auth/' . $color . '.svg') }}" class="login-bg-1">
            @endif
            
            <img src="{{ asset('assets/images/auth/common.svg') }}" class="login-bg-2">
        </div>
        <div class="bg-login bg-primary"></div>
        <div class="custom-login-inner">
            <header class="dash-header">
                <nav class="navbar navbar-expand-md default">
                    <div class="container-fluid pe-2">
                        <a class="navbar-brand" href="#">
                            @if ($setting['cust_darklayout'] == 'on')
                                <img class="logo"
                                    src="{{ rtrim($logo, '/') . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time() }}"
                                    alt="" loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ rtrim($logo, '/') }}/logo-light.png?{{ time() }}';" />
                            @else
                                <img class="logo"
                                    src="{{ rtrim($logo, '/') . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                                    alt="" loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ rtrim($logo, '/') }}/logo-dark.png?{{ time() }}';" />
                            @endif
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarlogin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarlogin">
                            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                                @if (Utility::getValByName('display_landing_page') == 'on')
                                    @include('landingpage::layouts.buttons')
                                @endif
                                @yield('language-bar')
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </main>
            <footer>

                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>&copy; {{ date('Y') }}&nbsp;
                                    {{ isset($langSetting['footer_text']) ? $langSetting['footer_text'] : config('app.name', 'vCardGo-SaaS') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
            document.addEventListener('DOMContentLoaded', (event) => {
                const recaptcha = document.querySelector('.g-recaptcha');
                recaptcha.setAttribute("data-theme", "dark");
            });
        @endif
    </script>
    <script>
        feather.replace();
    </script>
    @stack('custom-scripts')

</body>
@if ($set_cookie['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif

</html>
