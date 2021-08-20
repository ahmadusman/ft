<?php

namespace App\Http\Controllers;

use Cart;
use App\Items;
use App\Restorant;
use App\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\Variants;

class CartController extends Controller
{
    public function add(Request $request){
        $item = Items::find($request->id);
        $restID=$item->category->restorant->id;

        //Check if added item is from the same restorant as previus items in cart
        $canAdd = false;
        if(Cart::getContent()->isEmpty()){
            $canAdd = true;
        }else{
            $canAdd = true;
            foreach (Cart::getContent() as $key => $cartItem) {
                if($cartItem->attributes->restorant_id."" != $restID.""){
                    $canAdd = false;
                    break;
                }
            }
        }

        //TODO - check if cart contains, if so, check if restorant is same as pervios one

       // Cart::clear();
        if($item && $canAdd){

            //are there any extras
            $cartItemPrice=$item->price;
            $cartItemName=$item->name;
            $theElement="";

            //Is there a varaint
            //variantID
            if($request->variantID){
                //Get the variant
                $variant=Variants::findOrFail($request->variantID);

                $cartItemPrice=$variant->price;
                $cartItemName=$item->name." ".$variant->optionsList;
                //$theElement.=$value." -- ".$item->extras()->findOrFail($value)->name."  --> ". $cartItemPrice." ->- ";
            }


            foreach ($request->extras as $key => $value) {

                $cartItemName.="\n+ ".$item->extras()->findOrFail($value)->name;
                $cartItemPrice+=$item->extras()->findOrFail($value)->price;
                $theElement.=$value." -- ".$item->extras()->findOrFail($value)->name."  --> ". $cartItemPrice." ->- ";
            }


            Cart::add((new \DateTime())->getTimestamp(), $cartItemName, $cartItemPrice, $request->quantity, array('id'=>$item->id,'variant'=>$request->variantID, 'extras'=>$request->extras,'restorant_id'=>$restID,'image'=>$item->icon,'friendly_price'=>  Money($cartItemPrice, env('CASHIER_CURRENCY','usd'),true)->format() ));

            return response()->json([
                'status' => true,
                'errMsg' => $theElement
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errMsg' => __("You can't add items from other restaurant!")
            ]);
            //], 401);
        }
    }

    public function getContent(){
        //Cart::clear();
        return response()->json([
            'data' => Cart::getContent(),
            'total' => Cart::getSubTotal(),
            'status' => true,
            'errMsg' => ''
        ]);
    }

    public function minutesToHours($numMun){
        $h =(int) ($numMun/60);
        $min=$numMun%60;
        if($min<10){
            $min="0".$min;
        }

        $time=$h.":".$min;
        if(env('TIME_FORMAT',"24hours")=="AM/PM"){
            $time=date("g:i A", strtotime($time));
        }
        return $time;
    }


    /*"0_from" => "09:00"
  "0_to" => "20:00"
  "1_from" => "09:00"
  "1_to" => "20:00"
  "2_from" => "09:00"
  "2_to" => "20:00"
  "3_from" => "09:00"
  "3_to" => "20:00"
  "4_from" => "09:00"
  "4_to" => "20:00"
  "5_from" => "09:00"
  "5_to" => "17:00"
  "6_from" => "09:00"
  "6_to" => "17:00"*/

  /*
    "0_from" => "9:00 AM"
  "0_to" => "8:10 PM"
  "1_from" => "9:00 AM"
  "1_to" => "8:00 PM"
  "2_from" => "9:00 AM"
  "2_to" => "8:00 PM"
  "3_from" => "9:00 AM"
  "3_to" => "8:00 PM"
  "4_from" => "9:00 AM"
  "4_to" => "8:00 PM"
  "5_from" => "9:00 AM"
  "5_to" => "5:00 PM"
  "6_from" => "9:00 AM"
  "6_to" => "5:00 PM"
   */

    public function getMinutes($time){
        $parts=explode(':',$time);
        return ((int)$parts[0])*60+(int)$parts[1];
    }



    public function getTimieSlots($hours){

        $ourDateOfWeek=[6,0,1,2,3,4,5][date('w')];
        $restaurantOppeningTime=$this->getMinutes(date("G:i", strtotime($hours[$ourDateOfWeek."_from"])));
        $restaurantClosingTime=$this->getMinutes(date("G:i", strtotime($hours[$ourDateOfWeek."_to"])));


        //Interval
        $intervalInMinutes=env('DELIVERY_INTERVAL_IN_MINUTES',30);

        //Generate thintervals from
        $currentTimeInMinutes= Carbon::now()->diffInMinutes(Carbon::today());
        $from= $currentTimeInMinutes>$restaurantOppeningTime?$currentTimeInMinutes:$restaurantOppeningTime;//Workgin time of the restaurant or current time,



        //print_r('now: '.$from);
        //To have clear interval
        $missingInterval=$intervalInMinutes-($from%$intervalInMinutes); //21

        //print_r('<br />missing: '.$missingInterval);

        //Time to prepare the order in minutes
        $timeToPrepare=env('TIME_TO_PREPARE_ORDER_IN_MINUTES',0); //30

        //First interval
        $from+= $timeToPrepare<=$missingInterval?$missingInterval:($intervalInMinutes-(($from+$timeToPrepare)%$intervalInMinutes))+$timeToPrepare;

        //$from+=$missingInterval;

        //Generate thintervals to
        $to= $restaurantClosingTime;//Closing time of the restaurant or current time


        $timeElements=[];
        for ($i=$from; $i <= $to ; $i+=$intervalInMinutes) {
            array_push($timeElements,$i);
        }
        //print_r("<br />");
        //print_r($timeElements);



        $slots=[];
        for ($i=0; $i < count($timeElements)-1 ; $i++) {
            array_push($slots,[$timeElements[$i],$timeElements[$i+1]]);
        }

        //print_r("<br />SLOTS");
        //print_r($slots);


        //INTERVALS TO TIME
        $formatedSlots=[];
        for ($i=0; $i < count($slots) ; $i++) {
            $key=$slots[$i][0]."_".$slots[$i][1];
            $value=$this->minutesToHours($slots[$i][0])." - ".$this->minutesToHours($slots[$i][1]);
            $formatedSlots[$key]=$value;
            //array_push($formatedSlots,[$key=>$value]);
        }



        return($formatedSlots);


    }

    public function getRestorantHours($restorantID){
          //Create all the time slots
          //The restaurant
          $restaurant=Restorant::findOrFail($restorantID);

          $timeSlots=$restaurant->hours?$this->getTimieSlots($restaurant->hours->toArray()):[];

          //Modified time slots for app
          $timeSlotsForApp=[];
          foreach ($timeSlots as $key => $timeSlotsTitle) {
             array_push($timeSlotsForApp,array('id'=>$key,'title'=>$timeSlotsTitle));
          }

          //Working hours
          $ourDateOfWeek=[6,0,1,2,3,4,5][date('w')];

          $format="G:i";
          if(env('TIME_FORMAT',"24hours")=="AM/PM"){
              $format="g:i A";
          }


          $openingTime=date($format, strtotime($restaurant->hours[$ourDateOfWeek."_from"]));
          $closingTime=date($format, strtotime( $restaurant->hours[$ourDateOfWeek."_to"]));

          $params = [
            'restorant' => $restaurant,
            'timeSlots' => $timeSlotsForApp,
            'openingTime' => $restaurant->hours&&$restaurant->hours[$ourDateOfWeek."_from"]?$openingTime:null,
            'closingTime' => $restaurant->hours&&$restaurant->hours[$ourDateOfWeek."_to"]?$closingTime:null,
         ];

         if($restaurant){
            return response()->json([
                'data' => $params,
                'status' => true,
                'errMsg' => ''
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errMsg' => 'Restorants not found!'
            ]);
        }

    }

    /*public function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);

          if ($unit == "K") {
            return ($miles * 1.609344);
          } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
    }*/

    function calculateDistance($latitude1, $longitude1, $latitude2, $longitude2, $unit) {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch($unit) {
          case 'Mi':
            break;
          case 'K' :
            $distance = $distance * 1.609344;
        }
        return (round($distance,2));
      }

    public function cart(){
        $restorantID=null;
        foreach (Cart::getContent() as $key => $cartItem) {
            $restorantID=$cartItem->attributes->restorant_id;
            break;
        }

        //The restaurant
        $restaurant=Restorant::findOrFail($restorantID);

        //Create all the time slots
        $timeSlots=$restaurant->hours?$this->getTimieSlots($restaurant->hours->toArray()):[];

        //Working hours
        $ourDateOfWeek=[6,0,1,2,3,4,5][date('w')];

        $format="G:i";
        if(env('TIME_FORMAT',"24hours")=="AM/PM"){
            $format="g:i A";
        }


        $openingTime=date($format, strtotime($restaurant->hours[$ourDateOfWeek."_from"]));
        $closingTime=date($format, strtotime($restaurant->hours[$ourDateOfWeek."_to"]));

        //user addresses
        $polygon = json_decode(json_encode($restaurant->radius));
        $numItems = sizeof($restaurant->radius);

        $addresses = [];
        if(auth()->user()->addresses){
            foreach(auth()->user()->addresses->reverse() as $address){

                $point = json_decode('{"lat": '.$address->lat.', "lng":'.$address->lng.'}');

                if(!array_key_exists($address->id, $addresses)){
                    $new_obj = (object) [];
                    $new_obj->id = $address->id;
                    $new_obj->address = $address->address;

                    if(isset($polygon[0])&&$this->withinArea($point,$polygon,$numItems)){
                        $new_obj->inRadius = true;
                    }else{
                        $new_obj->inRadius = false;
                    }

                    if(env('ENABLE_COST_PER_DISTANCE', false) && env('COST_PER_KILOMETER', 1)){

                        $distance = intval(round($this->calculateDistance($address->lat, $address->lng, $restaurant->lat, $restaurant->lng, "K")));
                        $new_obj->cost_per_km=floor($distance)*floatval(env('COST_PER_KILOMETER'));
                       // $new_obj->cost_per_km = @money( $distance * intval(env('COST_PER_KILOMETER')), env('CASHIER_CURRENCY','usd'),true);
                    }

                    $addresses[$address->id] = (object)$new_obj;
                }

                /*if(isset($polygon[0])&&$this->withinArea($point,$polygon,$numItems)){
                    if(!array_key_exists($address->id, $addresses)){
                        $new_obj = (object) [];
                        $new_obj->id = $address->id;
                        $new_obj->address = $address->address;

                        if(env('ENABLE_COST_PER_DISTANCE', false) && env('COST_PER_KILOMETER', 1)){

                            $distance = intval(round($this->calculateDistance($address->lat, $address->lng, $restaurant->lat, $restaurant->lng, "K")));
                            $new_obj->cost_per_km=floor($distance)*floatval(env('COST_PER_KILOMETER'));
                           // $new_obj->cost_per_km = @money( $distance * intval(env('COST_PER_KILOMETER')), env('CASHIER_CURRENCY','usd'),true);
                        }
                        $addresses[$address->id] = (object)$new_obj;
                    }
                }*/
            }
        }

        $params = [
            'title' => 'Shopping Cart Checkout',
            'restorant' => $restaurant,
            'timeSlots' => $timeSlots,
            'openingTime' => $restaurant->hours&&$restaurant->hours[$ourDateOfWeek."_from"]?$openingTime:null,
            'closingTime' => $restaurant->hours&&$restaurant->hours[$ourDateOfWeek."_to"]?$closingTime:null,
            'addresses' => $addresses
        ];

        //Open for all
        return view('cart')->with($params);
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

    public function clear(Request $request){

        //Get the client_id from address_id

        $oreder = new Order;
        $oreder->address_id = strip_tags($request->addressID);
        $oreder->restorant_id = strip_tags($request->restID);
        $oreder->client_id = auth()->user()->id;
        $oreder->driver_id = 2;
        $oreder->delivery_price = 3.00;
        $oreder->order_price = strip_tags($request->orderPrice);
        $oreder->comment = strip_tags($request->comment);
        $oreder->save();

        foreach (Cart::getContent() as $key => $item) {
            $oreder->items()->attach($item->id);
        }

        //Find first status id,
        ///$oreder->stauts()->attach($status->id,['user_id'=>auth()->user()->id]);
        Cart::clear();
        return redirect()->route('front')->withStatus(__('Cart clear.'));
        //return back()->with('success',"The shopping cart has successfully beed added to the shopping cart!");;
    }


    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function remove(Request $request){
        Cart::remove($request->id);
        return response()->json([
            'status' => true,
            'errMsg' => ''
        ]);
    }

    /**
     * Makes general api resonse
     */
    private function generalApiResponse(){
        return response()->json([
            'status' => true,
            'errMsg' => ''
        ]);
    }

    /**
     * Updates cart
     */
    private function updateCartQty($howMuch,$item_id){
        Cart::update($item_id, array('quantity' => $howMuch));
        return $this->generalApiResponse();
    }


    /**
     * Increase cart
     */
    public function increase($id){
       return $this->updateCartQty(1,$id);
    }

    /**
     * Decrese cart
     */
    public function decrease($id){
        return $this->updateCartQty(-1,$id);
    }

}

