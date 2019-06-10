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
        'middleware' => ['auth:api', 'isAccountActive'],
    ],
    function () {
        Route::get('reviews', 'ReviewsController@index')->name('reviews.index');
        Route::get('reviews/{review}', 'ReviewsController@show')->name('reviews.show');
        Route::put('reviews/{review}', 'ReviewsController@update')->name('reviews.update');
        Route::delete('reviews/{review}', 'ReviewsController@delete')->name('reviews.delete');
        Route::delete('reviews/{review}/photo', 'ReviewsController@deleteReviewPhoto');
    }
);

Route::group(
    [
        'middleware' => ['auth:api', 'isAccountActive'],
    ],
    function () {
        Route::get('accommodations', 'AccommodationsController@index');
        Route::get('accommodations/{accommodation}', 'AccommodationsController@show');
        Route::post('accommodations/{accommodation}/review', 'AccommodationsController@storeReview');
        Route::get('accommodation/{accommodation}/reviews/', 'AccommodationsController@objectReviews');
        Route::get('accommodation/reviews/', 'AccommodationsController@indexReview');
        Route::get('accommodation/{accommodation}/reviews/statistics', 'AccommodationsController@reviewStatistics');
        Route::get('accommodation/reviews/{review}', 'AccommodationsController@showReview') ;

        Route::get('attractions', 'AttractionsController@index');
        Route::get('attractions/{attraction}', 'AttractionsController@show');
        Route::post('attractions/{attraction}/review', 'AttractionsController@storeReview');
        Route::get('attraction/{attraction}/reviews', 'AttractionsController@objectReviews');
        Route::get('attraction/reviews/', 'AttractionsController@indexReview');
        Route::get('attraction/{attraction}/reviews/statistics', 'AttractionsController@reviewStatistics');
        Route::get('attraction/reviews/{review}', 'AttractionsController@showReview');

        Route::get('caterings', 'CateringController@index');
        Route::get('caterings/{catering}', 'CateringController@show');
        Route::post('caterings/{catering}/review', 'CateringController@storeReview');
        Route::get('catering/{catering}/reviews', 'CateringController@objectReviews');
        Route::get('catering/reviews/', 'CateringController@indexReview');
        Route::get('catering/{catering}/reviews/statistics', 'CateringController@reviewStatistics');
        Route::get('catering/reviews/{review}', 'CateringController@showReview');

        Route::get('shops', 'ShopController@index');
        Route::get('shops/{shop}', 'ShopController@show');

        Route::get('events', 'EventController@index');
        Route::get('events/{event}', 'EventController@show');
    }
);
