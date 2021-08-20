<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="/">
            <img src="{{ config('global.site_logo') }}" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            
                            
                            <img alt="..." src="{{'https://www.gravatar.com/avatar/'.auth()->user()->email }}">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ config('global.site_logo') }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            @role('admin')
                @include('layouts.navbars.menus.admin')
            @else
                <span></span>
            @endrole

            @role('driver')
                @include('layouts.navbars.menus.driver')
            @else
                <span></span>
            @endrole

            @role('owner')
                @include('layouts.navbars.menus.owner')
            @else
                <span></span>
            @endrole

            @role('client')
                @include('layouts.navbars.menus.client')
            @else
                <span></span>
            @endrole

            @if(env('RESTOLOYALTY_TOKEN',null))
            @role('admin')
                <hr class="my-3">
                <h6 class="navbar-heading text-muted">{{ __('External plugins')}}</h6>
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link" href="https://app.restoloyalty.com/sso/{{ env('RESTOLOYALTY_TOKEN','') }}">
                            <i class="ni ni-credit-card text-info"></i> {{ __('Loyalty Platform') }}
                        </a>
                    </li>
                </ul>
            @endrole
            @endif

            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            @role('admin')
                <h6 class="navbar-heading text-muted">{{ __('Version')}} {{ config('app.version')}}</h6>
                <h6>{{ \Carbon\Carbon::now() }} </h6>
            @endrole
            
        </div>
    </div>
</nav>
