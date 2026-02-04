<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\TrackingController;
//use App\Http\Controllers\Api\ShoppingController;


Route::group(['namespace' => 'Api','prefix'=>'v1','middleware' => 'api'], function(){
    
     Route::get('app-config', [FrontendController::class, 'appconfig']);
     Route::get('slider', [FrontendController::class, 'slider']);
     Route::get('category-menu', [FrontendController::class, 'categorymenu']);
     Route::get('hotdeal-product', [FrontendController::class, 'hotdealproduct']);
     Route::get('homepage-product', [FrontendController::class, 'homepageproduct']);
     Route::get('footer-menu-left', [FrontendController::class, 'footermenuleft']);
     Route::get('footer-menu-right', [FrontendController::class, 'footermenuright']);
     Route::get('social-media', [FrontendController::class, 'socialmedia']);
     Route::get('contactinfo', [FrontendController::class, 'contactinfo']);
     Route::get('getDatalandingpage/{slug}', [FrontendController::class, 'landingpage']);
    
    
    Route::post('/saveDatalanding', [FrontendController::class, 'mydata_save']);
    Route::post('/shipping-charge', [FrontendController::class, 'shippingChargeApi']);
    Route::post('/cart/remove', [FrontendController::class, 'cartRemoveApi']);
    Route::post('/cart/increment', [FrontendController::class, 'cartMyIncrementApi']);
   // Route::post('/cart/decrement', [ShoppingController::class, 'cartMyDecrementApi']);
     
    //  Home Page Api End =================================
    
    Route::get('category/{id}', [FrontendController::class, 'catproduct']);
    

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/ajax/track-event', [App\Http\Controllers\Api\TrackingController::class, 'trackEvent'])->name('ajax.track.event');


