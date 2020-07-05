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

Auth::routes([
    'register' => false,
]);

// MessagesController
// Route::post('/messages', 'MessagesController@store')->name('messages.store');
// Route::get('/conversation/{id}', 'MessagesController@getMessagesFor')->name('conversation.allmessages');
// Route::get('/messages', 'MessagesController@index')->name('messages.index');

Route::get('/home', 'HomeController@index')->name('home');

// To keep vue router working even after browser refresh
Route::get('{path}', 'HomeController@index')->where('path', '([A-z\d\-\/_.]+)?');
