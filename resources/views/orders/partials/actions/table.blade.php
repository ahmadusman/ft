@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner') || auth()->user()->hasRole('driver'))
    @role('admin')
    <script>
        function setSelectedOrderId(id){
            $("#form-assing-driver").attr("action", "updatestatus/assigned_to_driver/"+id);
        }
    </script>
    <td>
        @if($order->status->pluck('alias')->last() == "just_created")
            <a href="{{'updatestatus/accepted_by_admin/'.$order->id }}" class="btn btn-success btn-sm order-action">{{ __('Accept') }}</a>
            <a href="{{'updatestatus/rejected_by_admin/'.$order->id }}" class="btn btn-danger btn-sm order-action">{{ __('Reject') }}</a>
        @elseif($order->status->pluck('alias')->last() == "accepted_by_restaurant"&&$order->delivery_method.""!="2")
            <button type="button" class="btn btn-primary btn-sm order-action" onClick=(setSelectedOrderId({{ $order->id }}))  data-toggle="modal" data-target="#modal-asign-driver">{{ __('Assign to driver') }}</a>
        @elseif($order->status->pluck('alias')->last() == "prepared"&&$order->driver==null)
            <button type="button" class="btn btn-primary btn-sm order-action" onClick=(setSelectedOrderId({{ $order->id }}))  data-toggle="modal" data-target="#modal-asign-driver">{{ __('Assign to driver') }}</a>
        @else
            <small>{{ __('No actions for you right now!') }}</small>
        @endif
    </td>
    @endrole
    @role('owner')
    <td>
        @if($order->status->pluck('alias')->last() == "accepted_by_admin")
            <a href="{{ url('updatestatus/accepted_by_restaurant/'.$order->id) }}" class="btn btn-success btn-sm order-action">{{ __('Accept') }}</a>
            <a href="{{ url('updatestatus/rejected_by_restaurant/'.$order->id) }}" class="btn btn-danger btn-sm order-action">{{ __('Reject') }}</a>
        @elseif($order->status->pluck('alias')->last() == "assigned_to_driver"||$order->status->pluck('alias')->last() == "accepted_by_restaurant")
            <a href="{{ url('updatestatus/prepared/'.$order->id) }}" class="btn btn-primary btn-sm order-action">{{ __('Prepared') }}</a>
        @elseif(config('app.allow_self_deliver')&&$order->status->pluck('alias')->last() == "accepted_by_restaurant")
            <a href="{{ url('updatestatus/prepared/'.$order->id) }}" class="btn btn-primary btn-sm order-action">{{ __('Prepared') }}</a>
        @elseif(config('app.allow_self_deliver')&&$order->status->pluck('alias')->last() == "prepared")
            <a href="{{ url('updatestatus/delivered/'.$order->id) }}" class="btn btn-primary btn-sm order-action">{{ __('Delivered') }}</a>
        @elseif($order->status->pluck('alias')->last() == "prepared"&&$order->delivery_method.""=="2")
            <a href="{{ url('updatestatus/delivered/'.$order->id) }}" class="btn btn-primary btn-sm order-action">{{ __('Delivered') }}</a>
        @else
            <small>{{ __('No actions for you right now!') }}</small>
        @endif
    </td>
    @endrole
    @role('driver')
    <td>
       @if($order->status->pluck('alias')->last() == "prepared")
            <a href="{{'updatestatus/picked_up/'.$order->id }}" class="btn btn-primary btn-sm order-action">{{ __('Picked Up') }}</a>
        @elseif($order->status->pluck('alias')->last() == "picked_up")
            <a href="{{'updatestatus/delivered/'.$order->id }}" class="btn btn-primary btn-sm order-action">{{ __('Delivered') }}</a>
        @else
            <small>{{ __('No actions for you right now!') }}</small>
        @endif
    </td>
    @endrole
@endif
