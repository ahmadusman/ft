@extends('layouts.app', ['title' => __('Orders')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-7 ">
                <br/>
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ "#".$order->id." - ".$order->created_at->format(env('DATETIME_DISPLAY_FORMAT','d M Y H:i')) }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">{{ __('Back') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       <h6 class="heading-small text-muted mb-4">{{ __('Restaurant information') }}</h6>
                        @include('partials.flash')
                        <div class="pl-lg-4">
                            <h3>{{ $order->restorant->name }}</h3>
                            <h4>{{ $order->restorant->address }}</h4>
                            <h4>{{ $order->restorant->phone }}</h4>
                            <h4>{{ $order->restorant->user->name.", ".$order->restorant->user->email }}</h4>
                        </div>
                        <hr class="my-4" />
                        <h6 class="heading-small text-muted mb-4">{{ __('Client Information') }}</h6>
                        <div class="pl-lg-4">
                            <h3>{{ $order->client->name }}</h3>
                            <h4>{{ $order->client->email }}</h4>
                            <h4>{{ $order->address?$order->address->address:"" }}</h4>

                            @if(!empty($order->address->apartment))
                                <h4>{{ __("Apartment number") }}: {{ $order->address->apartment }}</h4>
                            @endif
                            @if(!empty($order->address->entry))
                                <h4>{{ __("Entry number") }}: {{ $order->address->entry }}</h4>
                            @endif
                            @if(!empty($order->address->floor))
                                <h4>{{ __("Floor") }}: {{ $order->address->floor }}</h4>
                            @endif
                            @if(!empty($order->address->intercom))
                                <h4>{{ __("Intercom") }}: {{ $order->address->intercom }}</h4>
                            @endif
                            @if(!empty($order->client->phone))
                            <br/>
                            <h4>{{ __('Contact')}}: {{ $order->client->phone }}</h4>
                            @endif
                        </div>
                        <hr class="my-4" />
                        <h6 class="heading-small text-muted mb-4">{{ __('Order') }}</h6>
                        <ul id="order-items">
                            @foreach($order->items as $item)
                                <li><h4>{{ $item->pivot->qty." X ".$item->name }} -  @money( ($item->pivot->variant_price?$item->pivot->variant_price:$item->price), env('CASHIER_CURRENCY','usd'),true)  =  ( @money( $item->pivot->qty*($item->pivot->variant_price?$item->pivot->variant_price:$item->price), env('CASHIER_CURRENCY','usd'),true)
                                    @hasrole('admin|driver|owner')
                                        @if($item->pivot->vatvalue>0))
                                        <span class="small">-- {{ __('VAT ').$item->pivot->vat."%: "}} ( @money( $item->pivot->vatvalue, env('CASHIER_CURRENCY','usd'),true) )</span>
                                        @endif
                                    @endhasrole
                                </h4>
                                    @if (strlen($item->pivot->variant_name)>2)
                                        <br />
                                        <table class="table align-items-center">
                                            <thead class="thead-light">
                                                <tr>
                                                    @foreach ($item->options as $option)
                                                        <th>{{ $option->name }}</th>
                                                    @endforeach
                                                    
                                                    
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <tr>
                                                    @foreach (explode(",",$item->pivot->variant_name) as $optionValue)
                                                        <td>{{ $optionValue }}</td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                    
                                    @if (strlen($item->pivot->extras)>2)
                                        <br /><span>{{ __('Extras') }}</span><br />
                                        <ul>
                                            @foreach(json_decode($item->pivot->extras) as $extra)
                                                <li> {{  $extra }}</li>
                                            @endforeach
                                        </ul><br />
                                    @endif
                                    <br />
                                </li>
                            @endforeach
                        </ul>
                        @if(!empty($order->comment))
                        <br/>
                        <h4>{{ __('Comment') }}: {{ $order->comment }}</h4>
                        @endif
                        @if(!empty($order->time_to_prepare))
                        <br/>
                        <h4>{{ __('Time to prepare') }}: {{ $order->time_to_prepare ." " .__('minutes')}}</h4>
                        <br/>
                        @endif
                        @hasrole('admin|driver|owner')
                        <h5>{{ __("NET") }}: @money( $order->order_price-$order->vatvalue, env('CASHIER_CURRENCY','usd'),true)</h5>
                        <h5>{{ __("VAT") }}: @money( $order->vatvalue, env('CASHIER_CURRENCY','usd'),true)</h5>

                        @endhasrole
                        <h4>{{ __("Sub Total") }}: @money( $order->order_price, env('CASHIER_CURRENCY','usd'),true)</h4>
                        <h4>{{ __("Delivery") }}: @money( $order->delivery_price, env('CASHIER_CURRENCY','usd'),true)</h4>
                        <hr />
                        <h3>{{ __("TOTAL") }}: @money( $order->delivery_price+$order->order_price, env('CASHIER_CURRENCY','usd'),true)</h3>
                        <hr />
                        <h4>{{ __("Payment method") }}: {{ __(strtoupper($order->payment_method)) }}</h4>
                        <h4>{{ __("Payment status") }}: {{ __(ucfirst($order->payment_status)) }}</h4>
                        <hr />
                        <h4>{{ __("Delivery method") }}: {{ $order->delivery_method==1?__('Delivery'):__('Pickup') }}</h4>
                        <h3>{{ __("Time slot") }}: @include('orders.partials.time', ['time'=>$order->time_formated])</h3>



                    </div>
                   @include('orders.partials.actions.buttons',['order'=>$order])
                </div>
            </div>
            <div class="col-xl-5  mb-5 mb-xl-0">
                <br/>
                <div class="card card-profile shadow">
                    <div class="card-header">
                        <h5 class="h3 mb-0">{{ __("Order tracking")}}</h5>
                    </div>
                    <div class="card-body">
                        @include('orders.partials.map',['order'=>$order])
                    </div>
                </div>
                <br/>
                <div class="card card-profile shadow">
                    <div class="card-header">
                        <h5 class="h3 mb-0">{{ __("Status History")}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-one-side" id="status-history" data-timeline-content="axis" data-timeline-axis-style="dashed">
                        @foreach($order->status as $key=>$value)
                            <div class="timeline-block">
                                <span class="timeline-step badge-success">
                                    <i class="ni ni-bell-55"></i>
                                </span>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between pt-1">
                                        <div>
                                            <span class="text-muted text-sm font-weight-bold">{{ __($value->name) }}</span>
                                        </div>
                                        <div class="text-right">
                                            <small class="text-muted"><i class="fas fa-clock mr-1"></i>{{ $value->pivot->created_at->format(env('DATETIME_DISPLAY_FORMAT','d M Y H:i')) }}</small>
                                        </div>
                                    </div>
                                    <h6 class="text-sm mt-1 mb-0">{{ __('Status from') }}: {{$userNames[$key] }}</h6>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
                @if($order->status->pluck('alias')->last() == "delivered")
                    <br/>
                    @include('orders.partials.rating',['order'=>$order])
                @endif
            </div>
        </div>
        @include('layouts.footers.auth')
        @include('orders.partials.modals',['order'=>$order])
    </div>
@endsection

@section('head')
    <link type="text/css" href="{{ asset('custom') }}/css/rating.css" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('custom') }}/js/ratings.js"></script>
@endsection

