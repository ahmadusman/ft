@extends('layouts.front', ['class' => ''])

@section('content')
@include('restorants.partials.modals')

    <section class="section-profile-cover section-shaped grayscale-05 d-none d-md-none d-lg-block d-lx-block">
      <!-- Circles background -->
      <img class="bg-image" loading="lazy" src="{{ $restorant->coverm }}" style="width: 100%;">
      <!-- SVG separator -->
      <div class="separator separator-bottom separator-skew">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </section>
    
    <section class="section pt-lg-0 mb--5 mt--9 d-none d-md-none d-lg-block d-lx-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white"  <?php if($restorant->description || $openingTime && $closingTime){echo 'style="border-bottom: 1px solid #f2f2f2;"';} ?> >
                        
                        <h1 type="button" class="display-3 text-white" data-toggle="modal" data-target="#modal-restaurant-info" >{{ $restorant->name }}</h1>
                        <p class="display-4" style="margin-top: 120px">{{ $restorant->description }}</p>
                       <p>@if(!empty($openingTime) && !empty($closingTime))  <i class="ni ni-watch-time"></i> <span>{{ $openingTime }}</span> - <span>{{ $closingTime }}</span> | @endif  <i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($restorant->address) }}">{{ $restorant->address }}</a>  |  <i class="ni ni-mobile-button"></i> <a href="tel:{{$restorant->phone}}">{{ $restorant->phone }} </a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section section-lg d-md-block d-lg-none d-lx-none" style="padding-bottom: 0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">
                        
                        <h1 type="button" class="display-3 text" data-toggle="modal" data-target="#modal-restaurant-info" >{{ $restorant->name }}</h1>
                        <p class="display-4 text">{{ $restorant->description }}</p>
                        @if(!empty($openingTime) && !empty($closingTime))
                            <p>{{ __('Today working hours') }}: <span><strong>{{ $openingTime }}</strong></span> - <span><strong>{{ $closingTime }}</strong></span></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section pt-lg-0" id="restaurant-content" style="padding-top: 0px">
        <input type="hidden" id="rid" value="{{ $restorant->id }}"/>
        <div class="container container-restorant">
            @if(!$restorant->categories->isEmpty())
            <nav class="tabbable sticky">
                <ul class="nav nav-pills bg-white mb-2">
                    <li class="nav-item nav-item-category ">
                        <a class="nav-link  mb-sm-3 mb-md-0 active" data-toggle="tab" role="tab" href="">{{ __('All categories') }}</a>
                    </li>
                    @foreach ( $restorant->categories as $key => $category)
                        @if(!$category->items->isEmpty())
                            <li class="nav-item nav-item-category" id="{{ 'cat_'.str_replace(' ', '', strtolower($category->name)).strval($key) }}">
                                <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" role="tab" id="{{ 'nav_'.str_replace(' ', '', strtolower($category->name)).strval($key) }}" href="#{{ str_replace(' ', '', strtolower($category->name)).strval($key) }}">{{ $category->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
            @endif
            @if(!$restorant->categories->isEmpty())
            @foreach ( $restorant->categories as $key => $category)
                @if(!$category->items->isEmpty())
                <div id="{{ str_replace(' ', '', strtolower($category->name)).strval($key) }}" class="{{ str_replace(' ', '', strtolower($category->name)).strval($key) }}">
                    <h1>{{ $category->name }}</h1><br />
                </div>
                @endif
                <div class="row {{ str_replace(' ', '', strtolower($category->name)).strval($key) }}">
                    @foreach ($category->items as $item)
                        @if($item->available == 1)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="strip">
                                <figure>
                                    <a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)"><img src="{{ $item->logom }}" loading="lazy" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                                </figure>
                                <span class="res_title"><b><a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)">{{ $item->name }}</a></b></span><br />
                                <span class="res_description">{{ $item->short_description}}</span><br />
                                <span class="res_mimimum">@money($item->price, env('CASHIER_CURRENCY','usd'),true)</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                        <br/><br/><br/>
                        <div class="text-center" style="opacity: 0.2;">
                            <img src="https://www.jing.fm/clipimg/full/256-2560623_juice-clipart-pizza-box-pizza-box.png" width="200" height="200"></img>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <i id="scrollTopBtn"  onclick="topFunction()" class="fa fa-arrow-up btn-danger"></i>

    </section>
@endsection

@section('js')
<script>





    var items=[];
    var currentItem=null;
    var currentItemSelectedPrice=null;
    var lastAdded=null;
    var previouslySelected=[];
    var extrasSelected=[];
    var variantID=null;
    var CASHIER_CURRENCY = "<?php echo  env('CASHIER_CURRENCY','usd') ?>";
    var LOCALE="<?php echo  App::getLocale() ?>";

    /*
    * Price formater
    * @param {Nummber} price
    */
    function formatPrice(price){
        var formatter = new Intl.NumberFormat(LOCALE, {
            style: 'currency',
            currency:  CASHIER_CURRENCY,
        });

        var formated=formatter.format(price);

        return formated;
    }

    /**
     * Load extras for variant
     * @param {Number} variant_id the variant id
     * */
    function loadExtras(variant_id){
        //alert("Load extras for "+variant_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'GET',
                url: '/items/variants/'+variant_id+'/extras',
                success:function(response){
                    if(response.status){
                        response.data.forEach(element => {
                            $('#exrtas-area-inside').append('<div class="custom-control custom-checkbox mb-3"><input onclick="recalculatePrice('+element.item_id+');" class="custom-control-input" id="'+element.id+'" name="extra"  value="'+element.price+'" type="checkbox"><label class="custom-control-label" for="'+element.id+'">'+element.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+'+formatPrice(element.price)+'</label></div>');
                        });
                        $('#exrtas-area').show();

                    }
                }, error: function (response) {
                    //return callback(false, response.responseJSON.errMsg);
                }
            })
        }



    
    /**
     *
     * Set the selected variant, set price and shows qty area and calls load extras
     * */
    function setSelectedVariant(element){

        //console.log(formated);
        $('#modalPrice').html(formatPrice(element.price));

        //Set current item price
        currentItemSelectedPrice=element.price;

        //Show QTY
        $('.quantity-area').show();

        //Set variantID
        variantID=element.id;

        //Empty the extras, and call it
        $('#exrtas-area-inside').empty();
        loadExtras(variantID);

    }

    function getTheDataForTheFoundVariable(){
        console.log(previouslySelected);
        var comparableObject={};
        previouslySelected.forEach(element => {
            comparableObject[element.option_id]=element.name.trim().toLowerCase().replace(/\s/g , "-");
        });
        comparableObject=JSON.stringify(comparableObject)
        /*console.log("----- comparableObject -----")
        console.log(comparableObject);
        console.log("----- currentItemVariants -----")
        console.log(JSON.stringify(currentItem['variants']))*/
        //{\"9\":\"small\",\"10\":\"normal\"}
        currentItem['variants'].forEach(element => {
            if(comparableObject==element.options){
                console.log(element.options);
                setSelectedVariant(element);
            }
        });

    }


    function checkIfVariableExists(forOption,optionValue){

        var newElement={"option_id":forOption,"name":optionValue};
        console.log('NEW ELEMNGT');
        console.log(newElement);

        var possibleSelection=JSON.parse(JSON.stringify(previouslySelected));
        possibleSelection.push(newElement);
        console.log(possibleSelection);

        var filteredObjects=[];
        //possibleSelection.forEach(element => {
            currentItem.variants.forEach(theVariant => {
                theOptions=JSON.parse(theVariant.options);
                var ok=true;
                Object.keys(theOptions).map((key)=>{

                    console.log(key+" : "+theOptions[key])
                    possibleSelection.forEach(element => {
                        if(key==element.option_id){
                            if(theOptions[key]+""!=element.name.trim().toLowerCase().replace(/\s/g , "-")+""){
                                ok=false;
                            }
                        }
                    });

                })

                if(ok){
                        filteredObjects.push(theVariant);
                        console.log("ok")
                    }else{
                        console.log("not ok")
                    }

                //comparableObject[element.option_id]=element.name.trim().toLowerCase().replace(/\s/g , "-");
            });

        //});


        return filteredObjects.length>0;

    }

    function appendOption(name,id){
        lastAdded=id;
        $('#variants-area-inside').append('<div id="variants-area-'+id+'"><br /><label class="form-control-label"><b>'+name+'<b></label><div><div id="variants-area-inside-'+id+'" class="btn-group btn-group-toggle" data-toggle="buttons"> </div></div>');
    }

    function optionChanged(option_id,name){

        var newElement={"option_id":option_id,"name":name};

        //If this option is already in the select, then it means it is old
        var tempObj=[];
        var wasOld=false;
        if(previouslySelected.length>0){
            previouslySelected.forEach(element => {
                if(!wasOld){
                    if(element.option_id==option_id){
                        //This is old select, remove any new
                        tempObj.push(newElement);
                        wasOld=true;
                    }else{
                        tempObj.push(element);
                    }
                }else{
                    //When rest, from the old one, remove the object
                    $( "#variants-area-"+element.option_id ).remove();
                }

            });
        }

        if(wasOld){
            previouslySelected=tempObj;
            //remove also last inserted, and readded it
            $( "#variants-area-"+lastAdded ).remove();
        }else{
            previouslySelected.push(newElement);
        }
        setVariants();


    }

    function appendOptionValue(name,enabled,option_id){
        $('#variants-area-inside-'+option_id).append('<label style="opacity: '+(enabled?1:0.5)+'" class="btn btn-outline-primary"><input  onchange="optionChanged('+option_id+',\''+name+'\')"  '+ (enabled?"":"disabled") +' type="radio" name="option_'+option_id+'" value="option_'+option_id+"_"+name+'" autocomplete="off" />'+name+'</label>')
    }

    function setVariants(){
        //1. Determine previously selected variants
       // var previouslySelected=[];

       //HIDE QTY
       $('.quantity-area').hide();
       $('#exrtas-area-inside').empty();
       $('#exrtas-area').hide();

        //2. Get the new option to show
        var newOptionToShow=null;
        console.log(currentItem.options[previouslySelected.length]);
        newOptionToShow=currentItem.options[previouslySelected.length];

        if(newOptionToShow!=undefined){
            //2.1 Add the options in the table
            appendOption(newOptionToShow.name,newOptionToShow.id);

            newOptionToShow.options.split(",").forEach(element => {
                if(checkIfVariableExists(newOptionToShow.id,element)){
                    //Next variable exists
                    console.log("Exists: "+element);
                    appendOptionValue(element,true,newOptionToShow.id);
                }else{
                    //Vsaraiable with the next option value doens't exists
                    console.log("Does not exists: "+element);
                    appendOptionValue(element,false,newOptionToShow.id);
                }


            });
        }else{
            console.log("No more options");
            getTheDataForTheFoundVariable();
        }




        //3. Add the new option options
        //3.1 If new option is null, show the variant price
    }


    function setCurrentItem(id){


        var item=items[id];
        currentItem=item;
        previouslySelected=[];
        $('#modalTitle').text(item.name);
        $('#modalName').text(item.name);
        $('#modalPrice').html(item.price);
        $('#modalID').text(item.id);
        $("#modalImg").attr("src",item.image);
        $('#modalDescription').html(item.description);


        if(item.has_variants){
            //Vith variants
            //Hide the counter, and extrasts
            $('.quantity-area').hide();

           //Now show the variants options
           $('#variants-area-inside').empty();
           $('#variants-area').show();
           setVariants();
           //$('#modalPrice').html("dynamic");




        }else{
            //Normal
            currentItemSelectedPrice=item.priceNotFormated;
            $('#variants-area').hide();
            $('.quantity-area').show();
        }


        $('#productModal').modal('show');

        extrasSelected=[];

        //Now set the extrast
        if(item.extras.length==0||item.has_variants){
            console.log('has no extras');
            $('#exrtas-area-inside').empty();
            $('#exrtas-area').hide();
        }else{
            console.log('has extras');
            $('#exrtas-area-inside').empty();
            item.extras.forEach(element => {
                console.log(element);
                $('#exrtas-area-inside').append('<div class="custom-control custom-checkbox mb-3"><input onclick="recalculatePrice('+id+');" class="custom-control-input" id="'+element.id+'" name="extra"  value="'+element.price+'" type="checkbox"><label class="custom-control-label" for="'+element.id+'">'+element.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+'+element.priceFormated+'</label></div>');
            });
            $('#exrtas-area').show();
        }
    }

    function recalculatePrice(id,value){
        //console.log("Triger price recalculation: "+id);
       // console.log(items[id]);
        var mainPrice=parseFloat(currentItemSelectedPrice);
        extrasSelected=[];

        //Get the selected check boxes
        $.each($("input[name='extra']:checked"), function(){
            mainPrice+=parseFloat(($(this).val()+""));
            extrasSelected.push($(this).attr('id'));
        });
        $('#modalPrice').html(formatPrice(mainPrice));

    }
    <?php
    $items=[];
    $categories = [];
    foreach ($restorant->categories as $key => $category) {

        array_push($categories, str_replace(' ', '', strtolower($category->name)).strval($key));

        foreach ($category->items as $key => $item) {

            $formatedExtras=$item->extras;

            foreach ($formatedExtras as &$element) {
                $element->priceFormated=@money($element->price, env('CASHIER_CURRENCY','usd'),true)."";
            }

            //Now add the variants and optins to the item data
            $itemOptions=$item->options;

            $theArray=array(
                'name'=>$item->name,
                'id'=>$item->id,
                'priceNotFormated'=>$item->price,
                'price'=>@money($item->price, env('CASHIER_CURRENCY','usd'),true)."",
                'image'=>$item->logom,
                'extras'=>$formatedExtras,
                'options'=>$item->options,
                'variants'=>$item->variants,
                'has_variants'=>$item->has_variants==1&&$item->options->count()>0,
                'description'=>$item->description
            );
            echo "items[".$item->id."]=".json_encode($theArray).";";
        }
    }
    ?>
</script>
    <script type="text/javascript">
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

        function initializeMap(lat, lng){
            var map_options = {
                zoom: 13,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: "terrain",
                scaleControl: true
            }

            map_location = new google.maps.Map( document.getElementById("map3"), map_options );
        }

        function initializeMarker(lat, lng){
            var markerData = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                position: markerData,
                map: map_location,
                icon: start
            });
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

        var categories = <?php echo json_encode($categories); ?>;

        window.onload();

        window.onload = function () {
            //var map, infoWindow, marker, lng, lat;

            getLocation(function(isFetched, currPost){
                if(isFetched){


                    if(currPost.lat != 0 && currPost.lng != 0){
                        //initialize map
                        initializeMap(currPost.lat, currPost.lng)

                        //initialize marker
                        initializeMarker(currPost.lat, currPost.lng)

                        //var isClosed = false;
                    }
                }
            });

            //checkIfCategoryIsSelected();
        }

        /*function checkIfCategoryIsSelected() {
            var sPageURL = window.location.href;
            sURLVariables = sPageURL.split('#');
            $("#nav_"+sURLVariables[1]).addClass("active");

            if(sURLVariables[1] != undefined){
                $.each(categories, function( index, value ) {
                    if(value != sURLVariables[1]){
                        $("."+value).hide();
                    }else{
                        $("#nav_"+value).addClass("active");
                    }
                });
            }
        };*/

        var mybutton = document.getElementById("scrollTopBtn");

        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            //document.body.scrollTop = 0;
            $("html, body").animate({ scrollTop: 0 }, 600);
            document.documentElement.scrollTop = 0;
        }



        $(".nav-item-category").on('click', function() {
            $.each(categories, function( index, value ) {
                $("."+value).show();

                //$("#nav_"+value).removeClass("active");
            });

            var id = $(this).attr("id");
            var category_id = id.substr(id.indexOf("_")+1, id.length);
            //$("#nav_"+category_id).addClass("active");

            //$("."+category_id).hide();

            $.each(categories, function( index, value ) {
                if(value != category_id){
                    $("."+value).hide();
                }
            });
        });
    </script>
@endsection
