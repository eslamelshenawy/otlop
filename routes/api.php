<?php

use Illuminate\Http\Request;

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


Route::post('getCity','ApiController@getCity');
Route::post('getType','ApiController@getType');
Route::post('getState','ApiController@getState');
Route::post('search','ApiController@searchRestaurant');
Route::post('getMenu','ApiController@getMenu');
Route::post('getMenuDetails','ApiController@getMenuDetails');
Route::post('register','ApiController@register');
Route::post('login','ApiController@login');
Route::post('updatePlayerId','ApiController@updatePlayerId');
Route::post('resetPassword','ApiController@resetPassword');
Route::post('getPackage','ApiController@getPackage');
Route::post('gerFAQ','ApiController@gerFAQ');
Route::post('getPrivacy','ApiController@getPrivacy');
Route::post('getOffersRestaurant','ApiController@getOffersRestaurant');
Route::post('getOffers','ApiController@getOffers');
Route::post('allRestaurants','ApiController@allRestaurants');




Route::group(['middleware'=>'auth:api','prefix'=>'auth'],function (){
    Route::get('logout', 'ApiController@logout');
    Route::get('user','ApiController@infoUser');
    Route::post('updateProfile','ApiController@updateProfile');
    Route::post('updatePassword','ApiController@updatePassword');
    Route::post('addCart','ApiController@addCart');
    Route::post('deleteCart','ApiController@deleteCart');
    Route::post('minCart','ApiController@minCart');
    Route::post('plusCart','ApiController@plusCart');
    Route::post('myCart','ApiController@myCart');
    Route::post('order','ApiController@order');
    Route::post('myOrders','ApiController@myOrders');
    Route::post('myOrderDetails','ApiController@myOrderDetails');
    Route::post('rating','ApiController@rating');
    Route::post('chargingWallets','ApiController@chargingWallets');
    Route::post('myWallets','ApiController@myWallets');
    Route::post('dateWallet','ApiController@dateWallet');
    Route::post('previousTransactions','ApiController@previousTransactions');
    Route::post('payOfRestaurant','ApiController@payOfRestaurant');
    Route::post('checkPhoneShare','ApiController@checkPhoneShare');
    Route::post('sendNotifyShare','ApiController@sendNotifyShare');
    Route::post('appletOrRefusal','ApiController@appletOrRefusal');
    Route::post('getAnswer','ApiController@getAnswer');
    Route::post('send_massage','ApiController@send_massage');
    Route::post('gettoken','ApiController@gettoken');
    Route::post('getusershare','ApiController@getusershare');
    Route::post('getanswar','ApiController@getanswar');

    Route::post('getshare','ApiController@getuser');
    Route::post('confirm','ApiController@confirm');
    Route::post('confirmShare','ApiController@confirmShare');
    Route::post('confirmFinal','ApiController@confirmFinal');


});

Route::group(['prefix'=>'driver'],function (){
    Route::post('login','DriverController@login');
    Route::get('startDispatching','DriverController@startDispatching');
    Route::get('/getReadyTrips','DriverController@startDispatching');
    Route::get('/updateDispatchingUser','DriverController@updateDispatchingUser');

    Route::group(['middleware'=>'auth:api-admin','prefix'=>'auth'],function (){
        Route::post('activeOnMap','DriverController@activeOnMap');
        Route::post('updatedLocation','DriverController@updatedLocation');
        Route::post('getAllTrips','DriverController@getAllTrips');
        Route::post('acceptOrDeclineTrip','DriverController@acceptOrDeclineTrip');
        Route::post('getDeliveryTrips','DriverController@getDeliveryTrips');
        Route::post('getDetailsOrderDelivery','DriverController@getDetailsOrderDelivery');
        Route::post('DoneDeliverOrder','DriverController@DoneDeliverOrder');



    });
});

