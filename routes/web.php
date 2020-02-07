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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/station/search', 'FlightController@getStation');
Route::get('search', 'FlightController@search');

Route::get('/schedule', 'HomeController@schedule');
Route::get('/avtopark', 'HomeController@avtopark');
Route::get('/avtopark/bus/{id}', 'HomeController@getBus');
Route::get('/schedule/{id}/route/', 'HomeController@route');
Route::post('/feedback', 'HomeController@feedback');
Route::get('/contacts', 'HomeController@page');
Route::get('/rules', 'HomeController@page');
Route::get('/insurance', 'HomeController@page');
Route::get('/personal', 'HomeController@page');
Route::get('/public_offer', 'HomeController@page');
Route::post('/checkout', 'TicketController@checkout');
Route::get('/tickets/buy', 'TicketController@create');
Route::post('/tickets/buy', 'TicketController@buy');
Route::get('/tickets/success', 'TicketController@formPay');

Route::get('/tickets/{token}', 'TicketController@generate');
Route::post('/successPay', 'TicketController@successPay');

Route::get('/about', 'HomeController@page');
Route::get('/service/{page}', 'HomeController@servicePage');
Route::post('/orderBus', 'HomeController@orderBus');