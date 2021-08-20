$(document).ready(function() {
    /*$(".order_id").click(function(e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'GET',
            url: '/orders/'+$(this).text(),
            dataType: 'json',
            success:function(response){
                if(response.status){
                    //response.data.order_status_last;

                    $("#status-history").empty();
                    $("#order-items").empty();

                    $("#modal-title-order").html("#"+response.data.order_id+" - "+response.data.order_created_at);
                    $("#restorant-name").html(response.data.order_restorant.name);
                    $("#restorant-address").html(response.data.order_restorant.address);
                    $("#restorant-info").html(response.data.order_restorant_owner.name+", "+response.data.order_restorant_owner.email);
                    $("#client-name").html(response.data.order_client.name);
                    $("#client-info").html(response.data.order_client.email);
                    $("#delivery-price").html("Delivery: "+response.data.order_delivery_price+" $");
                    $("#total-price").html(response.data.order_total_price+" $");

                    var orderStatuses = response.data.order_status;
                    var userNames = response.data.order_status_usernames;
                    var statusTimes = response.data.order_status_times;
                    var orderItems = response.data.order_items;

                    Object.keys(orderItems).map((key)=>{
                        $("#order-items").append('<li>'+orderItems[key].count+'x - '+orderItems[key].name+' - '+orderItems[key].price+' $</li>');
                    })

                    Object.keys(orderStatuses).map((key, index)=>{
                        $("#status-history").append('<div class="timeline-block"><br/><span class="timeline-step badge-success"><i class="ni ni-bell-55"></i></span><div class="timeline-content"><div class="d-flex justify-content-between pt-1"><div><span class="text-muted text-sm font-weight-bold">'+orderStatuses[key].name+'</span></div><div class="text-right"><small class="text-muted"><i class="fas fa-clock mr-1"></i>'+statusTimes[index]+'</small></div></div><h6 class="text-sm mt-1 mb-0">Status from: '+userNames[index]+'</h6></div></div>');
                    })

                    //$('#modal-order-details').modal('show');
                }

            }, error: function (response) {
               alert(response.responseJSON.errMsg);
            }
        })
    });*/

    $("#show-hide-filters").click(function(){

        if($(".orders-filters").is(":visible")){
            $("#button-filters").removeClass("ni ni-bold-up")
            $("#button-filters").addClass("ni ni-bold-down")
        }else if($(".orders-filters").is(":hidden")){
            $("#button-filters").removeClass("ni ni-bold-down")
            $("#button-filters").addClass("ni ni-bold-up")
        }

        $(".orders-filters").slideToggle();
    });
});
