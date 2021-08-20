@extends('layouts.app', ['title' => __($title)])


@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8 mt--4">
       @isset($breadcrumbs)
           @include('general.breadcrumbs')
       @endisset
    </div>





    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __($title) }}</h3>
                            </div>
                            @isset($action_link)
                                <div class="col-4 text-right">
                                    <a href="{{ $action_link }}" class="btn btn-sm btn-primary">{{ __($action_name) }}</a>
                                </div>
                            @endisset

                        </div>
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>

                   @if (isset($iscontent))
                       <div class="card-body">
                            @yield('cardbody')
                       </div>
                   @else
                    @if(count($items))
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    @yield('thead')
                                </thead>
                                <tbody>
                                    @yield('tbody')
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="card-footer py-4">
                        @if(count($items))
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $items->links() }}
                            </nav>
                        @else
                            <h4>{{ __("There are no")." ".$item_names }} ...</h4>
                        @endif
                    </div>
                   @endif


                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
