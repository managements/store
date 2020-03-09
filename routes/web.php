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
    // Client
    Route::namespace('Client')->group(function (){
        Route::view('client/links','client.links')->name('client.links');
        Route::resource('client', 'ClientController')->except(['create', 'store', 'destroy']);
        Route::resource('qr-code', 'QrController')->only(['index', 'create', 'store']);
    });
    // Loading
    Route::namespace('Loading')->group(function (){
        Route::view('loading/links','loading.links')->name('loading.links');
        Route::resource('loading','LoadingController')->only(['create','store', 'edit', 'update']);
        Route::resource('unloading','UnloadingController')->only(['create','store', 'edit', 'update']);
    });
    // Claim
    Route::namespace('Claim')->group(function (){
        Route::get('claim/search','ClaimController@search')->name('claim.search');
        Route::resource('claim','ClaimController')->only(['index']);
        Route::resource('provider/{provider}/claim','ClaimController')->except(['show','index']);
        Route::get('debt/search','DebtController@search')->name('debt.search');
        Route::resource('debt','DebtController')->only(['index']);
        Route::resource('client/{client}/debt','DebtController')->except(['show','index']);
    });
    // prices
    Route::namespace('Product')->group(function () {
        Route::resource('price','ProductController')->only(['create', 'store']);
        Route::resource('client/{client}/remise', 'DiscountController')->only(['create', 'store']);
    });
    // store
    Route::namespace('Store')->group(function (){
        Route::resource('store/charge_store','ChargeStoreController')->except(['index', 'show']);
        Route::get('store/links','StoreController@links')->name('store.links');
        Route::get('store/stock/{provider?}','StoreController@stock')->name('store.stock');
        Route::get('store/caisse/{provider?}','StoreController@caisse')->name('store.caisse');
        Route::get('store/charge/{provider?}','StoreController@charge')->name('store.charge');
    });
    // Account
    Route::namespace('Account')->group(function (){
        Route::get('account/links','AccountTypeController@links')->name('account.links');
        Route::get('account-type/{type}','AccountTypeController@show')->name('account.type.show');
        Route::get('account/{account}','AccountController@show')->name('account.show');
    });
});
