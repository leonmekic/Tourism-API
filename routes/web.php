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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(
    [
//        'middleware' => ['auth:api', 'isAccountActive'],
        'prefix' => 'administrator'
    ],
    function () {
        Route::get('accommodations', 'Web\AdminBookingController@accommodationList')->name('admin.accommodations.list');
        Route::get('accommodations/rooms/{room}', 'Web\AdminBookingController@showBookings')->name('admin.room.bookings');
    }
);
