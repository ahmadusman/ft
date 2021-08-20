<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plans;
use App\User;

class PlansController extends Controller
{
    private function adminOnly(){
        if(!auth()->user()->hasRole('admin')){
            abort(403, 'Unauthorized action.');
        }
    }

    public function current(){
        //The curent plan -- access for owner only
        if(!auth()->user()->hasRole('owner')){
            abort(403, 'Unauthorized action.');
        }

        
        
        $plans=Plans::get()->toArray();
        $colCounter=[4,12,6,4,3,2,2,2,2,2,2,2,2,2,2,2,2,2];
        return view('plans.current',['col'=>$colCounter[count($plans)],'plans'=>$plans,'currentPlan'=>Plans::findOrFail(auth()->user()->mplanid())]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Plans $plans)
    {   
        $this->adminOnly();
        return view('plans.index', ['plans' => $plans->paginate(10)]);
    }

    public function paddle(Request $request){
        //Email - find the user
        $email=$request->email;
        $user=User::where('email',$email)->firstOrFail();

        //subscription_id -- Find the plan
        $subscription_plan_id=$request->subscription_plan_id;
        $plan=Plans::where('paddle_id',$subscription_plan_id)->firstOrFail();

        //Status is to decide what to do
        $status=$request->status;

        if($status=="active"||$status=="trialing"){
            //Assign the user this plan
            $user->plan_id=$plan->id;
            $user->plan_status=$status;
            $user->cancel_url=$request->cancel_url;
            $user->update_url=$request->update_url;
            $user->subscription_plan_id=$request->subscription_plan_id;
            $user->update();

        }

        if($stauts=="deleted"){
            //Remove assigned plan to user
            $user->plan_id=null;
            $user->plan_status="";
            $user->cancel_url="";
            $user->update_url="";
            $user->subscription_plan_id=null;
            $user->update();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->adminOnly();
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->adminOnly();
        $plan = new Plans;
        $plan->name = strip_tags($request->name);
        $plan->price = strip_tags($request->price);
        $plan->limit_items = strip_tags($request->limit_items);
        $plan->limit_orders = 0;
        $plan->paddle_id = strip_tags($request->paddle_id);
        $plan->period = $request->period == "monthly" ? 1 : 2;

        $plan->save();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Plans $plan)
    {
        $this->adminOnly();
        return view('plans.edit',['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plans $plan)
    {
        $this->adminOnly();
        $plan->name = strip_tags($request->name);
        $plan->price = strip_tags($request->price);
        $plan->limit_items = strip_tags($request->limit_items);
        $plan->limit_orders = 0;
        $plan->paddle_id = strip_tags($request->paddle_id);
        $plan->period = $request->period == "monthly" ? 1 : 2;
        $plan->description=$request->description;
        $plan->features=$request->features;

        $plan->update();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plans $plan)
    {
        $this->adminOnly();
        $plan->delete();

        return redirect()->route('plans.index')->withStatus(__('Plan successfully deleted!'));
    }
}
