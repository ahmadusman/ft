@extends('layouts.app', ['title' => __('Orders')])
@section('admin_title')
    {{__('Restaurant Management')}}
@endsection
@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-6">
                <br/>
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Restaurant Management') }}</h3>
                                @if (env('WILDCARD_DOMAIN_READY'))
                                    <span class="blockquote-footer">{{ (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$restorant->subdomain.".".$_SERVER['HTTP_HOST'] }}</span>
                                @endif
                            </div>
                            <div class="col-4 text-right">
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('restorants.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                @endif
                                @if (env('WILDCARD_DOMAIN_READY'))
                                    <a target="_blank" href="{{ (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$restorant->subdomain.".".$_SERVER['HTTP_HOST'] }}" class="btn btn-sm btn-success">{{ __('View it') }}</a>
                                @else
                                    <a target="_blank" href="{{ route('vendor',$restorant->subdomain) }}" class="btn btn-sm btn-success">{{ __('View it') }}</a>
                                @endif

                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                       <h6 class="heading-small text-muted mb-4">{{ __('Restaurant information') }}</h6>
                        @include('partials.flash')
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('restorants.update', $restorant) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                    <input type="hidden" id="rid" value="{{ $restorant->id }}"/>
                                    @include('partials.fields',['fields'=>[
                                         ['ftype'=>'input','name'=>"Restaurant Name",'id'=>"name",'placeholder'=>"Restaurant Name",'required'=>true,'value'=>$restorant->name],
                                         ['ftype'=>'input','name'=>"Restaurant Description",'id'=>"description",'placeholder'=>"Restaurant description",'required'=>true,'value'=>$restorant->description],
                                         ['ftype'=>'input','name'=>"Restaurant Address",'id'=>"address",'placeholder'=>"Restaurant description",'required'=>true,'value'=>$restorant->address],
                                    ]])

                                    @if (env('MULTI_CITY',false))
                                        @include('partials.fields',['fields'=>[
                                            ['ftype'=>'select','name'=>"Restaurant city",'id'=>"city_id",'data'=>$cities,'required'=>true,'value'=>$restorant->city_id],

                                    ]])
                                    @endif



                                    <div class="form-group{{ $errors->has('minimum') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="input-description">{{ __('Minimum order') }}</label>
                                        <input type="number" name="minimum" id="input-minimum" class="form-control form-control-alternative{{ $errors->has('minimum') ? ' is-invalid' : '' }}" placeholder="{{ __('Enter Minimum order value') }}" value="{{ old('minimum', $restorant->minimum) }}" required autofocus>
                                        @if ($errors->has('minimum'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('minimum') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    @if(auth()->user()->hasRole('admin'))
                                        <br/>
                                        <div class="row">
                                            <div class="col-6 form-group{{ $errors->has('fee') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-description">{{ __('Fee percent') }}</label>
                                                <input type="number" name="fee" id="input-fee" step="any" min="0" max="100" class="form-control form-control-alternative{{ $errors->has('fee') ? ' is-invalid' : '' }}" value="{{ old('fee', $restorant->fee) }}" required autofocus>
                                                @if ($errors->has('fee'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fee') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="col-6 form-group{{ $errors->has('static_fee') ? ' has-danger' : '' }}">
                                                <label class="form-control-label" for="input-description">{{ __('Static fee') }}</label>
                                                <input type="number" name="static_fee" id="input-fee" step="any" min="0" max="100" class="form-control form-control-alternative{{ $errors->has('static_fee') ? ' is-invalid' : '' }}" value="{{ old('static_fee', $restorant->static_fee) }}" required autofocus>
                                                @if ($errors->has('static_fee'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('static_fee') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="form-group">
                                            <label class="form-control-label" for="item_price">{{ __('Is Featured') }}</label>
                                            <label class="custom-toggle" style="float: right">
                                                <input type="checkbox" name="is_featured" <?php if($restorant->is_featured == 1){echo "checked";}?>>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                        <br/>
                                    @endif
                                    <div class="row">
                                        <?php
                                            $images=[
                                                ['name'=>'resto_logo','label'=>__('Restaurant Image'),'value'=>$restorant->logom,'style'=>'width: 295px; height: 200px;'],
                                                ['name'=>'resto_cover','label'=>__('Restaurant Cover Image'),'value'=>$restorant->coverm,'style'=>'width: 200px; height: 100px;']
                                            ]
                                        ?>
                                        @foreach ($images as $image)
                                            <div class="col-md-6">
                                                @include('partials.images',$image)
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                            <hr />
                            <h6 class="heading-small text-muted mb-4">{{ __('Owner information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name_owner">{{ __('Owner Name') }}</label>
                                    <input type="text" name="name_owner" id="name_owner" class="form-control form-control-alternative" placeholder="{{ __('Owner Name') }}" value="{{ old('name', $restorant->user->name) }}" readonly>
                                </div>
                                <div class="form-group{{ $errors->has('email_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="email_owner">{{ __('Owner Email') }}</label>
                                    <input type="text" name="email_owner" id="email_owner" class="form-control form-control-alternative" placeholder="{{ __('Owner Email') }}" value="{{ old('name', $restorant->user->email) }}" readonly>
                                </div>
                                <div class="form-group{{ $errors->has('phone_owner') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone_owner">{{ __('Owner Phone') }}</label>
                                    <input type="text" name="phone_owner" id="phone_owner" class="form-control form-control-alternative" placeholder="{{ __('Owner Phone') }}" value="{{ old('name', $restorant->user->phone) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <br/>
                    <div class="card card-profile shadow">
                        <div class="card-header">
                            <h5 class="h3 mb-0">{{ __("Restaurant Location")}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="nav-wrapper">
                                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">{{ __('Location') }}</a>
                                    </li>
                                    @if (!env('DISABLE_DELIVER',false))
                                        <li class="nav-item">
                                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{ __('Delivery Area') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                            <div id="map_location" class="form-control form-control-alternative"></div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                            <div id="map_area" class="form-control form-control-alternative"></div>
                                            <br/>
                                            <button type="button" id="clear_area" class="btn btn-danger btn-sm btn-block">{{ __("Clear Delivery Area")}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                <div class="card card-profile bg-secondary shadow">
                    <div class="card-header">
                        <h5 class="h3 mb-0">{{ __("Working Hours")}}</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('restaurant.workinghours') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="rid" name="rid" value="{{ $restorant->id }}"/>
                            <div class="form-group">
                                @foreach($days as $key => $value)
                                    <br/>
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="days" class="custom-control-input" id="{{ 'day'.$key }}" value={{ $key }}>
                                                <label class="custom-control-label" for="{{ 'day'.$key }}">{{ __($value) }}</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                                                </div>
                                                <input id="{{ $key.'_from' }}" name="{{ $key.'_from' }}" class="flatpickr datetimepicker form-control" type="text" placeholder="{{ __('Time') }}">
                                            </div>
                                        </div>
                                        <div class="col-2 text-center">
                                            <p class="display-4">-</p>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                                                </div>
                                                <input id="{{ $key.'_to' }}" name="{{ $key.'_to' }}" class="flatpickr datetimepicker form-control" type="text" placeholder="{{ __('Time') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var defaultHourFrom = "09:00";
        var defaultHourTo = "17:00";

        var timeFormat = '{{ env('TIME_FORMAT','24hours') }}';

        function formatAMPM(date) {
            //var hours = date.getHours();
            //var minutes = date.getMinutes();
            var hours = date.split(':')[0];
            var minutes = date.split(':')[1];

            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            //minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return strTime;
        }

        //console.log(formatAMPM("19:05"));

        var config = {
            enableTime: true,
            dateFormat: timeFormat == "AM/PM" ? "h:i K": "H:i",
            noCalendar: true,
            altFormat: timeFormat == "AM/PM" ? "h:i K" : "H:i",
            altInput: true,
            allowInput: true,
            time_24hr: timeFormat == "AM/PM" ? false : true,
            onChange: [
                function(selectedDates, dateStr, instance){
                    //...
                    this._selDateStr = dateStr;
                },
            ],
            onClose: [
                function(selDates, dateStr, instance){
                    if (this.config.allowInput && this._input.value && this._input.value !== this._selDateStr) {
                        this.setDate(this.altInput.value, false);
                    }
                }
            ]
        };

        $("input[type='checkbox'][name='days']").change(function() {
            /*if(this.checked) {
                var returnVal = confirm("Are you sure?");
                $(this).prop("checked", returnVal);
            }
            $('#textbox1').val(this.checked);*/

            var hourFrom = flatpickr($('#'+ this.value + '_from'), config);
            var hourTo = flatpickr($('#'+ this.value + '_to'), config);

            if(this.checked){
                hourFrom.setDate(timeFormat == "AP/PM" ? formatAMPM(defaultHourFrom) : defaultHourFrom, false);
                hourTo.setDate(timeFormat == "AP/PM" ? formatAMPM(defaultHourTo) : defaultHourTo, false);
            }else{
                hourFrom.clear();
                hourTo.clear();
            }
        });

        $('input:radio[name="primer"]').change(function(){
            if($(this).val() == 'map') {
                $("#clear_area").hide();
            }else if($(this).val() == 'area' && isClosed){
                $("#clear_area").show();
            }
        });

        $("#clear_area").click(function() {
            //remove markers
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }

            //remove polygon
            poly.setMap(null);
            poly.setPath([]);

            poly = new google.maps.Polyline({ map: map_area, path: [], strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });

            path = poly.getPath();

            //update delivery path
            changeDeliveryArea(path)

            isClosed = false;
            $("#clear_area").hide();
        });

        //Initialize working hours
        function initializeWorkingHours(){
            var workingHours = {!! json_encode($hours) !!};
            if(workingHours != null){
                Object.keys(workingHours).map((key, index)=>{
                    if(workingHours[key] != null){
                        var hour = flatpickr($('#'+key), config);
                        hour.setDate(workingHours[key], false);

                        var day_key = key.split('_')[0];
                        $('#day'+day_key).attr('checked', 'checked');
                    }
                })
            }
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

        function changeDeliveryArea(path){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: '/updateres/delivery/'+$('#rid').val(),
                dataType: 'json',
                data: {
                    path: JSON.stringify(path.i)
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

        function initializeMap(lat, lng){
            var map_options = {
                zoom: 13,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: "terrain",
                scaleControl: true
            }

            map_location = new google.maps.Map( document.getElementById("map_location"), map_options );
            map_area = new google.maps.Map( document.getElementById("map_area"), map_options );
        }

        function initializeMarker(lat, lng){
            var markerData = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                position: markerData,
                map: map_location,
                icon: start
            });
        }

        function new_delivery_area(latLng){
            if (isClosed) return;
            markerIndex = poly.getPath().length;
            var isFirstMarker = markerIndex === 0;
            markerArea = new google.maps.Marker({ map: map_area, position: latLng, draggable: false, icon: area });

            //push markers
            markers.push(markerArea);

            if(isFirstMarker) {
                google.maps.event.addListener(markerArea, 'click', function () {
                    if (isClosed) return;
                    path = poly.getPath();
                    poly.setMap(null);
                    poly = new google.maps.Polygon({ map: map_area, path: path, strokeColor: "#FF0000", strokeOpacity: 0.8, strokeWeight: 2, fillColor: "#FF0000", fillOpacity: 0.35, editable: false });
                    isClosed = true;

                    //update delivery path
                    changeDeliveryArea(path)
                    //show button clear
                    //$("#clear_area").show();
                });
            }
            //show button clear
            $("#clear_area").show();

            google.maps.event.addListener(markerArea, 'drag', function (dragEvent) {
                poly.getPath().setAt(markerIndex, dragEvent.latLng);
            });
            poly.getPath().push(latLng);
        }

        function initialize_existing_area(area_positions){
            for(i=0; i<area_positions.length; i++){
                var markerAreaData = new google.maps.LatLng(area_positions[i].lat, area_positions[i].lng);
                markerArea = new google.maps.Marker({ map: map_area, position: markerAreaData, draggable: false, icon: area });

                //push markers
                markers.push(markerArea);

                //var path = poly.getPath();
                path = poly.getPath();

                poly.setMap(null);
                poly = new google.maps.Polygon({ map: map_area, path: path, strokeColor: "#FF0000", strokeOpacity: 0.8, strokeWeight: 2, fillColor: "#FF0000", fillOpacity: 0.35, editable: false });

                //show clear area
                isClosed = true;
                $("#clear_area").show();
                //google.maps.event.addListener(markerArea, "drag", update_polygon_closure(poly, i));
            }

            /*function update_polygon_closure(poly, i){
                return function(event){
                    poly.getPath().setAt(i, event.latLng);
                }
            }*/
        }

        var start = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Pink.png"
        var area = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Chartreuse.png"
        var map_location = null;
        var map_area = null;
        var marker = null;
        var infoWindow = null;
        var lat = null;
        var lng = null;
        var circle = null;
        var isClosed = false;
        var poly = null;
        var markers = [];
        var markerArea = null;
        var markerIndex = null;
        var path = null;

        window.onload = function () {
            //var map, infoWindow, marker, lng, lat;

            //Working hours
            initializeWorkingHours();

            getLocation(function(isFetched, currPost){
                if(isFetched){
                    infoWindow = new google.maps.InfoWindow;

                    if(currPost.lat != 0 && currPost.lng != 0){
                        //initialize map
                        initializeMap(currPost.lat, currPost.lng)

                        //initialize marker
                        initializeMarker(currPost.lat, currPost.lng)

                        //var isClosed = false;

                        poly = new google.maps.Polyline({ map: map_area, path: currPost.area ? currPost.area : [], strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });

                        if(currPost.area != null){
                            initialize_existing_area(currPost.area)
                        }

                        map_location.addListener('click', function(event) {
                            marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                            changeLocation(event.latLng.lat(), event.latLng.lng());
                        });

                        map_area.addListener('click', function(event) {
                            new_delivery_area(event.latLng)
                        });
                    }else{
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

                                //infoWindow.setPosition(pos);
                                //infoWindow.setContent('Location found.');
                                //infoWindow.open(map);

                                //initialize map
                                initializeMap(position.coords.latitude, position.coords.longitude)

                                //initialize marker
                                initializeMarker(position.coords.latitude, position.coords.longitude)

                                //change location in database
                                changeLocation(pos.lat, pos.lng);

                                map_location.addListener('click', function(event) {
                                    marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                                    changeLocation(event.latLng.lat(), event.latLng.lng());
                                });

                                map_area.addListener('click', function(event) {
                                    new_delivery_area(event.latLng)
                                });
                            }, function() {
                                // handleLocationError(true, infoWindow, map.getCenter());

                                //initialize map
                                initializeMap(54.5260, 15.2551)

                                //initialize marker
                                initializeMarker(54.5260, 15.2551)

                                map_location.addListener('click', function(event) {
                                    marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                                    changeLocation(event.latLng.lat(), event.latLng.lng());
                                });

                                map_area.addListener('click', function(event) {
                                    new_delivery_area(event.latLng)
                                });
                            });
                        } else {
                            // Browser doesn't support Geolocation
                            //handleLocationError(false, infoWindow, map.getCenter());
                        }
                    }
                }
            });
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
@endsection

