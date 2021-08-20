<?php

namespace App\Http\Controllers;

use App\Restorant;
use App\User;
use App\Hours;
use App\City;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\RestaurantCreated;
use Illuminate\Support\Facades\Validator;
use App\Notifications\WelcomeNotification;

//use Intervention\Image\Image;
use Image;

use App\Imports\RestoImport;
use Maatwebsite\Excel\Facades\Excel;

class RestorantController extends Controller
{

    protected $imagePath='uploads/restorants/';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Restorant $restorants)
    {
        if(auth()->user()->hasRole('admin')){
            //return view('restorants.index', ['restorants' => $restorants->where(['active'=>1])->paginate(10)]);
            return view('restorants.index', ['restorants' => $restorants->orderBy('id', 'desc')->paginate(10)]);
        }else return redirect()->route('orders.index')->withStatus(__('No Access'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->hasRole('admin')){
            return view('restorants.create');
        }else return redirect()->route('orders.index')->withStatus(__('No Access'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate first
        $request->validate([
            'name' => ['required', 'string', 'unique:restorants,name', 'max:255'],
            'name_owner' => ['required', 'string', 'max:255'],
            'email_owner' => ['required', 'string', 'email', 'unique:users,email', 'max:255'],
            'phone_owner' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        //Create the user
        $generatedPassword = Str::random(10);
        $owner = new User;
        $owner->name = strip_tags($request->name_owner);
        $owner->email = strip_tags($request->email_owner);
        $owner->phone = strip_tags($request->phone_owner)|"";
        $owner->api_token = Str::random(80);

        $owner->password =  Hash::make($generatedPassword);
        $owner->save();

        //Assign role
        $owner->assignRole('owner');

        //Create Restorant
        $restorant = new Restorant;
        $restorant->name = strip_tags($request->name);
        $restorant->user_id = $owner->id;
        $restorant->description = strip_tags($request->description."");
        $restorant->minimum = $request->minimum|0;
        $restorant->lat = 0;
        $restorant->lng = 0;
        $restorant->address = "";
        $restorant->phone = strip_tags($request->phone_owner)|"";
        $restorant->subdomain= $this->createSubdomainFromName(strip_tags($request->name));
        //$restorant->logo = "";
        $restorant->save();

        //Send email to the user/owner
        $owner->notify(new RestaurantCreated($generatedPassword,$restorant,$owner));



        return redirect()->route('restorants.index')->withStatus(__('Restaurant successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Restorant  $restorant
     * @return \Illuminate\Http\Response
     */
    public function show(Restorant $restorant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Restorant  $restorant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restorant $restorant)
    {
        //Days of the week
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $days[$i] = jddayofweek($i,1);
        }

        //Generate days columns
        $hoursRange = [];
        for($i=0; $i<7; $i++){
            $from = $i."_from";
            $to = $i."_to";

            array_push($hoursRange, $from);
            array_push($hoursRange, $to);
        }

        $hours = Hours::where(['restorant_id' => $restorant->id])->get($hoursRange)->first();

        if(auth()->user()->id==$restorant->user_id||auth()->user()->hasRole('admin')){
            //return view('restorants.edit', compact('restorant'));
            return view('restorants.edit',[
                'restorant' => $restorant, 
                'days' => $days, 
                'cities'=> City::get()->pluck('name','id'),
                'hours' => $hours]);
        }
        return redirect()->route('home')->withStatus(__('No Access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Restorant  $restorant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restorant $restorant)
    {
        $restorant->name = strip_tags($request->name);

        $restorant->address = strip_tags($request->address);
        $restorant->description = strip_tags($request->description);
        $restorant->minimum = strip_tags($request->minimum);
        $restorant->fee = $request->fee ? $request->fee : 0;
        $restorant->static_fee = $request->static_fee ? $request->static_fee : 0;
        $restorant->subdomain=$this->createSubdomainFromName(strip_tags($request->name));
        $restorant->is_featured = $request->is_featured != null ? 1 : 0;
        if(isset($request->city_id)){
            $restorant->city_id = $request->city_id;
        }
        

        if($request->hasFile('resto_logo')){
            $restorant->logo=$this->saveImageVersions(
                $this->imagePath,
                $request->resto_logo,
                [
                    ['name'=>'large','w'=>590,'h'=>400],
                    ['name'=>'medium','w'=>295,'h'=>200],
                    ['name'=>'thumbnail','w'=>200,'h'=>200]
                ]
            );

        }
        if($request->hasFile('resto_cover')){
            $restorant->cover=$this->saveImageVersions(
                $this->imagePath,
                $request->resto_cover,
                [
                    ['name'=>'cover','w'=>2000,'h'=>1000],
                    ['name'=>'thumbnail','w'=>400,'h'=>200]
                ]
            );
        }

        $restorant->update();

        if(auth()->user()->hasRole('admin')){
            return redirect()->route('restorants.edit',['id' => $restorant->id])->withStatus(__('Restaurant successfully updated.'));
        }else{
            return redirect()->route('restorants.edit',['id' => $restorant->id])->withStatus(__('Restaurant successfully updated.'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Restorant  $restorant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restorant $restorant)
    {
        $restorant->active=0;
        $restorant->save();

        return redirect()->route('restorants.index')->withStatus(__('Restaurant successfully deactivated.'));
    }

    public function updateLocation(Restorant $restorant, Request $request)
    {

        $restorant->lat = $request->lat;
        $restorant->lng = $request->lng;

        $restorant->update();

        return response()->json([
            'status' => true,
            'errMsg' => ''
        ]);
    }

    public function updateRadius(Restorant $restorant, Request $request)
    {
        $restorant->radius = $request->radius;
        $restorant->update();

        return response()->json([
            'status' => true,
            'msg' => ''
        ]);
    }

    public function updateDeliveryArea(Restorant $restorant, Request $request)
    {
        $restorant->radius = json_decode($request->path);
        $restorant->update();

        return response()->json([
            'status' => true,
            'msg' => ''
        ]);
    }

    public function getLocation(Restorant $restorant)
    {
        return response()->json([
            'data' => [
                'lat' => $restorant->lat,
                'lng' => $restorant->lng,
                'area' => $restorant->radius
            ],
            'status' => true,
            'errMsg' => ''
        ]);
    }

    public function import(Request $request)
    {
        Excel::import(new RestoImport, request()->file('resto_excel'));

        return redirect()->route('restorants.index')->withStatus(__('Restaurant successfully imported.'));
    }

    public function workingHours(Request $request)
    {
        $hours = Hours::where(['restorant_id' => $request->rid])->first();

        if($hours == null){

            $hours = new Hours();
            $hours->restorant_id = $request->rid;
            $hours->{'0_from'} = $request->{'0_from'} ?? null;
            $hours->{'0_to'} = $request->{'0_to'} ?? null;
            $hours->{'1_from'} = $request->{'1_from'} ?? null;
            $hours->{'1_to'} = $request->{'1_to'} ?? null;
            $hours->{'2_from'} = $request->{'2_from'} ?? null;
            $hours->{'2_to'} = $request->{'2_to'} ?? null;
            $hours->{'3_from'} = $request->{'3_from'} ?? null;
            $hours->{'3_to'} = $request->{'3_to'} ?? null;
            $hours->{'4_from'} = $request->{'4_from'} ?? null;
            $hours->{'4_to'} = $request->{'4_to'} ?? null;
            $hours->{'5_from'} = $request->{'5_from'} ?? null;
            $hours->{'5_to'} = $request->{'5_to'} ?? null;
            $hours->{'6_from'} = $request->{'6_from'} ?? null;
            $hours->{'6_to'} = $request->{'6_to'} ?? null;
            $hours->save();
        }

        $hours->{'0_from'} = $request->{'0_from'} ?? null;
        $hours->{'0_to'} = $request->{'0_to'} ?? null;
        $hours->{'1_from'} = $request->{'1_from'} ?? null;
        $hours->{'1_to'} = $request->{'1_to'} ?? null;
        $hours->{'2_from'} = $request->{'2_from'} ?? null;
        $hours->{'2_to'} = $request->{'2_to'} ?? null;
        $hours->{'3_from'} = $request->{'3_from'} ?? null;
        $hours->{'3_to'} = $request->{'3_to'} ?? null;
        $hours->{'4_from'} = $request->{'4_from'} ?? null;
        $hours->{'4_to'} = $request->{'4_to'} ?? null;
        $hours->{'5_from'} = $request->{'5_from'} ?? null;
        $hours->{'5_to'} = $request->{'5_to'} ?? null;
        $hours->{'6_from'} = $request->{'6_from'} ?? null;
        $hours->{'6_to'} = $request->{'6_to'} ?? null;
        $hours->update();

        return redirect()->route('restorants.edit',['id' => $request->rid])->withStatus(__('Working hours successfully updated!'));
    }

    public function showRegisterRestaurant()
    {
        return view('restorants.register');
    }

    public function storeRegisterRestaurant(Request $request)
    {
        //Validate first
        $request->validate([
            'name' => ['required', 'string', 'unique:restorants,name', 'max:255'],
            'name_owner' => ['required', 'string', 'max:255'],
            'email_owner' => ['required', 'string', 'email', 'unique:users,email', 'max:255'],
            'phone_owner' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        //Create the user
        //$generatedPassword = Str::random(10);
        $owner = new User;
        $owner->name = strip_tags($request->name_owner);
        $owner->email = strip_tags($request->email_owner);
        $owner->phone = strip_tags($request->phone_owner)|"";
        $owner->active = 0;
        $owner->api_token = Str::random(80);

        //Send welcome email
        $owner->notify(new WelcomeNotification($owner));

        $owner->password = null;
        $owner->save();

        //Assign role
        $owner->assignRole('owner');

        //Create Restorant
        $restorant = new Restorant;
        $restorant->name = strip_tags($request->name);
        $restorant->user_id = $owner->id;
        $restorant->description = strip_tags($request->description."");
        $restorant->minimum = $request->minimum|0;
        $restorant->lat = 0;
        $restorant->lng = 0;
        $restorant->address = "";
        $restorant->phone = strip_tags($request->phone_owner)|"";
        //$restorant->subdomain=strtolower(preg_replace('/[^A-Za-z0-9]/', '', strip_tags($request->name)));
        $restorant->active = 0;
        $restorant->subdomain = null;
        //$restorant->logo = "";
        $restorant->save();

        if(config('app.isqrsaas')||env('DIRECTLY_APPROVE_RESSTAURANT',false)){
            //QR SaaS - or directly approve
            $this->makeRestaurantActive($restorant);
            return redirect()->route('front')->withStatus(__('Thanks for registering. Plese check your email for login information!'));
        }else{
            //Foodtiger
            return redirect()->route('newrestaurant.register')->withStatus(__('Thank you for submitting your restaurant. We will review your restaurant and contact you shortly!'));
        }
    }

    private function createSubdomainFromName($name){
        $cyr = array(
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
        $lat = array(
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
        $name= str_replace( $cyr,$lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    private function makeRestaurantActive(Restorant $restorant){
        //Activate the restaurant
        $restorant->active = 1;
        $restorant->subdomain = $this->createSubdomainFromName($restorant->name);
        $restorant->update();

        $owner = $restorant->user;

        //if the restaurant is first time activated
        if($owner->password == null){
            //Activate the owner
            $generatedPassword = Str::random(10);

            $owner->password = Hash::make($generatedPassword);
            $owner->active = 1;
            $owner->update();

            //Send email to the user/owner
            $owner->notify(new RestaurantCreated($generatedPassword, $restorant, $owner));
        }
    }

    public function activateRestaurant(Restorant $restorant)
    {
        $this->makeRestaurantActive($restorant);
        return redirect()->route('restorants.index')->withStatus(__('Restaurant successfully activated.'));
    }
}
