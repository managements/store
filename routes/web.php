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

    Route::middleware('store')->group(function (){
        Route::namespace('Truck')->group(function (){
            Route::resource('truck','TruckController')->except(['destroy']);
        });
    });
});




