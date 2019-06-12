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
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('signup', 'AuthController@signup')->name('signup');
        Route::get('signup/activate/{token}', 'AuthController@signupActivate')->name('signup.activate');

        Route::group(
            [
                'middleware' => 'auth:api'
            ],
            function () {
                Route::get('logout', 'AuthController@logout')->name('logout');
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
        Route::post('create', 'PasswordResetController@create')->name('create.password.reset');
        Route::get('find/{token}', 'PasswordResetController@find')->name('find.reset.token');
        Route::post('reset', 'PasswordResetController@reset')->name('password.reset');
        Route::post('change', 'PasswordResetController@changePassword')->middleware(['auth:api', 'isAccountActive'])->name('password.change');
    }
);
Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'user'
    ],
    function () {
    Route::get('me', 'UserController@user')->middleware(['isAccountActive'])->name('user.me');
        Route::post('update', 'UserController@updateUser')->name('user.update');
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
        Route::delete('reviews/{review}/photo', 'ReviewsController@deleteReviewPhoto')->name('reviews.delete.photo');
    }
);

Route::group(
    [
        'middleware' => ['auth:api', 'isAccountActive'],
    ],
    function () {
        Route::get('accommodations', 'Objects\AccommodationsController@index')->name('accommodations.list');
        Route::get('accommodations/{accommodation}', 'Objects\AccommodationsController@show')->name('accommodation.show');
        Route::post('accommodations/{accommodation}/review', 'Objects\AccommodationsController@storeReview')->name('accommodation.store.review');
        Route::get('accommodation/{accommodation}/reviews/', 'Objects\AccommodationsController@objectReviews')->name('accommodation.reviews');
        Route::get('accommodation/reviews/', 'Objects\AccommodationsController@indexReview')->name('accommodations.list.statistics');
        Route::get('accommodation/{accommodation}/reviews/statistics', 'Objects\AccommodationsController@reviewStatistics')->name('accommodation.review.statistics');
        Route::get('accommodation/reviews/{review}', 'Objects\AccommodationsController@showReview')->name('accommodation.review');

        Route::get('attractions', 'Objects\AttractionsController@index')->name('attractions.list');
        Route::get('attractions/{attraction}', 'Objects\AttractionsController@show')->name('attraction.show');
        Route::post('attractions/{attraction}/review', 'Objects\AttractionsController@storeReview')->name('attraction.store.review');
        Route::get('attraction/{attraction}/reviews', 'Objects\AttractionsController@objectReviews')->name('attraction.reviews');
        Route::get('attraction/reviews/', 'Objects\AttractionsController@indexReview')->name('attractions.list.statistics');
        Route::get('attraction/{attraction}/reviews/statistics', 'Objects\AttractionsController@reviewStatistics')->name('attraction.review.statistics');
        Route::get('attraction/reviews/{review}', 'Objects\AttractionsController@showReview')->name('attraction.review');

        Route::get('caterings', 'Objects\CateringController@index')->name('caterings.list');
        Route::get('caterings/{catering}', 'Objects\CateringController@show')->name('catering.show');
        Route::post('caterings/{catering}/review', 'Objects\CateringController@storeReview')->name('catering.store.review');
        Route::get('catering/{catering}/reviews', 'Objects\CateringController@objectReviews')->name('catering.reviews');
        Route::get('catering/reviews/', 'Objects\CateringController@indexReview')->name('caterings.list.statistics');
        Route::get('catering/{catering}/reviews/statistics', 'Objects\CateringController@reviewStatistics')->name('catering.review.statistics');
        Route::get('catering/reviews/{review}', 'Objects\CateringController@showReview')->name('catering.review');

        Route::get('shops', 'Objects\ShopController@index')->name('shops.list');
        Route::get('shops/{shop}', 'Objects\ShopController@show')->name('shop.show');

        Route::get('events', 'Objects\EventController@index')->name('events.list');
        Route::get('events/{event}', 'Objects\EventController@show')->name('event.show');
    }
);
