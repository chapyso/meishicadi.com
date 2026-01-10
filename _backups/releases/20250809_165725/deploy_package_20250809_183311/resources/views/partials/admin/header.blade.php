@php
    $logo=\App\Models\Utility::get_file('uploads/logo/');
    $company_favicon=Utility::getValByName('company_favicon');
    $setting = App\Models\Utility::settings();
    
@endphp

<head>
    <!-- Performance: preconnect to common CDNs -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <!-- Fonts preconnect if used -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'vCardGo SaaS')}} - @yield('page-title')</title>

    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8"  />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    @if(Auth::check() && Auth::user()->type == 'super admin')
        <link rel="icon" href="{{$logo.'/favicon.png'}}" type="image" sizes="16x16">
    @else
        <link rel="icon" href="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}" type="image" sizes="16x16">
    @endif

    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('assets/css/style.css') }}?t={{ time() }}" as="style">
    <link rel="preload" href="{{ asset('custom/js/jquery.min.js') }}" as="script">
    <link rel="preload" href="{{ asset('assets/js/plugins/bootstrap.min.js') }}" as="script">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/animate.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{asset('custom/libs/summernote/summernote-bs4.css')}}">
     @stack('css-page')
  
    
    <!-- vendor css -->
    @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?t={{ time() }}" id="main-style-link1">
    @endif

    @if( isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" >
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
     <!-- custom css -->
     <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}?t={{ time() }}">

    <link rel="stylesheet" href="{{ asset('custom/css/emojionearea.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}?t={{ time() }}">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/bootstrap-switch-button.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-modern.css') }}?t={{ time() }}">
    <style>
    [dir="rtl"] .dash-header {
            left: 15px!important;;
            right: 280px!important;
        }
        [dir="rtl"] .dash-sidebar.light-sidebar 
        {
            right: 0px
        }
    </style>
</head>


