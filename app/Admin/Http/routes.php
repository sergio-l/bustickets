<?php


Route::get('', ['as' => 'admin.dashboard', 'uses' =>'App\Admin\Http\Controllers\DashboardController@index']);
Route::get('/flight/{id}/{date}', ['as' => 'admin.flightToday', 'uses' =>'App\Admin\Http\Controllers\DashboardController@showTodayFlight']);
Route::get('stat/get','App\Admin\Http\Controllers\DashboardController@stat');
Route::get('getFlightDate','App\Admin\Http\Controllers\DashboardController@getFlightDate');
Route::post('dopFlight','App\Admin\Http\Controllers\FlightController@addFlight');
Route::post('dopFlight/edit','App\Admin\Http\Controllers\FlightController@editDopFlight');
Route::get('history/get','App\Admin\Http\Controllers\DashboardController@history');
Route::get('flight/{id}/{date}/pdf','App\Admin\Http\Controllers\DashboardController@toPdfPassengers');


Route::get('information', ['as' => 'admin.information', function () {
	$content = 'Define your information here.';
	return AdminSection::view($content, 'Information');
}]);

Route::get('/station/search', ['as' => 'admin.search', 'uses' =>'App\Admin\Http\Controllers\FlightController@search']);
Route::get('/flight/search', ['as' => 'admin.search', 'uses' =>'App\Admin\Http\Controllers\FlightController@searchRoute']);
Route::post('flights/create', ['as' => 'admin.flight', 'uses' =>'App\Admin\Http\Controllers\FlightController@create']);
Route::post('flights/{id}/edit', ['as' => 'admin.flight', 'uses' =>'App\Admin\Http\Controllers\FlightController@update']);
Route::get('flights/{id}/price', ['as' => 'admin.flight.price', 'uses' =>'App\Admin\Http\Controllers\FlightController@price']);
Route::post('flights/{id}/price', ['as' => 'admin.flight.price', 'uses' =>'App\Admin\Http\Controllers\FlightController@savePrice']);

Route::get('tickets/search-flight', ['as' => 'admin.tickets.search-flight', 'uses' =>'App\Admin\Http\Controllers\TicketController@searchFlight']);
Route::get('tickets/checkout', ['as' => 'admin.tickets.checkout', 'uses' =>'App\Admin\Http\Controllers\TicketController@createForm']);
Route::post('tickets/checkout', ['as' => 'admin.tickets.checkout', 'uses' =>'App\Admin\Http\Controllers\TicketController@create']);

Route::post('/orders/{id}/edit', ['as' => 'admin.updateTicket', 'uses' => 'App\Admin\Http\Controllers\TicketController@update']);
Route::get('orders/{id}/PDFtickets/','App\Admin\Http\Controllers\TicketController@toPDF');
Route::post('/order/search','App\Admin\Http\Controllers\OrderController@search');
Route::post('/orders/checkout','App\Admin\Http\Controllers\OrderController@checkout');
Route::get('/orders/buy/', 'App\Admin\Http\Controllers\OrderController@create');
Route::post('/orders/create','App\Admin\Http\Controllers\OrderController@storage');
Route::get('order/{id}/return/','App\Admin\Http\Controllers\TicketController@returnTicket');
Route::post('order/{id}/return/','App\Admin\Http\Controllers\TicketController@returnTicketPost');

Route::get('/stat/', 'App\Admin\Http\Controllers\StatController@index');
Route::get('/getStat/', 'App\Admin\Http\Controllers\StatController@getStat');
Route::get('/getStationsTable', 'App\Admin\Http\Controllers\StatController@getStationsTable');
Route::get('/stat/station/{id}/toPDF', 'App\Admin\Http\Controllers\StatController@pdfStationsTable');
Route::get('/stat/all/toPDF', 'App\Admin\Http\Controllers\StatController@pdfAllStations');


/*
Route::get('flights/{id}/schedule', ['as' => 'admin.flight.schedule', 'uses' =>'App\Admin\Http\Controllers\ScheduleController@sсhedule']);
Route::post('flight/addDriver', ['as' => 'admin.flight.schedule', 'uses' =>'App\Admin\Http\Controllers\ScheduleController@save']);
Route::post('flight/detachDriver', ['as' => 'admin.flight.schedule', 'uses' =>'App\Admin\Http\Controllers\ScheduleController@detachDriver']);
Route::get('scheduleJson', ['as' => 'admin.flight.schedule', 'uses' =>'App\Admin\Http\Controllers\ScheduleController@getJson']);
*/