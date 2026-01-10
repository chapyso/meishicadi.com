	@php
		
		// get theme color
		$setting = App\Models\Utility::colorset();
		$layout_setting = App\Models\Utility::getLayoutsSetting();
		$color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
		$company_logo = \App\Models\Utility::GetLogo();
		
		$logo = \App\Models\Utility::get_file('uploads/logo/');
		
		$company_favicon = Utility::getValByName('company_favicon');
		$set_cookie = App\Models\Utility::cookie_settings();
		$lang=app()->getLocale('lang');
		if ($lang == 'ar' || $lang == 'he') {
			$setting['SITE_RTL'] = 'on';
		}
		$langSetting=App\Models\Utility::langSetting();
	@endphp
	<!DOCTYPE html>
	<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

	<head>
		<title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'vCardGo SaaS')}} - @yield('page-title')</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,  initial-scale=1.0, user-scalable=0, minimal-ui" />
		<meta name="description" content="Dashboard Template Description" />
		<meta name="keywords" content="Dashboard Template" />
		<meta name="author" content="Nick Sharma" />

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Favicon -->

		<link rel="icon" href="{{ $logo . '/favicon.png' }}" type="image/x-icon" />
		<!-- font css -->
		<link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

		<!-- vendor css -->
		@if ($setting['SITE_RTL'] == 'on')
			<link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
		@endif
		
		@if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
			<link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
		@else
			<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
		@endif

		<link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}?v={{ time() }}">

	<link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}?v={{ time() }}">
		<link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">


		@stack('css-page')


		<style type="text/css">
				img.navbar-brand-img {
					width: 245px;
					height: 61px;
				} 
		</style>
	</head>


	<body class="{{ $color }}">
		@yield('content')

		<!-- Required Js -->
		<script src="{{ asset('assets/js/vendor-all.js') }}"></script>
		<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
		<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
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
