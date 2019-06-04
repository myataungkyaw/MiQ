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




Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'Dashboard\AuthController@login');
    Route::post('logout', 'Dashboard\AuthController@logout');
    Route::post('refresh', 'Dashboard\AuthController@refresh');
    Route::post('profile', 'Dashboard\AuthController@me');

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

    Route::get('dashboard/audit_logs', 'Dashboard\AuditLogAPIController@index');
    Route::post('dashboard/audit_logs', 'Dashboard\AuditLogAPIController@store');
    Route::get('dashboard/audit_logs/{audit_logs}', 'Dashboard\AuditLogAPIController@show');
    Route::put('dashboard/audit_logs/{audit_logs}', 'Dashboard\AuditLogAPIController@update');
    Route::patch('dashboard/audit_logs/{audit_logs}', 'Dashboard\AuditLogAPIController@update');
    Route::delete('dashboard/audit_logs/{audit_logs}', 'Dashboard\AuditLogAPIController@destroy');


    Route::get('dashboard/users', 'Dashboard\UserAPIController@index');
    Route::post('dashboard/users', 'Dashboard\UserAPIController@store');
    Route::get('dashboard/users/{users}', 'Dashboard\UserAPIController@show');
    Route::put('dashboard/users/{users}', 'Dashboard\UserAPIController@update');
    Route::patch('dashboard/users/{users}', 'Dashboard\UserAPIController@update');
    Route::delete('dashboard/users/{users}', 'Dashboard\UserAPIController@destroy');
    Route::post('dashboard/users/{users}/changePassword', 'Dashboard\UserAPIController@changePasswordByForce');


    Route::get('dashboard/lines', 'Dashboard\LineAPIController@index');
    Route::post('dashboard/lines', 'Dashboard\LineAPIController@store');
    Route::get('dashboard/lines/{lines}', 'Dashboard\LineAPIController@show');
    Route::put('dashboard/lines/{lines}', 'Dashboard\LineAPIController@update');
    Route::patch('dashboard/lines/{lines}', 'Dashboard\LineAPIController@update');
    Route::delete('dashboard/lines/{lines}', 'Dashboard\LineAPIController@destroy');


   
    //Route::post('dashboard/companies', 'Dashboard\CompanyAPIController@store');
    Route::get('dashboard/companies/{companies}', 'Dashboard\CompanyAPIController@show');
    Route::post('dashboard/companies/{companies}', 'Dashboard\CompanyAPIController@update');
    //Route::patch('dashboard/companies/{companies}', 'Dashboard\CompanyAPIController@update');
    //Route::delete('dashboard/companies/{companies}', 'Dashboard\CompanyAPIController@destroy');



    Route::get('dashboard/queues/{queues}', 'Dashboard\QueueAPIController@show');
    Route::put('dashboard/queues/{queues}', 'Dashboard\QueueAPIController@update');
    Route::patch('dashboard/queues/{queues}', 'Dashboard\QueueAPIController@update');
    Route::delete('dashboard/queues/{queues}', 'Dashboard\QueueAPIController@destroy');


    //macro api list
    Route::get('dashboard/roles', 'Dashboard\MacroController@getUserRole');
    Route::get('dashboard/log_retention_periods', 'Dashboard\MacroController@getLogRetention');

    //queue Line
    Route::get('dashboard/queueLines', 'Dashboard\QueueLineAPIController@index');
    Route::post('dashboard/queueLines/addQueueLine', 'Dashboard\QueueAPIController@addQueueLine');
    Route::put('dashboard/queueLines/updateQueueLine', 'Dashboard\QueueAPIController@updateQueueLine');
    Route::put('dashboard/queueLines/updateQueuePosition', 'Dashboard\QueueAPIController@updateQueuePosition');
    Route::put('dashboard/queueLines/changeStatus', 'Dashboard\QueueAPIController@changeStatus');
    Route::post('dashboard/queueLines/updateCallNumber', 'Dashboard\QueueLineAPIController@updateCallNumber');
    
    Route::put('dashboard/queueLines/serveQueueLine/{id}', 'Dashboard\QueueAPIController@serveQueueLine');
    Route::put('dashboard/queueLines/noshowQueueLine/{id}', 'Dashboard\QueueAPIController@noshowQueueLine');
    Route::put('dashboard/queueLines/return/{id}', 'Dashboard\QueueLineAPIController@returnQ');
    Route::put('dashboard/queueLines/finish/{id}', 'Dashboard\QueueLineAPIController@finishQ');

});

Route::get('dashboard/companies', 'Dashboard\CompanyAPIController@index');

Route::get('dashboard/lines/company/{company_id}', 'Dashboard\LineAPIController@getLinesByCompany');
Route::get('dashboard/lines/desks/{company_id}', 'Dashboard\LineAPIController@getDesksByCompany');
Route::post('dashboard/queues', 'Dashboard\QueueAPIController@store');
Route::get('dashboard/companies/{companies}', 'Dashboard\CompanyAPIController@show');
Route::get('dashboard/queues', 'Dashboard\QueueAPIController@index');
Route::get('dashboard/queues_search', 'Dashboard\QueueAPIController@search');

//call => call count  add to queue line table and increase
// no show update with status


Route::group(['prefix' => 'dashboard'], function () {
    Route::resource('tags', 'Dashboard\TagAPIController');
});


Route::group(['prefix' => 'dashboard'], function () {
    Route::resource('printers', 'Dashboard\PrinterAPIController');
});
