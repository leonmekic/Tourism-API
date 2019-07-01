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

//Route::middleware('auth:api')->get(
//    '/user',
//    function (Request $request) {
//        return $request->user();
//    }
//);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::group(
    [
        'prefix' => 'auth'
    ],
    function () {
        Route::post('login', 'Auth\AuthController@login')->name('login');
        Route::post('signup', 'Auth\AuthController@signup')->name('signup');
        Route::get('signup/activate/{token}', 'Auth\AuthController@signupActivate')->name('signup.activate');

        Route::group(
            [
                'middleware' => 'auth:api'
            ],
            function () {
                Route::get('logout', 'Auth\AuthController@logout')->name('logout');
            }
        );
    }
);

Route::group(
    [
        'middleware' => 'api',
        'prefix'     => 'password'
    ],
    function () {
        Route::post('create', 'Auth\PasswordResetController@create')->name('create.password.reset');
        Route::get('find/{token}', 'Auth\PasswordResetController@find')->name('find.reset.token');
        Route::post('reset', 'Auth\PasswordResetController@reset')->name('password.reset');
        Route::post('change', 'Auth\PasswordResetController@changePassword')->middleware(['auth:api', 'isAccountActive'])->name('password.change');
    }
);
Route::group(
    [
        'middleware' => 'auth:api',
        'prefix'     => 'user'
    ],
    function () {
    Route::get('me', 'User\UserController@user')->middleware(['isAccountActive'])->name('user.me');
        Route::post('update', 'User\UserController@updateUser')->name('user.update');
    }
);

Route::group(
    [
        'middleware' => ['auth:api', 'isAccountActive'],
    ],
    function () {
        Route::put('reviews/{review}', 'Review\ReviewsController@update')->name('reviews.update');
        Route::delete('reviews/{review}', 'Review\ReviewsController@delete')->name('reviews.delete');
        Route::delete('reviews/{review}/photo', 'Review\ReviewsController@deleteReviewPhoto')->name('reviews.delete.photo');
    }
);

Route::group(
    [
        'middleware' => ['auth:api', 'isAccountActive'],
        'prefix' => 'categories'
    ],
    function () {
        Route::get('/', 'Categories\CategoriesController@index')->name('categories.list');

        Route::get('accommodations', 'Categories\AccommodationsController@index')->name('accommodations.list');
        Route::get('accommodations/{accommodation}', 'Categories\AccommodationsController@show')->name('accommodation.show');
        Route::post('accommodations/{accommodation}/review', 'Categories\AccommodationsController@storeReview')->name('accommodation.store.review');
        Route::get('accommodation/{accommodation}/reviews/', 'Categories\AccommodationsController@objectReviews')->name('accommodation.reviews');
        Route::get('accommodation/reviews/', 'Categories\AccommodationsController@indexReview')->name('accommodations.list.statistics');
        Route::get('accommodation/{accommodation}/reviews/statistics', 'Categories\AccommodationsController@reviewStatistics')->name('accommodation.review.statistics');

        Route::get('attractions', 'Categories\AttractionsController@index')->name('attractions.list');
        Route::get('attractions/{attraction}', 'Categories\AttractionsController@show')->name('attraction.show');
        Route::post('attractions/{attraction}/review', 'Categories\AttractionsController@storeReview')->name('attraction.store.review');
        Route::get('attraction/{attraction}/reviews', 'Categories\AttractionsController@objectReviews')->name('attraction.reviews');
        Route::get('attraction/reviews/', 'Categories\AttractionsController@indexReview')->name('attractions.list.statistics');
        Route::get('attraction/{attraction}/reviews/statistics', 'Categories\AttractionsController@reviewStatistics')->name('attraction.review.statistics');

        Route::get('caterings', 'Categories\CateringController@index')->name('caterings.list');
        Route::get('caterings/{catering}', 'Categories\CateringController@show')->name('catering.show');
        Route::post('caterings/{catering}/review', 'Categories\CateringController@storeReview')->name('catering.store.review');
        Route::get('catering/{catering}/reviews', 'Categories\CateringController@objectReviews')->name('catering.reviews');
        Route::get('catering/reviews/', 'Categories\CateringController@indexReview')->name('caterings.list.statistics');
        Route::get('catering/{catering}/reviews/statistics', 'Categories\CateringController@reviewStatistics')->name('catering.review.statistics');

        Route::get('shops', 'Categories\ShopController@index')->name('shops.list');
        Route::get('shops/{shop}', 'Categories\ShopController@show')->name('shop.show');

        Route::get('events', 'Categories\EventController@index')->name('events.list');
        Route::get('events/{event}', 'Categories\EventController@show')->name('event.show');

        Route::get('news/list/{locale?}', function ($locale = 'en') {
            return app('App\Http\Controllers\Categories\NewsController')->index($locale);
        })->name('news.list');

        Route::get('news/show/{news}/{locale?}', function ($news, $locale = 'en') {
            $news = \App\Models\News::find($news);

            return app('App\Http\Controllers\Categories\NewsController')->show($news,$locale);
        })->name('news.show');
        Route::post('news/{news}/review', 'Categories\NewsController@storeReview')->name('news.store.review');
        Route::get('news/{news}/reviews', 'Categories\NewsController@objectReviews')->name('news.reviews');

    }
);
