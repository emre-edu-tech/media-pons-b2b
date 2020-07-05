<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResources([
    'users' => 'API\UsersController',
    'dealers' => 'API\DealersController',
]);

// DealersController
Route::post('/accept-dealer', 'API\DealersController@acceptDealer')->name('dealers.accept');

// Users Controller
Route::get('/profile', 'API\UsersController@profile')->name('users.profile');
Route::put('/profile', 'API\UsersController@updateProfile')->name('users.profile.update');
Route::get('/get-users','API\UsersController@getAllUsers')->name('users.all');
