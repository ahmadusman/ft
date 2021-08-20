<thead class="thead-light">
    <tr>
        <th scope="col">{{ __('ID') }}</th>
        @hasrole('admin|driver')
            <th scope="col">{{ __('Restaurant') }}</th>
        @endhasrole
        <th class="table-web" scope="col">{{ __('Created') }}</th>
        <th class="table-web" scope="col">{{ __('Time Slot') }}</th>
        <th class="table-web" scope="col">{{ __('Method') }}</th>
        <th scope="col">{{ __('Last status') }}</th>
        @hasrole('admin|owner|driver')
            <th class="table-web" scope="col">{{ __('Client') }}</th>
        @endhasrole
        @role('admin')
            <th class="table-web" scope="col">{{ __('Address') }}</th>
        @endrole
        @role('owner')
            <th class="table-web" scope="col">{{ __('Items') }}</th>
        @endrole
        @hasrole('admin|owner')
            <th class="table-web" scope="col">{{ __('Driver') }}</th>
        @endhasrole
        <th class="table-web" scope="col">{{ __('Price') }}</th>
        <th class="table-web" scope="col">{{ __('Delivery') }}</th>
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner') || auth()->user()->hasRole('driver'))
            <th scope="col">{{ __('Actions') }}</th>
        @endif
    </tr>
</thead>
<tbody>
@foreach($orders as $order)
<tr>
    <td>
        
        <a class="btn badge badge-success badge-pill" href="{{ route('orders.show',$order->id )}}">#{{ $order->id }}</a>
    </td>
    @hasrole('admin|driver')
    <th scope="row">
        <div class="media align-items-center">
            <a class="avatar-custom mr-3">
                <img class="rounded" alt="..." src={{ $order->restorant->icon }}>
            </a>
            <div class="media-body">
                <span class="mb-0 text-sm">{{ $order->restorant->name }}</span>
            </div>
        </div>
    </th>
    @endhasrole

    <td class="table-web">
        {{ $order->created_at->format(env('DATETIME_DISPLAY_FORMAT','d M Y H:i')) }}
    </td>
    <td class="table-web">
        {{ $order->time_formated }}
    </td>
    <td class="table-web">
        @if ($order->delivery_method==1)
            <span class="badge badge-primary badge-pill">{{ __('Delivery') }}</span>
        @else
            <span class="badge badge-success badge-pill">{{ __('Pickup') }}</span>
        @endif

    </td>
    <td>
        @if($order->status->pluck('id')->last() == "1")
            <span class="badge badge-primary badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "2" || $order->status->pluck('id')->last() == "3")
            <span class="badge badge-success badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "4")
            <span class="badge badge-default badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "5")
            <span class="badge badge-warning badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "6")
            <span class="badge badge-success badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "7")
            <span class="badge badge-info badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @elseif($order->status->pluck('id')->last() == "8" || $order->status->pluck('id')->last() == "9")
            <span class="badge badge-danger badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
        @endif
    </td>
    @hasrole('admin|owner|driver')
    <td class="table-web">
       {{ $order->client->name }}
    </td>
    @endhasrole
    @role('admin')
        <td class="table-web">
            {{ $order->address?$order->address->address:"" }}
        </td>
    @endrole
    @role('owner')
        <td class="table-web">
            {{ count($order->items) }}
        </td>
    @endrole
    @hasrole('admin|owner')
        <td class="table-web">
            {{ !empty($order->driver->name) ? $order->driver->name : "" }}
        </td>
    @endhasrole
    <td class="table-web">
        @money( $order->order_price, env('CASHIER_CURRENCY','usd'),true)

    </td>
    <td class="table-web">
        @money( $order->delivery_price, env('CASHIER_CURRENCY','usd'),true)
    </td>
    @include('orders.partials.actions.table',['order' => $order ])
</tr>
@endforeach
</tbody>
