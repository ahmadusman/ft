@extends('layouts.app')
@section('admin_title')
    {{__('Dashboard')}}
@endsection

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card bg-gradient-default shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-light ls-1 mb-1">{{ __('Overview') }}</h6>
                                <h2 class="text-white mb-0">{{ __('Sales value') }}</h2>
                            </div>

                        </div>
                    </div>
                    <script>
                        //var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug', 'Sep','Oct', 'Nov', 'Dec'];
                        var months = {!! json_encode($months) !!};
                        function monthNumToName(monthnum) {return months[monthnum - 1] || ''}

                        var monthLabels = {!! json_encode($monthLabels) !!};
                        var salesValue= {!! json_encode($salesValue) !!};
                        var totalOrders = {!! json_encode($totalOrders) !!};

                        for(var i=0; i<monthLabels.length; i++){monthLabels[i]=monthNumToName(monthLabels[i])}
                    </script>
                    <div class="card-body">
                        <!-- Chart -->
                        @if(!$salesValue->isEmpty())
                            <div class="chart">
                                <!-- Chart wrapper -->
                                <canvas id="chart-sales" class="chart-canvas"></canvas>
                            </div>
                        @else
                            <p class="text-white">{{ __('No sales right now!') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-uppercase text-muted ls-1 mb-1">{{ __('Performance') }}</h6>
                                <h2 class="mb-0">{{ __('Total orders') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        @if(!$totalOrders->isEmpty())
                            <div class="chart">
                                <canvas id="chart-orders" class="chart-canvas"></canvas>
                            </div>
                        @else
                            <p>{{ __('No orders right now!') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
