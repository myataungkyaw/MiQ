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

Route::get('/', function () {
    return view('welcome');
});


//  Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/home', 'HomeController@index');

Route::middleware('auth')->group(function () {

    Route::get('dashboard/auditLogs', ['as'=> 'dashboard.auditLogs.index', 'uses' => 'Dashboard\AuditLogController@index']);
    Route::post('dashboard/auditLogs', ['as'=> 'dashboard.auditLogs.store', 'uses' => 'Dashboard\AuditLogController@store']);
    Route::get('dashboard/auditLogs/create', ['as'=> 'dashboard.auditLogs.create', 'uses' => 'Dashboard\AuditLogController@create']);
    Route::put('dashboard/auditLogs/{auditLogs}', ['as'=> 'dashboard.auditLogs.update', 'uses' => 'Dashboard\AuditLogController@update']);
    Route::patch('dashboard/auditLogs/{auditLogs}', ['as'=> 'dashboard.auditLogs.update', 'uses' => 'Dashboard\AuditLogController@update']);
    Route::delete('dashboard/auditLogs/{auditLogs}', ['as'=> 'dashboard.auditLogs.destroy', 'uses' => 'Dashboard\AuditLogController@destroy']);
    Route::get('dashboard/auditLogs/{auditLogs}', ['as'=> 'dashboard.auditLogs.show', 'uses' => 'Dashboard\AuditLogController@show']);
    Route::get('dashboard/auditLogs/{auditLogs}/edit', ['as'=> 'dashboard.auditLogs.edit', 'uses' => 'Dashboard\AuditLogController@edit']);


    Route::get('dashboard/users', ['as'=> 'dashboard.users.index', 'uses' => 'Dashboard\UserController@index']);
    Route::post('dashboard/users', ['as'=> 'dashboard.users.store', 'uses' => 'Dashboard\UserController@store']);
    Route::get('dashboard/users/create', ['as'=> 'dashboard.users.create', 'uses' => 'Dashboard\UserController@create']);
    Route::put('dashboard/users/{users}', ['as'=> 'dashboard.users.update', 'uses' => 'Dashboard\UserController@update']);
    Route::patch('dashboard/users/{users}', ['as'=> 'dashboard.users.update', 'uses' => 'Dashboard\UserController@update']);
    Route::delete('dashboard/users/{users}', ['as'=> 'dashboard.users.destroy', 'uses' => 'Dashboard\UserController@destroy']);
    Route::get('dashboard/users/{users}', ['as'=> 'dashboard.users.show', 'uses' => 'Dashboard\UserController@show']);
    Route::get('dashboard/users/{users}/edit', ['as'=> 'dashboard.users.edit', 'uses' => 'Dashboard\UserController@edit']);


    Route::get('dashboard/lines', ['as'=> 'dashboard.lines.index', 'uses' => 'Dashboard\LineController@index']);
    Route::post('dashboard/lines', ['as'=> 'dashboard.lines.store', 'uses' => 'Dashboard\LineController@store']);
    Route::get('dashboard/lines/create', ['as'=> 'dashboard.lines.create', 'uses' => 'Dashboard\LineController@create']);
    Route::put('dashboard/lines/{lines}', ['as'=> 'dashboard.lines.update', 'uses' => 'Dashboard\LineController@update']);
    Route::patch('dashboard/lines/{lines}', ['as'=> 'dashboard.lines.update', 'uses' => 'Dashboard\LineController@update']);
    Route::delete('dashboard/lines/{lines}', ['as'=> 'dashboard.lines.destroy', 'uses' => 'Dashboard\LineController@destroy']);
    Route::get('dashboard/lines/{lines}', ['as'=> 'dashboard.lines.show', 'uses' => 'Dashboard\LineController@show']);
    Route::get('dashboard/lines/{lines}/edit', ['as'=> 'dashboard.lines.edit', 'uses' => 'Dashboard\LineController@edit']);


    Route::get('dashboard/companies', ['as'=> 'dashboard.companies.index', 'uses' => 'Dashboard\CompanyController@index']);
     Route::post('dashboard/companies', ['as'=> 'dashboard.companies.store', 'uses' => 'Dashboard\CompanyController@store']);
     Route::get('dashboard/companies/create', ['as'=> 'dashboard.companies.create', 'uses' => 'Dashboard\CompanyController@create']);
    Route::post('dashboard/companies/{companies}', ['as'=> 'dashboard.companies.update', 'uses' => 'Dashboard\CompanyController@update']);
     Route::patch('dashboard/companies/{companies}', ['as'=> 'dashboard.companies.update', 'uses' => 'Dashboard\CompanyController@update']);
     Route::delete('dashboard/companies/{companies}', ['as'=> 'dashboard.companies.destroy', 'uses' => 'Dashboard\CompanyController@destroy']);
    Route::get('dashboard/companies/{companies}', ['as'=> 'dashboard.companies.show', 'uses' => 'Dashboard\CompanyController@show']);
    Route::get('dashboard/companies/{companies}/edit', ['as'=> 'dashboard.companies.edit', 'uses' => 'Dashboard\CompanyController@edit']);


    Route::get('dashboard/queues', ['as'=> 'dashboard.queues.index', 'uses' => 'Dashboard\QueueController@index']);
    Route::post('dashboard/queues', ['as'=> 'dashboard.queues.store', 'uses' => 'Dashboard\QueueController@store']);
    Route::get('dashboard/queues/create', ['as'=> 'dashboard.queues.create', 'uses' => 'Dashboard\QueueController@create']);
    Route::put('dashboard/queues/{queues}', ['as'=> 'dashboard.queues.update', 'uses' => 'Dashboard\QueueController@update']);
    Route::patch('dashboard/queues/{queues}', ['as'=> 'dashboard.queues.update', 'uses' => 'Dashboard\QueueController@update']);
    Route::delete('dashboard/queues/{queues}', ['as'=> 'dashboard.queues.destroy', 'uses' => 'Dashboard\QueueController@destroy']);
    Route::get('dashboard/queues/{queues}', ['as'=> 'dashboard.queues.show', 'uses' => 'Dashboard\QueueController@show']);
    Route::get('dashboard/queues/{queues}/edit', ['as'=> 'dashboard.queues.edit', 'uses' => 'Dashboard\QueueController@edit']);
});




Route::group(['prefix' => 'dashboard'], function () {
    Route::resource('tags', 'Dashboard\TagController', ["as" => 'dashboard']);
});


Route::group(['prefix' => 'dashboard'], function () {
    Route::resource('printers', 'Dashboard\PrinterController', ["as" => 'dashboard']);
});
