<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'FrontController@index')->name('welcome');
Route::get('/apply', 'FrontController@showApplyForm')->name('front.applyform');
Route::post('/apply', 'FrontController@applyAsDealer')->name('front.applyasdealer');

// Shopping cart controllers
Route::post('/add-product', 'CartController@addProduct');
// Temporary route for emptying the Cart
Route::get('/empty-cart', function() {
    Cart::destroy();
});
// Cart Controller Custom Routes
Route::get('/check-cart-status', 'CartController@checkIfCartEmpty');
Route::get('/get-cart-count', 'CartController@getCartCount');
Route::post('/remove-cart-item', 'CartController@removeCartItem');

// Checkout Controller Custom Routes
Route::get('/get-stripe-client-secret', 'CheckoutController@store');

// Web Authentication controllers
Auth::routes([
    'register' => false,
]);

// Main Controller for the home page
Route::get('/home', 'HomeController@index')->name('home');

// To keep vue router working even after browser refresh
// This delegates the routing to Vue Router
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d\-\/_.]+)?');
