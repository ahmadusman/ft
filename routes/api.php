<?php

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
    //Driver
    Route::get('/driverorders', 'DriverController@getOrders')->name('driver.orders');
    Route::get('/updateorderstatus/{order}/{status}', 'DriverController@updateOrderStatus')->name('driver.updateorderstatus');
    Route::get('/updateorderlocation/{order}/{lat}/{lng}', 'DriverController@orderTracking')->name('driver.updateorderlocation');

    //Client
    Route::get('/myorders', 'ClientController@getMyOrders');
    Route::get('/mynotifications', 'ClientController@getMyNotifications');


    Route::get('/myaddresses', 'ClientController@getMyAddresses');
    Route::post('/make/order','ClientController@makeOrder')->name('make.order');
    Route::post('/make/address','ClientController@makeAddress')->name('make.address');
    Route::post('/delete/address','ClientController@deleteAddress')->name('delete.address');
    Route::get('/user/data', 'ClientController@getUseData')->name('user.getData');
});

//Driver
Route::post('/drivergettoken', 'DriverController@getToken')->name('driver.getToken');

//Client
Route::post('/clientgettoken', 'ClientController@getToken')->name('client.getToken');
Route::post('/client/register', 'ClientController@register')->name('client.register');
Route::post('/client/forgot', 'ClientController@forgot')->name('client.forgot');
Route::post('/client/loginfb','ClientController@loginFacebook');
Route::post('/client/logingoogle','ClientController@loginGoogle');
Route::get('/restorantslist', 'ClientController@getRestorants')->name('restorants.list');
Route::get('/restorant/{id}/items', 'ClientController@getRestorantItems')->name('restorant.items');
Route::get('/restaurant/{restorants}/hours', 'CartController@getRestorantHours')->name('restorant.hours');
Route::get('/deliveryfee/{res}/{adr}', 'SettingsController@getDeliveryFee')->name('delivery.fee');


Route::post('/app/settings','ClientController@getSettings')->name('app.settings');

//Route::post('send-sms','SmsController@store');
//Route::post('verify-user','SmsController@verifyContact');

