<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\DriverCreated;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->hasRole('admin')){
            return view('drivers.index', ['drivers' =>User::role('driver')->where(['active'=>1])->paginate(15)]);
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
            return view('drivers.create');
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
        //Validate
        $request->validate([
            'name_driver' => ['required', 'string', 'max:255'],
            'email_driver' => ['required', 'string', 'email', 'unique:users,email', 'max:255'],
            'phone_driver' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
        ]);

        //Create the driver
        $generatedPassword = Str::random(10);

        $driver = new User;
        $driver->name = strip_tags($request->name_driver);
        $driver->email = strip_tags($request->email_driver);
        $driver->phone = strip_tags($request->phone_driver);
        $driver->api_token = Str::random(80);
        //$driver->password =  Hash::make(str_random(10));
        $driver->password = Hash::make($generatedPassword);
        $driver->save();

        //Assign role
        $driver->assignRole('driver');

        //Send email to the user/driver
        $driver->notify(new DriverCreated($generatedPassword, $driver));

        return redirect()->route('drivers.index')->withStatus(__('Driver successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(User $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(User $driver)
    {
        if(auth()->user()->hasRole('admin')){
            return view('drivers.edit', compact('driver'));
        }else return redirect()->route('orders.index')->withStatus(__('No Access'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $driver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $driver)
    {
        //$driver->delete();
        $driver->active=0;
        $driver->save();

        return redirect()->route('drivers.index')->withStatus(__('Driver successfully deleted.'));
    }

    public function getToken(Request $request){
        $user = User::where(['active'=>1,'email'=>$request->email])->first();
        if($user != null){
            if(Hash::check($request->password, $user->password)){
                if($user->hasRole(['driver'])){
                    return response()->json([
                        'status' => true,
                        'token' => $user->api_token,
                        'id'=>$user->id
                    ]);
                }else{
                    return response()->json([
                        'status' => false,
                        'errMsg' => 'User is not a driver. Please create driver'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Incorrect password'
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'errMsg' => 'User not found. Incorrect email.'
            ]);
        }
    }


    public function getOrders(){

        $orders = Order::orderBy('created_at','desc');
        $orders =$orders->where(['driver_id'=>auth()->user()->id]);
        $orders =$orders->where('created_at', '>=', Carbon::today());

        //TODO -- Today orders only

        return response()->json([
            'data' => $orders->with(['items','status','restorant','client','address'])->get(),
            'driver_id'=>auth()->user()->id,
            'status' => true,
            'errMsg' => ''
        ]);
    }

    public function orderTracking(Order $order,$lat,$lng){
        $order->lat=$lat;
        $order->lng=$lng;
        $order->update();
        return response()->json([
            'status' => true,
            'message' => 'Order location updated'
        ]);

    }


    public function updateOrderStatus(Order $order, Status $status){
        $order->status()->attach($status->id,['user_id'=>auth()->user()->id,'comment'=>isset($_GET['comment'])?$_GET['comment']:""]);
        if($status->id==6){
            $order->lat=$order->restorant->lat;
            $order->lng=$order->restorant->lng;
            $order->update();
        }

        if($status->alias.""=="delivered"){
            $order->payment_status='paid';
            $order->update();
        }


        return response()->json([
            'status' => true,
            'message' => 'Order updated',
            'data'=> Order::where(['id'=>$order->id])->with(['items','status','restorant','client','address'])->get(),
        ]);
    }

    public function updateOrderLocation(Order $order, $lat,$lng){
        //Only assigned driver
        if(auth()->user()->id==$order->driver_id){
            if($order->status->pluck('alias')->last()=="picked_up"){
                $order->lat=$lat;
                $order->lng=$lng;
                $order->update();
                return response()->json([
                    'status' => true,
                    'message' => 'Order lat/lng updated',
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Order is not is status where should change lat / lng',
                ]);
            }

        }else{
            return response()->json([
                'status' => false,
                'message' => 'Order not on this driver',
                'data'=> Order::where(['id'=>$order->id])->with(['items','status','restorant','client','address'])->get(),
            ]);
        }

    }
}
