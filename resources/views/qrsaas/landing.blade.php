<!--

=========================================================
* Impact Design System - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/impact-design-system
* Copyright 2010 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/impact-design-system/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head> 
    <!-- Primary Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argonfront') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('argonfront') }}/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ config('global.site_logo') }}">
    <title>{{ config('global.site_name','QRTiger') }}</title>

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

    @yield('head')
    @laravelPWA

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <!-- Fontawesome -->
    <link type="text/css" href="{{ asset('impactfront') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">


    <!-- Nucleo icons -->
    <link rel="stylesheet" href="{{ asset('impactfront') }}/vendor/nucleo/css/nucleo.css" type="text/css">

    <!-- Front CSS -->
    <link type="text/css" href="{{ asset('impactfront') }}/css/front.min.css" rel="stylesheet">

<!-- Anti-flicker snippet (recommended)
<style>.async-hide { opacity: 0 !important} </style>
<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',4000,
{'GTM-K9BGS8K':true});</script>

<!-- Analytics-Optimize Snippet
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-46172202-22', 'auto', {allowLinker: true});
ga('set', 'anonymizeIp', true);
ga('require', 'GTM-K9BGS8K');
ga('require', 'displayfeatures');
ga('require', 'linker');
ga('linker:autoLink', ["2checkout.com","avangate.com"]);
</script>
<!-- end Analytics-Optimize Snippet -->
</head>

<body>

    <header class="header-global">
        <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg headroom py-lg-3 px-lg-6 navbar-light navbar-theme-primary">
            <div class="container">
                <a class="navbar-brand @@logo_classes" href="/">
                    <img class="navbar-brand-dark common" src="{{ config('global.site_logo') }}" height="35" alt="Logo">
                    <img class="navbar-brand-light common" src="{{ config('global.site_logo') }}" height="35" alt="Logo">
                </a>
                <div class="navbar-collapse collapse" id="navbar_global">
                    <div class="navbar-collapse-header">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="/">
                                    <img src="{{ config('global.site_logo') }}" height="35" alt="Logo">
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <a href="#navbar_global" role="button" class="fas fa-times" data-toggle="collapse"
                                    data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false"
                                    aria-label="Toggle navigation"></a>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-nav navbar-nav-hover justify-content-center">
                        <li class="nav-item">
                        <a data-scroll href="#product" class="nav-link">{{ __('Product') }}</a>
                        </li>
                        <li class="nav-item">
                            <a data-scroll href="#pricing" class="nav-link">{{ __('Pricing') }}</a>
                        </li>
                        <li class="nav-item">
                            <a data-scroll href="#testimonials" class="nav-link">{{ __('Testimonials') }}</a>
                        </li>
                        <li class="nav-item">
                            <a data-scroll href="#demo" class="nav-link">{{ __('Demo') }}</a>
                        </li>

                        
                    </ul>
                </div>
                <div class=" @@cta_button_classes">
                    <a data-scroll href="/login" class="btn btn-md btn-docs btn-outline-white animate-up-2 mr-3"><i class="fas fa-th-large mr-2"></i>
                        @auth()
                            {{ __('Dashboard')}}
                        @endauth
                        @guest()
                            {{ __('Login')}}
                        @endguest
                    </a>
                    @guest()
                        <a href="{{ route('newrestaurant.register') }}" target="_blank" class="btn btn-md btn-secondary animate-up-2"><i class="fas fa-paper-plane mr-2"></i>{{ __('Register')}}</a>
                    @endguest
                    
                </div>
                <div class="d-flex d-lg-none align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global"
                        aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation"><span
                            class="navbar-toggler-icon"></span></button>
                </div>
            </div>
        </nav>
    </header>
    
    <main>

         <!-- Loader -->
        <div class="preloader bg-soft flex-column justify-content-center align-items-center">
            <div class="loader-element">
                <span class="loader-animated-dot"></span>
                <img src="{{ config('global.site_logo') }}" height="40" alt="logo">
            </div>
        </div>

        <!-- Hero 1 -->
        <section class="section-header pb-7 pb-lg-11 bg-soft">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-6 order-2 order-lg-1">
                    <img src="{{ asset('impactfront') }}/img/flayer.png" alt="">
                    </div>
                    <div class="col-12 col-md-5 order-1 order-lg-2">
                    <h1 class="display-2 mb-3">{{__('Contactless QR digital menu')}}</h1>
                          <p class="lead">{{ __('Create digital menu for your Restaurant or Bar. Engage more with your customers.')}}<br /><strong>{{ __('Their mobile is your menu now!') }}</strong></p>
                          <div class="mt-4">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            @guest()
                                <form action="{{ route('newrestaurant.register') }}" class="d-flex flex-column mb-5 mb-lg-0">
                                    <input class="form-control" type="text" name="name" placeholder="{{ __('Restaurant or bar name')}}" required>
                                    <input class="form-control my-3" type="email" name="email" placeholder="{{ __('Your email')}}" required>
                                    <input class="form-control my-1" type="text" name="phone" placeholder="{{ __('Phone')}}" required>
                                    <button class="btn btn-primary my-3" type="submit">{{ __('Join now')}}</button>
                                </form>
                            @endguest


                            
                          </div>
                      </div>
                </div>
            </div>
            <div class="pattern bottom"></div>
        </section>

         <!-- Product -->
         <section id="product" class="section section-lg">
            <div class="container">
                <div class="row justify-content-center mb-5 mb-md-7">
                    <div class="col-12 col-md-8 text-center">
                        <h2 class="h1 font-weight-bolder mb-4">{{ __('The most comprehensive platform for QR digital menu') }}</h2>
                        <p class="lead">{{ __('There are platforms where you can make QR code, but no menu. There are platforms where you can crete menu but not design your QR')}} <br /><strong>{{ __('We do both')}}.</strong></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4 mb-5">
                        <div class="card shadow-soft border-light">
                            <div class="card-header p-0">
                                <img src="{{ asset('impactfront') }}/img/menubuilder.jpg" class="card-img-top rounded-top" alt="image">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title mt-3">{{ __('Create Digital Menu') }}</h3>
                                <p class="card-text">{{ __('Create your menu directly in our platform. Update anytime. Easy And Simple') }}</p>
                                <ul class="list-group d-flex justify-content-center mb-4">
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                    <div>{{ __('Real-time chnages')}}</div>    
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Organize into categories')}}</div>      
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Extras and items variants')}}</div>    
                                    </li>
                                </ul>
                                <a href="{{ route('newrestaurant.register') }}" class="btn btn-primary">{{ __('Experience it')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-5">
                        <div class="card shadow-soft border-light">
                            <div class="card-header p-0">
                                <img src="{{ asset('impactfront') }}/img/qr_image_builder.jpg" class="card-img-top rounded-top" alt="image">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title mt-3">{{ __('Create QR') }}</h3>
                                <p class="card-text">{{ __('8 different designs. Unlimited color options. Choose QR and Flayer style. ') }}</p>
                                <ul class="list-group d-flex justify-content-center mb-4">
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Beautifull QR Styles')}}</div>    
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Pick QR color')}}</div>        
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Print templates for download')}}</div> 
                                    </li>
                                </ul>
                                <a href="{{ route('newrestaurant.register') }}" class="btn btn-primary">{{ __('Design it')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 mb-5">
                        <div class="card shadow-soft border-light">
                            <div class="card-header p-0">
                                <img src="{{ asset('impactfront') }}/img/mobile_pwa.jpg" class="card-img-top rounded-top" alt="image">
                            </div>
                            <div class="card-body">
                            <h3 class="card-title mt-3">{{ __('Go Digital')}}</h3>
                            <p class="card-text">{{ __('Now your visitors will use their mobile phone camera to access your menu.')}}</p>
                                <ul class="list-group d-flex justify-content-center mb-4">
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                    <div>{{ __('No mobile app required')}}</div>    
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('Super fast online menu - PWA')}}</div>    
                                    </li>
                                    <li class="list-group-item d-flex pl-0 pb-1">
                                        <span class="mr-2"><i class="fas fa-check-circle text-success"></i></span>
                                        <div>{{ __('View analytics')}}</div> 
                                    </li>
                                </ul>
                                <a href="{{ route('newrestaurant.register') }}" class="btn btn-primary">{{ __('Go Mobile')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="section-header bg-primary text-white">
            <div class="container">
               
                <div class="row justify-content-center mb-6">
                    <div class="col-12 col-md-10 text-center">
                        <h1 class="display-2 mb-3">{{ __('Simple Pricing') }}</h1>
                    <p class="lead px-5">{{__('Start free and and fell in love in our pro features')}}</p>
                    </div>
                </div>
                <div class="row text-gray">
                    @foreach ($plans as $plan)
                        @include('qrsaas.plan',['plan'=>$plan,'col'=>$col])
                    @endforeach
                </div>
                
            </div>
            
        </section>

        <!-- Testimonials -->
        <section id="testimonials" class="section section-lg">
            <div class="container">
                <div class="row justify-content-center mb-5 mb-lg-7">
                    <div class="col-12 col-md-8 text-center">
                        <h1 class="h1 font-weight-bolder mb-4">{{ __('Restaurants and Bars that love our QRs') }}</h1>
                        <p class="lead">{{ __('Used by top restaurants worldwide') }}</p>
                    </div>
                </div>
                <div class="row mb-lg-5">
                    <div class="col-12 col-lg-6">
                        <div class="customer-testimonial d-flex mb-5">
                            <img src="https://randomuser.me/api/portraits/men/74.jpg" class="image image-sm mr-3 rounded-circle shadow" alt="">
                            <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                                <div class="d-flex mb-4">
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                </div>
                                <p class="mt-2">"We use {{ env('APP_NAME') }} to protect our visitors. Dirty old menus are thing from the past. So far clients report no issues. And they love our new online menu."</p>
                                <span class="h6">- James Curran <small class="ml-0 ml-md-2">Brooklyn Taco</small></span>
                            </div>
                        </div>
                        <div class="customer-testimonial d-flex mb-5">
                            <img src="https://randomuser.me/api/portraits/men/62.jpg" class="image image-sm mr-3 rounded-circle shadow" alt="">
                            <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                                <div class="d-flex mb-4">
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                </div>
                                <p class="mt-2">"{{ env('APP_NAME') }} was the perfect tool for the covid situation. We should gone earlier to this type of menu"</p>
                                <span class="h6">- Richard Thomas <small class="ml-0 ml-md-2">Burger 2Go</small></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 pt-lg-6">
                        <div class="customer-testimonial d-flex mb-5">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" class="image image-sm mr-3 rounded-circle shadow" alt="">
                            <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                                <div class="d-flex mb-4">
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                </div>
                                <p class="mt-2">"No more printing and re - printing for some small mistake in our menu or price change. We love what {{ env('APP_NAME') }} have provided. "</p>
                                <span class="h6">- Jessica Evans <small class="ml-0 ml-md-2">Awang Italian Restorant</small></span>
                            </div>
                        </div>
                        <div class="customer-testimonial d-flex mb-5">
                            <img src="https://randomuser.me/api/portraits/men/61.jpg" class="image image-sm mr-3 rounded-circle shadow" alt="">
                            <div class="content bg-soft shadow-soft border border-light rounded position-relative p-4">
                                <div class="d-flex mb-4">
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                    <span class="text-warning mr-2"><i class="star fas fa-star"></i></span>
                                </div>
                                <p class="mt-2">"Clients are happy. They can see that we are responsible bar and their health is priority. No more old dirty menus :D. All thy need is their phone."</p>
                                <span class="h6">- Jason Edwards <small class="ml-0 ml-md-2">Malibu Diner</small></span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>

         <!-- DEMO -->
         <section id="demo" class="section section-lg pb-5 bg-soft">
            <div class="container">
                <div class="row"> 
                    <div class="col-12 text-center mb-5">
                        <h2 class="mb-4">{{ __('See a demo online menu') }}</h2>
                        <p class="lead mb-5">{{ __('Just open the camera on your phone and scan the ') }}<span class="font-weight-bolder">{{ __('QR code') }}</span> {{ __('bellow') }}!</p>
                        <a href="#" class="icon icon-lg text-gray mr-3">
                            <img style="width:300px" src="{{ asset('impactfront') }}/img/qrdemo.jpg" />
                            
                        </a>
                       
                    </div>
                    <div class="col-12 text-center">
                        <!-- Button Modal -->
                        <a href="{{ route('newrestaurant.register') }}" class="btn btn-secondary animate-up-2"><span class="mr-2"><i class="fas fa-hand-pointer"></i></span>{{ __('Create menu for you, now!') }}</a>
                    </div>
                </div> 
            </div>    
        </section>
    

   
       
       
        
       
        <footer class="footer section  pb-3 pt-1 bg-primary text-white overflow-hidden">
            
            <div class="container">
                
                <hr class="my-4 my-lg-5">
                <div class="row">
                    <div class="col pb-4 mb-md-0">
                        <div class="d-flex text-center justify-content-center align-items-center">
                            <p class="font-weight-normal mb-0">© {{  env('APP_NAME') }} <span class="current-year"></span>. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </main>

    <!-- Core -->
    <script src="{{ asset('impactfront') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/headroom.js/dist/headroom.min.js"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('impactfront') }}/vendor/onscreen/dist/on-screen.umd.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Impact JS -->
    <script src="{{ asset('impactfront') }}/js/front.js"></script>

    
</body>

</html>