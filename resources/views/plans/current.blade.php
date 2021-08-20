@extends('layouts.app', ['title' => __('Pages')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>


    <div class="container-fluid mt--7">
        
        <div class="row">

            <div class="col-12">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>

            @foreach ($plans as $plan)
            <div class="col-md-{{ $col}}">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $plan['name'] }}</h3>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0">@money($plan['price'], env('CASHIER_CURRENCY','usd'),true)/{{ $plan['period']==1?__('m'):__('y') }}</h3>
                            </div>
                            
                        </div>
                    </div>

                    
                    @if(count($plans))
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Features') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (explode(",",$plan['features']) as $feature)
                                    <tr>
                                        <td>{{ $feature }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       
                        
                    </div>
                    @endif
                    <div class="card-footer py-4">
                        @if($plan['id'].""==$currentPlan->id."")
                            <a href="" class="btn btn-primary disabled">{{__('Current Plan')}}</a>
                        @else
                            @if(strlen($plan['paddle_id'])>2)
                            <a href="javascript:openCheckout({{ $plan['paddle_id'] }})" class="btn btn-primary">{{__('Switch to ').$plan['name']}}</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            
        </div>

        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card bg-secondary shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Your current plan') }}</h3>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ __('You are currently using the ').$currentPlan->name." ".__('plan') }}<p>
                            @if(strlen(auth()->user()->plan_status)>0)
                            <p>{{ __('Status').": "}} <strong>{{ auth()->user()->plan_status }}</strong><p>
                            @endif
                    </div>
                    @if(strlen(auth()->user()->cancel_url)>5)
                    <div class="card-footer py-4">
                        <a href="{{ auth()->user()->update_url }}" target="_blank" class="btn btn-warning">{{__('Update subscription')}}</a>
                        <a href="{{ auth()->user()->cancel_url }}" target="_blank" class="btn btn-danger">{{__('Cancel subscription')}}</a>
                    </div>
                    @endif
                </div>
                
            </div>
            
        </div>


        @include('layouts.footers.auth')
    </div>
@endsection
@section('js')
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script type="text/javascript">
    var paddleVendorID="{{ env('paddleVendorID','')}}";
    var currentUserEmail="{{ auth()->user()->email }}";
    Paddle.Setup({ vendor: paddleVendorID  });
	function openCheckout(product_id) {
		var form = document.getElementById('pre-checkout');
		Paddle.Checkout.open({
			product: product_id,
			email: currentUserEmail
		});
	}
</script>
@endsection
