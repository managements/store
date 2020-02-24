<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware('auth')->group(function () {
    // Home
    Route::get('/', 'HomeController@index')->name('home');
    // Users
    Route::namespace('User')->group(function () {
        Route::get('psw', 'PswController@edit')->name('psw.edit');
        Route::put('psw', 'PswController@update')->name('psw.update');
        Route::middleware('admin')->group(function (){
            Route::resource('staff', 'StaffController')->except(['show']);
            Route::patch('staff/{staff}/restore', 'StaffController@restore')->name('staff.restore');
        });
    });
    // Truck
    Route::namespace('Truck')->group(function (){
        // Transport
        Route::resource('truck','TruckController')->except(['destroy']);
        // charge
        Route::resource('charge','ChargeController')->except(['show']);
    });

    // provider
    Route::namespace('Provider')->group(function (){
        // links
        Route::view('provider/links','provider.links')->name('provider.links');
        // intermediate
        Route::resource('intermediate', 'IntermediateController')->except(['show', 'destroy']);
        // Provider
        Route::resource('provider','ProviderController')->except(['destroy']);
        // BC
        Route::resource('bc','BcController')->except(['destroy','index', 'show']);
        // BL
        Route::resource('bl','BlController')->except(['destroy','index', 'show']);
    });
    // Trade
    Route::namespace('Trade')->group(function (){
        Route::view('transaction/links','trade.link')->name('transaction.links');
        Route::resource('transaction','TradeController')->except(['destroy','show']);
    });

    // Saisie
    Route::view('saisie','saisie.links')->name('saisie');

});
//todo:: charge
//todo:: intermediate
//todo:: provider
//todo:: prices
//todo:: clients
