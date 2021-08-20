<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settings;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;

class SettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected static $currencies;
    protected $imagePath="/uploads/settings/";

    public function index(Settings $settings)
    {
        if(auth()->user()->hasRole('admin')){
            $curreciesArr = [];
            static::$currencies = require __DIR__.'/../../../config/money.php';
            foreach(static::$currencies as $key => $value){
                array_push($curreciesArr, $key);
            }

            return view('settings.index', ['settings' => $settings->first(), 'currencies' => $curreciesArr]);
        }else return redirect()->route('orders.index')->withStatus(__('No Access'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('settings.index');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $settings = Settings::find($id);

        $settings->site_name = strip_tags($request->site_name);
        $settings->description = strip_tags($request->site_description);
        $settings->header_title = $request->header_title;
        $settings->header_subtitle = $request->header_subtitle;
        $settings->facebook = strip_tags($request->facebook) ? strip_tags($request->facebook) : "";
        $settings->instagram = strip_tags($request->instagram) ? strip_tags($request->instagram) : "";
        $settings->playstore = strip_tags($request->playstore) ? strip_tags($request->playstore) : "";
        $settings->appstore = strip_tags($request->appstore) ? strip_tags($request->appstore) : "";
        $settings->typeform = strip_tags($request->typeform) ? strip_tags($request->typeform) : "";
        $settings->mobile_info_title = strip_tags($request->mobile_info_title) ? strip_tags($request->mobile_info_title) : "";
        $settings->mobile_info_subtitle = strip_tags($request->mobile_info_subtitle) ? strip_tags($request->mobile_info_subtitle) : "";
        $settings->delivery = (double)$request->delivery;
        //$settings->order_options = $request->order_options;




        if($request->hasFile('site_logo')){
            $settings->site_logo=$this->saveImageVersions(
                $this->imagePath,
                $request->site_logo,
                [
                    ['name'=>'logo','type'=>"png"],
                ]
            );
        }

        if($request->hasFile('search')){
            $settings->search=$this->saveImageVersions(
                $this->imagePath,
                $request->search,
                [
                    ['name'=>'cover'],
                ]
            );
        }

        if($request->hasFile('restorant_details_image')){
            $settings->restorant_details_image=$this->saveImageVersions(
                $this->imagePath,
                $request->restorant_details_image,
                [
                    ['name'=>'large','w'=>590,'h'=>400],
                    ['name'=>'thumbnail','w'=>200,'h'=>200]
                ]
            );
        }

        //restorant_details_cover_image
        if($request->hasFile('restorant_details_cover_image')){
            $settings->restorant_details_cover_image=$this->saveImageVersions(
                $this->imagePath,
                $request->restorant_details_cover_image,
                [
                    ['name'=>'cover','w'=>2000,'h'=>1000]
                ]
            );
        }

        $settings->update();

        return redirect()->route('settings.index')->withStatus(__('Settings successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getDeliveryFee()
    {
        return response()->json([
            'fee' => Settings::findOrFail(1)->delivery,
            'status' => true,
            'errMsg' => ''
        ]);
    }
}
