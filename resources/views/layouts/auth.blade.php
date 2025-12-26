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

		<link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

	<link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">


		@stack('css-page')


		<style type="text/css">
				img.navbar-brand-img {
					width: 245px;
					height: 61px;
				} 
		</style>
	</head>


	<body class="{{ $color }}">
		<div class="auth-wrapper auth-v3">
			<div class="bg-auth-side bg-primary"></div>
			<div class="auth-content">
				<div class="auth-wrapper auth-v3">
					<div class="bg-auth-side bg-primary"></div>
					<div class="auth-content">
						<nav class="navbar navbar-expand-md navbar-light default">
							<div class="container-fluid pe-2">
								<a class="navbar-brand" href="#">
									@if ($setting['cust_darklayout'] == 'on')
										<img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png').'?'.time() }}"
											alt="" class="img-fluid" />
									@else
										<img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png').'?'.time() }}"
											alt="" class="img-fluid" />
									@endif
								</a>
								<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
									data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
									aria-expanded="false" aria-label="Toggle navigation">
									<span class="navbar-toggler-icon"></span>
								</button>
								<div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
									<ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
										 <li class="nav-item">
											<a class="nav-link active" href="https://wetransfer.meishicadi.com">Bulk Transfer</a>
										</li>
										<li class="nav-item">
											<a class="nav-link active" href="https://chapysocial.com/pages/contact" target="_blank">Supports</a>
										</li>
										<li class="nav-item">
											<a class="nav-link active" href="https://chapysocial.com" target="_blank">Shop</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="https://chapysocial.com/policies/terms-of-service" target="_blank">Terms</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="https://chapysocial.com/pages/returns-exchanges-read-and-replace-info" target="_blank">Privacy</a>
										</li>
									</ul>
									<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
										@yield('language-bar')
									</ul>
								</div>
							</div>
						</nav>

						@yield('content')
						<div class="auth-footer">
							<div class="container-fluid">
								<div class="row">
									<div class="col-6">
										<p class="">
											{{ __('Copyright © ') }}{{ isset($langSetting['footer_text']) ? $langSetting['footer_text'] : config('app.name', 'vCardGo-SaaS') }} {{ date('Y') }} </p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

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
