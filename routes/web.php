<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontEndController@index')->name('front');
Route::get('/'.env('URL_ROUTE','restaurant').'/{alias}', 'FrontEndController@restorant')->name('vendor');
Route::get('/city/{city}','FrontEndController@showStores')->name('show.stores');

Route::post('/search/location', 'FrontEndController@getCurrentLocation')->name('search.location');

Auth::routes(['register' => !config('app.isqrsaas')]);



Route::get('/home', 'HomeController@index')->name('home');

Route::post('/paddle', 'PlansController@paddle')->name('paddle');

//Route::group(['middleware' => 'auth'], function () {
Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
    Route::post('/user/push', 'UserController@checkPushNotificationId');

    Route::resource('restorants', 'RestorantController');
    Route::resource('cities', 'CitiesController');
    Route::get('/cities/del/{city}', 'CitiesController@destroy')->name('cities.delete');

    Route::post('/updateres/location/{restorant}', 'RestorantController@updateLocation');
    //Route::get('/get/rlocation/{restorant}', 'RestorantController@getLocation');
    //Route::post('/updateres/radius/{restorant}', 'RestorantController@updateRadius');
    Route::post('/updateres/delivery/{restorant}', 'RestorantController@updateDeliveryArea');
    Route::post('/import/restaurants', 'RestorantController@import')->name('import.restaurants');
    Route::get('/restaurant/{restorant}/activate', 'RestorantController@activateRestaurant')->name('restaurant.activate');
    Route::post('/restaurant/workinghours', 'RestorantController@workingHours')->name('restaurant.workinghours');

    Route::prefix('finances')->name('finances.')->group(function () {
        Route::get('admin', 'FinanceController@adminFinances')->name('admin');
        Route::get('owner', 'FinanceController@ownerFinances')->name('owner');
    });

    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('connect', 'FinanceController@connect')->name('connect');
    });

    Route::resource('reviews', 'ReviewsController');
    Route::get('/reviewsdelete/{rating}', 'ReviewsController@destroy')->name('reviews.destroyget');
   

    Route::resource('drivers', 'DriverController');
    Route::resource('clients', 'ClientController');
    Route::resource('orders', 'OrderController');
    Route::post('/rating/{order}', 'OrderController@rateOrder')->name('rate.order');
    Route::get('/check/rating/{order}', 'OrderController@checkOrderRating')->name('check.rating');

    Route::get('ordertracingapi/{order}', 'OrderController@orderLocationAPI');
    Route::get('liveapi', 'OrderController@liveapi');

    Route::get('live', 'OrderController@live');
    Route::get('/updatestatus/{alias}/{order}', ['as' => 'update.status', 'uses'=>'OrderController@updateStatus']);

    Route::resource('settings', 'SettingsController');

	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);

    Route::resource('items', 'ItemsController');
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('list/{restorant}', 'ItemsController@indexAdmin')->name('admin');

        // Options
        Route::get('options/{item}', 'Items\OptionsController@index')->name('options.index');
        Route::get('options/{option}/edit', 'Items\OptionsController@edit')->name('options.edit');
        Route::get('options/{item}/create', 'Items\OptionsController@create')->name('options.create');
        Route::post('options/{item}', 'Items\OptionsController@store')->name('options.store');
        Route::put('options/{option}', 'Items\OptionsController@update')->name('options.update');
        Route::get('options/del/{option}', 'Items\OptionsController@destroy')->name('options.delete');


        // Variants
        Route::get('variants/{item}', 'Items\VariantsController@index')->name('variants.index');
        Route::get('variants/{variant}/edit', 'Items\VariantsController@edit')->name('variants.edit');
        Route::get('variants/{item}/create', 'Items\VariantsController@create')->name('variants.create');
        Route::post('variants/{item}', 'Items\VariantsController@store')->name('variants.store');
        Route::put('variants/{variant}', 'Items\VariantsController@update')->name('variants.update');
        
        Route::get('variants/del/{variant}', 'Items\VariantsController@destroy')->name('variants.delete');

    });


    Route::post('/import/items', 'ItemsController@import')->name('import.items');
    Route::post('/item/change/{item}', 'ItemsController@change');
    Route::post('/{item}/extras', 'ItemsController@storeExtras')->name('extras.store');
    Route::post('/{item}/extras/edit', 'ItemsController@editExtras')->name('extras.edit');
    Route::delete('/{item}/extras/{extras}', 'ItemsController@deleteExtras')->name('extras.destroy');

    Route::resource('categories', 'CategoriesController');

    Route::resource('addresses', 'AddressControler');
    //Route::post('/order/address','AddressControler@orderAddress')->name('order.address');
    Route::get('/new/address/autocomplete','AddressControler@newAddressAutocomplete');
    Route::post('/new/address/details','AddressControler@newAdressPlaceDetails');
    Route::post('/address/delivery','AddressControler@AddressInDeliveryArea');

    Route::post('/change/{page}', 'PagesController@change')->name('changes');

    Route::post('ckeditor/image_upload', 'CKEditorController@upload')->name('upload');
    Route::get('/payment','PaymentController@view')->name('payment.view');
    Route::post('/make/payment','PaymentController@payment')->name('make.payment');

    Route::get('/cart-checkout', 'CartController@cart')->name('cart.checkout');

    Route::resource('plans','PlansController');
    Route::get('/plan','PlansController@current')->name('plans.current');

    Route::get('qr','QRController@index')->name('qr');

    Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
    Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');
});

Route::get('/footer-pages', 'PagesController@getPages');
Route::get('/cart-getContent', 'CartController@getContent')->name('cart.getContent');
Route::post('/cart-add', 'CartController@add')->name('cart.add');
Route::post('/cart-remove', 'CartController@remove')->name('cart.remove');
Route::get('/cart-update', 'CartController@update')->name('cart.update');
Route::get('/cartinc/{item}', 'CartController@increase')->name('cart.increase');
Route::get('/cartdec/{item}', 'CartController@decrease')->name('cart.decrease');


Route::post('/order', 'OrderController@store')->name('order.store');

Route::resource('pages', 'PagesController');

Route::get('/login/google', 'Auth\LoginController@googleRedirectToProvider')->name('google.login');
Route::get('/login/google/redirect', 'Auth\LoginController@googleHandleProviderCallback');

Route::get('/login/facebook', 'Auth\LoginController@facebookRedirectToProvider')->name('facebook.login');
Route::get('/login/facebook/redirect', 'Auth\LoginController@facebookHandleProviderCallback');

Route::get('/new/restaurant/register', 'RestorantController@showRegisterRestaurant')->name('newrestaurant.register');
Route::post('/new/restaurant/register/store', 'RestorantController@storeRegisterRestaurant')->name('newrestaurant.store');

Route::get('phone/verify', 'PhoneVerificationController@show')->name('phoneverification.notice');
Route::post('phone/verify', 'PhoneVerificationController@verify')->name('phoneverification.verify');

Route::get('/get/rlocation/{restorant}', 'RestorantController@getLocation');
Route::get('/items/variants/{variant}/extras', 'Items\VariantsController@extras')->name('items.variants.extras');


