<?php

namespace App\Http\Controllers;
use App\Restorant;
use App\Items;
use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;
use Spatie\Geocoder\Exceptions\CouldNotGeocode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use App\Plans;
use App\User;
use App\City;
use App\Settings;


class FrontEndController extends Controller
{
    /**
     * Gets subdomain
     */
    public function getSubDomain(){
        $subdomain = substr_count($_SERVER['HTTP_HOST'], '.') > 1 ? substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.')) : '';
        if($subdomain==""|in_array($subdomain,config('app.ignore_subdomains'))){
            return false;
        }
        return $subdomain;
    }


    /**
     * Returns restaurants based on the q parameter
     * @param $restaurantIDS - the list of the restaurants to take into account
     * @return Restorant[] restaurants
     */
    private function filterRestaurantsOnQuery($restaurantIDS){
         //1. Find all items
         $items = Items::where(['available' => 1])->where(function ($q) {
            $stripedQuery='%'.strip_tags(\Request::input('q'))."%";
            $q->where('name', 'like',$stripedQuery)->orWhere('description', 'like',$stripedQuery);
        })->with('category.restorant')->get();


        //Find how many time happens on item level
        $restorants=array();
        foreach($items as $item) {
            if(isset($item->category)){

                //Check if this restaurant is part of the restaurant list
                if(in_array($item->category->restorant_id,$restaurantIDS)){
                    if(isset($restorants[$item->category->restorant_id])){
                        //Enlarge
                        $restorants[$item->category->restorant_id]->items_count++;
                    }else{
                        //Add
                        $restorants[$item->category->restorant_id]=$item->category->restorant;
                        $restorants[$item->category->restorant_id]->items_count=1;
                    }
                }

            }
        }



        //Find how many time happens on restaurant level
        $restorantsQ = Restorant::where(['active' => 1])->where(function ($q) {
            $stripedQuery='%'.strip_tags(\Request::input('q'))."%";
            $q->where('name', 'like',$stripedQuery)->orWhere('description', 'like',$stripedQuery);
        });
        //Calculate the values
        foreach($restorantsQ->get() as $restorant) {
            if(in_array($restorant->id,$restaurantIDS)){
                if(isset($results[$restorant->id])){
                    //Enlarge - more value
                    $restorants[$restorant->id]->items_count+=5;
                }else{
                    //Add
                    $restorants[$restorant->id]=$restorant;
                    $restorants[$restorant->id]->items_count=5;
                }
            }
        }

        //Now sort the restaurant based on how many times occures the search string
        usort($restorants, function($a, $b) {return strcmp($a->items_count, $b->items_count);});

        return $restorants;
    }

    public function index(){

        $hasQuery=\Request::has('q')&&strlen(\Request::input('q'))>1;
        $hasLocation=\Request::has('location')&&strlen(\Request::input('location'))>1;

        //0. Check if it has DB access - otherwise go to install
        try {
            \DB::connection()->getPdo();
        } catch ( \Exception $e) {
            return redirect()->route('LaravelInstaller::welcome');
        }

        //SITE SWITCHER

        //1. Single mode
        if(env('SINGLE_MODE',false)&&env('SINGLE_MODE_ID')){return $this->singleMode();}

        //2. Subdomain mode
        if($this->getSubDomain()){return $this->subdomainMode();}

        //3. QR Mode
        if(config('app.isqrsaas')){return $this->qrsaasMode();}


        //Multy City mode, and we don't have location atm
        if(env('MULTI_CITY',false)&&!($hasLocation||$hasQuery)){return $this->multyCityMode();}

        //ELSE - Query and Location mode //Default
        return $this->showStores(null);

    }

    /**
     * 1. Single mode, only one restorant
     */
    public function singleMode(){
        $restorant = Restorant::findOrFail(env('SINGLE_MODE_ID'));
        return view('restorants.show',['restorant' =>$restorant]);
    }

    /**
     * 2. Subdomain mode - directly show store
     */
    public function subdomainMode(){
        $subDomain=$this->getSubDomain();
        if($subDomain){
            $restorant = Restorant::where('subdomain',$subDomain)->get();
            if(count($restorant)!=1){
                return view('restorants.alertdomain',['subdomain' =>$subDomain]);
            }
            return view('restorants.show',['restorant' =>$restorant[0]]);
        }
    }

    /**
     * 3. QR Mode
     */
    public function qrsaasMode(){
        $plans=Plans::get()->toArray();
        $colCounter=[4,12,6,4,3,2,2,2,2,2,2,2,2,2,2,2,2,2];
        return view('qrsaas.landing',['col'=>$colCounter[count($plans)],'plans'=>$plans]);
    }

    /**
     * 4. Multy city mode
     */
    public function multyCityMode(){

        //Default headers
        $settings=Settings::findOrFail(1)->first();
        config(['global.header_title' => $settings->header_title]);
        config(['global.header_subtitle' => $settings->header_subtitle]);

        //Set the cookie of the last entered address
        $lastaddress = Cookie::get('lastaddress');
        $response = new \Illuminate\Http\Response(view('welcome',[
            'sections' =>[['super_title'=>__('Cities'),'title'=>__('Find us in these cities and many more!'),'cities'=>City::where('id','>',0)->get()]],
            'lastaddress'=>$lastaddress
        ]));
        if(\Request::has('location')&&strlen(\Request::input('location'))>1){
            $response->withCookie(cookie('lastaddress', \Request::input('location'), 120));
        }
        return $response;
    }


    /**
     * Show stores
     * @param {String} city_alias - city alias to show results for - can be null
     */
    public function showStores($city_alias){
        //Variants
        /**
         * 1. Nothing no city, no q, no location
         * 2. Just city
         * 3. City and Query
         * 4. Just location
         * 5. Location and query
         * 6. Just Qury
         */

        //DATA
        $hasQuery=\Request::has('q')&&strlen(\Request::input('q'))>1;
        $hasLocation=\Request::has('location')&&strlen(\Request::input('location'))>1;
        $hasCity=$city_alias!=null;

        $sections=[];
        $aditionInTitle=$hasQuery?" ".__('where you can find')." ".\Request::input('q'):"";

        //Default headers
        $settings=Settings::findOrFail(1)->first();
        config(['global.header_title' => $settings->header_title]);
        config(['global.header_subtitle' => $settings->header_subtitle]);


        if($hasCity){
            //CITY BASED SEARCH CASE 4 and 5

            //Find the city
            $city=City::where('alias',$city_alias)->first();

            config(['global.header_title' => $city->header_title]);
            config(['global.header_subtitle' => $city->header_subtitle]);

            if(!$city){abort(404);}
            $theRestorants=Restorant::where(['active'=>1,'city_id'=>$city->id]);

            if($hasQuery){
                //With Query
                $restorants=$this->filterRestaurantsOnQuery($theRestorants->pluck('id')->toArray());
            }else{
                //No query
                $restorants=$theRestorants->get()->shuffle();
            }
            array_push($sections,['title'=>__('Restaurants in')." ".$city->name.$aditionInTitle,'restorants' =>$restorants]);
        }
        else if($hasLocation){
            //LOCATION BASED SEARCH CASE 4 and 5
            //First, find the provided location, convert it to lat/lng
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocoder->setApiKey(config('geocoder.key'));


            try {
                 $geoResults=$geocoder->getCoordinatesForAddress(\Request::input('location'));
            } catch (CouldNotGeocode $e) {
                report($e);
                return view('restorants.error_location',['message'=>"The provided api key GOOGLE_MAPS_API_KEY has restrictions and we can not geocode the address. Please look into the documentation of this product to see what APIs are required to be enabled."]);
            }



            if($geoResults['formatted_address']=="result_not_found"){
                //No results found
                return view('restorants.error_location',['message'=>'You have provided address that we can not find']);
            }else{
                //Ok, we have lat and lng
                $restorantsQuery = Restorant::where('active', 1);
                $restorantsWithGeoIDS=$this->scopeIsWithinMaxDistance($restorantsQuery,$geoResults['lat'],$geoResults['lng'],env('LOCATION_SEARCH_RADIUS',50))->pluck('id');
                $restorants=Restorant::whereIn('id',$restorantsWithGeoIDS)->get();


                //Furthure, check restaurant's delivery area
                $allRestorantDelivering=[];
                $nearBytDelivering=[];
                $featuredDelivering=[];

                $allRestorantDeliveringIDS=[];
                $nearBytDeliveringIDS=[];
                $featuredDeliveringIDS=[];

                $limitOfNearby=env('MOST_NEARBY_LIMIT',4);

                $point = json_decode('{"lat": '.$geoResults['lat'].', "lng":'.$geoResults['lng'].'}');
                foreach ($restorants as $key => $restorant) {
                   //Check if restorant delivery area is within
                   if(!is_array($restorant->radius)){
                       continue;
                   }
                   $polygon= json_decode(json_encode($restorant->radius));
                   $numItems=sizeof($restorant->radius);
                   //dd($restorant->radius);
                   //dd($numItems);

                   //In disabled deliver - no delivery area
                   if( env('DISABLE_DELIVER',false) ||     (isset($polygon[0])&&$this->withinArea($point,$polygon,$numItems))){
                        //add in allRestorantDelivering
                        array_push($allRestorantDelivering,$restorant);
                        array_push($allRestorantDeliveringIDS,$restorant->id);

                        if(count($nearBytDelivering)<$limitOfNearby){
                            array_push($nearBytDelivering,$restorant);
                            array_push($nearBytDeliveringIDS,$restorant->id);
                        }

                        //Featured
                        if($restorant->is_featured.""=="1"){
                            array_push($featuredDelivering,$restorant);
                            array_push($featuredDeliveringIDS,$restorant->id);
                        }
                   }
                }

                if($hasQuery){
                    //CASE 5
                    //we have some query
                    $allRestorantDeliveringCollection = collect($this->filterRestaurantsOnQuery($allRestorantDeliveringIDS));
                    $nearBytDeliveringCollection = collect($this->filterRestaurantsOnQuery($nearBytDeliveringIDS));
                    $featuredDeliveringCollection = collect($this->filterRestaurantsOnQuery($featuredDeliveringIDS));
                }else{
                    //CASE 4
                    //No additinal qury
                    $allRestorantDeliveringCollection = collect($allRestorantDelivering)->shuffle();
                    $nearBytDeliveringCollection = collect($nearBytDelivering)->shuffle();
                    $featuredDeliveringCollection = collect($featuredDelivering)->shuffle();
                }


                if($featuredDeliveringCollection->count()>0){
                    array_push($sections,['title'=>__('Featured restaurants').$aditionInTitle,'restorants' =>$featuredDeliveringCollection]);
                }
                if($nearBytDeliveringCollection->count()>0){
                    array_push($sections,['title'=>__('Popular restaurants near you').$aditionInTitle,'restorants' =>$nearBytDeliveringCollection]);
                }

                $allReastaurantsTitle=__('All restaurants delivering to your address');
                if(env('DISABLE_DELIVER',false)){
                    $allReastaurantsTitle=__('All restaurants');
                }
                array_push($sections,['title'=>$allReastaurantsTitle.$aditionInTitle,'restorants' => $allRestorantDeliveringCollection]);
            }

        }
        else if($hasQuery){
            //CASE 6
            //IS IS Query String Search
            $restorants=$this->filterRestaurantsOnQuery(Restorant::where(['active' => 1])->pluck('id')->toArray());
            array_push($sections,['title'=>__('Restaurants').$aditionInTitle,'restorants' =>$restorants]);
        }
        else{
            //CASE 1 - nothing at all
            //No query at all
            array_push($sections,['title'=>__('Popular restaurants'),'restorants' =>Restorant::where('active', 1)->get()->shuffle()]);
        }




        //Set the cookie of the last entered address
        $lastaddress = Cookie::get('lastaddress');
        $response = new \Illuminate\Http\Response(view('welcome',[
            'sections' =>$sections,
            'lastaddress'=>$lastaddress
        ]));
        if(\Request::has('location')&&strlen(\Request::input('location'))>1){
            $response->withCookie(cookie('lastaddress', \Request::input('location'), 120));
        }
        return $response;


    }

    private function withinArea($point, $polygon,$n)
    {
        if($polygon[0] != $polygon[$n-1])
            $polygon[$n] = $polygon[0];
        $j = 0;
        $oddNodes = false;
        $x = $point->lng;
        $y = $point->lat;
        for ($i = 0; $i < $n; $i++)
        {
            $j++;
            if ($j == $n)
            {
                $j = 0;
            }
            if ((($polygon[$i]->lat < $y) && ($polygon[$j]->lat >= $y)) || (($polygon[$j]->lat < $y) && ($polygon[$i]->lat >=$y)))
            {
                if ($polygon[$i]->lng + ($y - $polygon[$i]->lat) / ($polygon[$j]->lat - $polygon[$i]->lat) * ($polygon[$j]->lng - $polygon[$i]->lng) < $x)
                {
                    $oddNodes = !$oddNodes;
                }
            }
        }
        return $oddNodes;
    }

    private function scopeIsWithinMaxDistance($query, $latitude, $longitude, $radius = 25) {

        $haversine = "(6371 * acos(cos(radians($latitude))
                        * cos(radians(restorants.lat))
                        * cos(radians(restorants.lng)
                        - radians($longitude))
                        + sin(radians($latitude))
                        * sin(radians(restorants.lat))))";
        return $query
           ->select(['name','id']) //pick the columns you want here.
           ->selectRaw("{$haversine} AS distance")
           ->whereRaw("{$haversine} < ?", [$radius])
           ->orderBy('distance');
   }

    public function restorant($alias){
        $subDomain=$this->getSubDomain();
        if($subDomain&&$alias!==$subDomain){
            return redirect()->route('restorant',$subDomain);
        }
        $restorant = Restorant::where('subdomain',$alias)->first();

        //ratings usernames
        $usernames = [];
        if($restorant&&$restorant->ratings){
            foreach($restorant->ratings as $rating){
                $user = User::where('id',$rating->user_id)->get()->first();

                if(!array_key_exists($user->id, $usernames)){
                    $new_obj = (object) [];
                    $new_obj->name = $user->name;

                    $usernames[$user->id] = (object)$new_obj;
                }
            }
        }


        //Working hours
        $ourDateOfWeek=[6,0,1,2,3,4,5][date('w')];

        $format="G:i";
        if(env('TIME_FORMAT',"24hours")=="AM/PM"){
            $format="g:i A";
        }

        /*$openingTime=date($format, strtotime($restorant[0]->hours[$ourDateOfWeek."_from"]));
        $closingTime=date($format, strtotime($restorant[0]->hours[$ourDateOfWeek."_to"]));

        return view('restorants.show',[
            'restorant' => $restorant[0],
            'openingTime' => $restorant[0]->hours&&$restorant[0]->hours[$ourDateOfWeek."_from"]?$openingTime:null,
            'closingTime' => $restorant[0]->hours&&$restorant[0]->hours[$ourDateOfWeek."_to"]?$closingTime:null,
        ]);*/

        $openingTime = $restorant->hours&&$restorant->hours[$ourDateOfWeek."_from"] ? date($format, strtotime($restorant->hours[$ourDateOfWeek."_from"])) : null;
        $closingTime = $restorant->hours&&$restorant->hours[$ourDateOfWeek."_to"] ? date($format, strtotime($restorant->hours[$ourDateOfWeek."_to"])) : null;

        //dd($restorant->categories[1]->items[0]->extras);
        return view('restorants.show',[
            'restorant' => $restorant,
            'openingTime' => $openingTime,
            'closingTime' => $closingTime,
            'usernames' => $usernames
        ]);
    }

    public function findByLocation(Request $request)
    {
        return view('restorants.location');
    }

    public function getCurrentLocation(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $res = $geocoder->getAddressForCoordinates($request->lat, $request->lng);
        return response()->json([
            'data' => $res,
            'status' => true,
            'errMsg' => ''
        ]);
    }
}
