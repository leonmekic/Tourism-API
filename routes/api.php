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

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);

Route::group(
    [
        'prefix' => 'auth'
    ],
    function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
        Route::get('signup/activate/{token}', 'AuthController@signupActivate');

        Route::group(
            [
                'middleware' => 'auth:api'
            ],
            function () {
                Route::get('logout', 'AuthController@logout');
            }
        );
    }
);

Route::group(
    [
        'namespace'  => 'Auth',
        'middleware' => 'api',
        'prefix'     => 'password'
    ],
    function () {
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
        Route::get('reset', 'PasswordResetController@view')->name('reset');
        Route::post('change', 'PasswordResetController@changePassword')->name('change');
    }
);
Route::group(
    [
        'middleware' => ['auth:api', 'isAccountActive'],
        'prefix'     => 'user'
    ],
    function () {
    Route::get('me', 'UserController@user')->name('me');
        Route::post('update', 'UserController@updateUser')->name('update');
    }
);

Route::group(
    [
        'middleware' => ['isAccountActive'],
    ],
    function () {
        Route::get('accommodations', 'AccommodationsController@index');
        Route::get('accommodations/{accommodation}', 'AccommodationsController@show');

        Route::get('attractions', 'AttractionsController@index');
        Route::get('attractions/{attraction}', 'AttractionsController@show');

        Route::get('caterings', 'CateringController@index');
        Route::get('caterings/{catering}', 'CateringController@show');

        Route::get('shops', 'ShopController@index');
        Route::get('shops/{shop}', 'ShopController@show');
    }
);


//Route::get('/test', 'test');