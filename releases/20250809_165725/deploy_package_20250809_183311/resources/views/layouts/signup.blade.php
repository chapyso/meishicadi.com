@php
   $logo=\App\Models\Utility::get_file('uploads/logo/');
   $setting = App\Models\Utility::settings();
   $set_cookie = App\Models\Utility::cookie_settings();
   $langSetting=App\Models\Utility::langSetting();
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') : config('app.name', 'vCardGo SaaS')}} - Sign Up</title>
      
      <link rel="icon" href="{{ $logo. '/favicon.png' }}" type="image/x-icon" />
      
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('landing/assets/css/style.css') }}">
      
      @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">

<style type="text/css">
   .logo{
      max-width: 160px;
      width: 100%;
      height: 50px;
      padding: 0.33594rem 0; 
   }
    .logo img {
       width: 100%;
       height: 100%;
   }
   
   .signup-container {
       min-height: 100vh;
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       padding: 40px 0;
   }
   
   .signup-card {
       background: white;
       border-radius: 15px;
       box-shadow: 0 15px 35px rgba(0,0,0,0.1);
       overflow: hidden;
   }
   
   .signup-header {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       color: white;
       padding: 30px;
       text-align: center;
   }
   
   .signup-body {
       padding: 40px;
   }
   
   .form-control {
       border-radius: 8px;
       border: 2px solid #e9ecef;
       padding: 12px 15px;
       transition: all 0.3s ease;
   }
   
   .form-control:focus {
       border-color: #667eea;
       box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
   }
   
   .btn-primary {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       border: none;
       border-radius: 8px;
       padding: 12px 30px;
       font-weight: 600;
       transition: all 0.3s ease;
   }
   
   .btn-primary:hover {
       transform: translateY(-2px);
       box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
   }
   
   .alert {
       border-radius: 8px;
       border: none;
   }
   
   .alert-success {
       background: #d4edda;
       color: #155724;
   }
   
   .alert-danger {
       background: #f8d7da;
       color: #721c24;
   }
</style>

   </head>
   <body translate="no">
      <nav class="custom_navbar">
         <div class="first_side_vector">
            <img src="{{ asset('landing/assets/img/vector0.svg') }}" alt="vector0" class="img-fluid">
         </div>
         <div class="first_right_side_vector">
            <img src="{{ asset('landing/assets/img/vector.svg') }}" alt="vector" class="img-fluid">
         </div>
         <div class="container">
            <div class="row">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="logo">
                     @if ($setting['cust_darklayout'] == 'on')
                        <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png').'?'.time() }}" alt=""
                              class="img-fluid" />
                     @else
                        <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png').'?'.time() }}" alt=""
                              class="img-fluid" />
                     @endif
                  </div>
                  <ul class="nav-links">
                     <li><a href="{{ url('/') }}">Home</a></li>
                     <li><a href="{{ route('login') }}">Login</a></li>
                     @if(Utility::getValByName('signup_button') == 'on')
                     <li class="try-btn"><a href="{{ route('register') }}">{{__('Register')}}</a></li>
                     @endif
                  </ul>
                  <div class="burger">
                     <div class="line1"></div>
                     <div class="line2"></div>
                     <div class="line3"></div>
                  </div>
               </div>
            </div>
         </div>
      </nav>

      <div class="signup-container">
         <div class="container">
            @yield('content')
         </div>
      </div>

      <nav class="custom_navbar">
         <div class="container">
            <div class="row">
               <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="logo footer_logo">
                     <h4><img src="{{$logo.'/logo-dark.png'}}"></h4>
                  </div>
                  <ul class="nav-links footer-nav-links ">
                     <li class="text-muted">Copyright © &nbsp;{{ isset($langSetting['footer_text']) ? $langSetting['footer_text'] : config('app.name', 'vCardGo-SaaS') }} {{ date('Y') }}</li>
                  </ul>
               </div>
            </div>
         </div>
      </nav>

      <script src="{{ asset('landing/assets/js/jquery.min.js') }}"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
      
      <script>
         const navSlide = () => {
           const burger = document.querySelector('.burger');
           const body = document.querySelector('body');
           const nav = document.querySelector('.nav-links');
           const navLinks = document.querySelectorAll('.nav-links li');
         
           //Toggle Nav
           burger.addEventListener('click', () => {
             nav.classList.toggle('nav-active');
         
             //Animate Links
             navLinks.forEach((link, index) => {
               if (link.style.animation) {
                 link.style.animation = '';
               } else {
                 link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
         
               }
             });
         
             //burger animation
             burger.classList.toggle('toggle');
             body.classList.toggle('scroll-hidden');
         
         
           });
         };
         
         navSlide();
      </script>
   </body>
   @if($set_cookie['enable_cookie'] == 'on')
   @include('layouts.cookie_consent')
   @endif
</html>
