// Initialize and add the map
/*function initMap() {
    // The location of Uluru
    //var uluru = {lat: 41.1231, lng: 20.8016};
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }


    // The map, centered at Uluru
    /*var map = new google.maps.Map(
        document.getElementById('map'), {zoom: 13, center: uluru});
    // The marker, positioned at Uluru
    var marker = new google.maps.Marker({position: uluru, map: map});

    var infoWindow = new google.maps.InfoWindow({
        content: '<h1>Restorant</h1>'
    })

    marker.addListener('click', function(){
        infoWindow.open(map, marker);
    })
}*/
var map, infoWindow, marker, lng, lat;
function initMap() {

    /*if(window.location.toString().includes("restorants")){
        initMapR();
    }else if(window.location.toString().includes("addresses")){
        initMapA();
    }*/


    /*var infoWindow = new google.maps.InfoWindow({
        content: '<h1>Restorant</h1>'
    })

    marker.addListener('click', function(){
        infoWindow.open(map, marker);
    })*/
}

function initMapR(){
    map = new google.maps.Map(document.getElementById('map'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
    marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
    infoWindow = new google.maps.InfoWindow;

    getLocation(function(isFetched, currPost){
        if(isFetched){
            if(currPost.lat != 0 && currPost.lng != 0){
                map.setCenter(currPost);
                marker.setPosition(currPost);
            }else{
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                      var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                      //infoWindow.setPosition(pos);
                      //infoWindow.setContent('Location found.');
                      //infoWindow.open(map);
                      map.setCenter(pos);
                      marker.setPosition(pos);
                      changeLocation(pos.lat, pos.lng);
                    }, function() {
                     // handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    // Browser doesn't support Geolocation
                    //handleLocationError(false, infoWindow, map.getCenter());
                }
            }
        }
    });

    map.addListener('click', function(event) {
        var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
        marker.setPosition(currPos);
        changeLocation(event.latLng.lat(), event.latLng.lng());
    });
}

function getLocation(callback){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'GET',
        url: '/get/rlocation/'+$('#rid').val(),
        success:function(response){
            if(response.status){
                return callback(true, response.data)
            }
        }, error: function (response) {
           return callback(false, response.responseJSON.errMsg);
        }
    })
}

function changeLocation(lat, lng){
    //var latConv = parseFloat(lat.toString().substr(0, 5));
    //var lngConv = parseFloat(lng.toString().substr(0, 5));
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'POST',
        url: '/updateres/location/'+$('#rid').val(),
        dataType: 'json',
        data: {
            lat: lat,
            lng: lng
        },
        success:function(response){
            if(response.status){
                console.log(response.status)
            }
        }, error: function (response) {
           //alert(response.responseJSON.errMsg);
        }
    })
}

function initMapA() {
    map = new google.maps.Map(document.getElementById('map2'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
    marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
    infoWindow = new google.maps.InfoWindow;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
        var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

            map.setCenter(pos);
            marker.setPosition(pos);
            //changeLocation(pos.lat, pos.lng);
            lat = position.coords.latitude;
            lng = position.coords.longitude;
        }, function() {
                     // handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
                    // Browser doesn't support Geolocation
                    //handleLocationError(false, infoWindow, map.getCenter());
    }

    map.addListener('click', function(event) {
        var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
        marker.setPosition(currPos);
        //changeLocation(event.latLng.lat(), event.latLng.lng());

        lat = event.latLng.lat()
        lng = event.latLng.lng();
    });
}

$("#submitNewAddress").click(function() {
    saveLocation(lat, lng);
});

function saveLocation(lat, lng){
    var new_address = $('#new_address').val();

    if(new_address.length > 0){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            url: '/addresses',
            dataType: 'json',
            data: {
                new_address: new_address,
                lat: lat,
                lng: lng
            },
            success:function(response){
                if(response.status){
                    window.location.href = "/addresses";
                }
            }, error: function (response) {
               //alert(response.responseJSON.errMsg);
            }
        })
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}

