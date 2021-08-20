<!--
=========================================================
* Argon Design System - v1.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-design-system
* Copyright 2020 Creative Tim (http://www.creative-tim.com)

Coded by www.creative-tim.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argonfront') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('argonfront') }}/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ config('global.site_logo') }}">
    <title>{{ config('global.site_name','FoodTiger') }}</title>

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link href="{{ asset('argonfront') }}/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ asset('argonfront') }}/css/nucleo-svg.css" rel="stylesheet" />
    <link href="{{ asset('argonfront') }}/css/nucleo-icons.css" rel="stylesheet">

    <!-- CSS Files -->
    <link href="{{ asset('argonfront') }}/css/argon-design-system.min.css?v=1.4.0" rel="stylesheet" />

    <!-- Custom CSS -->
    <link type="text/css" href="{{ asset('custom') }}/css/custom.css" rel="stylesheet">

    <!-- Select2 -->
    <link type="text/css" href="{{ asset('custom') }}/css/select2.min.css" rel="stylesheet">



    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if (env('GOOGLE_ANALYTICS',false))
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo env('GOOGLE_ANALYTICS',''); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo env('GOOGLE_ANALYTICS',''); ?>');
        </script>
    @endif
   
  @include('googletagmanager::head')
  @yield('head')
  @laravelPWA

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">

</head>

<body class="">
    @include('googletagmanager::body')
    @auth()
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth



    <!-- Navbar -->
    @if(!config('app.isqrsaas'))
        @include('layouts.menu.top')
    @else
        @include('layouts.menu.top_justlogo')
    @endif
    
    <!-- End Navbar -->
    <div class="wrapper">
        @yield('content')
        @include('layouts.navbars.cartSideMenu')
        @include('layouts.footers.front')
        @if(request()->get('location') )
            @include('layouts.headers.modallocation')
        @endif
    </div>

    <!--   Core JS Files   -->
    <script src="{{ asset('argonfront') }}/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="{{ asset('argonfront') }}/js/core/popper.min.js" type="text/javascript"></script>
    <script src="{{ asset('argonfront') }}/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{ asset('argonfront') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>

    <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="{{ asset('argonfront') }}/js/plugins/bootstrap-switch.js"></script>

    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{ asset('argonfront') }}/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="{{ asset('argonfront') }}/js/plugins/moment.min.js"></script>

    <script src="{{ asset('argonfront') }}/js/plugins/datetimepicker.js" type="text/javascript"></script>
    <script src="{{ asset('argonfront') }}/js/plugins/bootstrap-datepicker.min.js"></script>

    <!-- Control Center for Argon UI Kit: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('argonfront') }}/js/argon-design-system.js?v=1.2.0" type="text/javascript"></script>


    <!-- Import Vue -->
    <script src="https://unpkg.com/vue@2.1.6/dist/vue.js"></script>
    <!-- Import AXIOS --->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- Add to Cart   -->
    <script>
        var LOCALE="<?php echo  App::getLocale() ?>";
        var CASHIER_CURRENCY = "<?php echo  env('CASHIER_CURRENCY','usd') ?>";
    </script>
    <script src="{{ asset('custom') }}/js/cartFunctions.js"></script>
    <!-- Cart custom sidemenu -->
    <script src="{{ asset('custom') }}/js/cartSideMenu.js"></script>

    <!-- Notify JS -->
    <script src="{{ asset('custom') }}/js/notify.min.js"></script>

     <!-- SELECT2 - CHECKOUT ONLT  -->
     <script src="{{ asset('custom') }}/js/select2.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

     <!-- Google Map -->
     <script async defer
            src= "https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=<?php echo env('GOOGLE_MAPS_API_KEY',''); ?>">
        </script>

    @yield('js')
</body>

</html>
