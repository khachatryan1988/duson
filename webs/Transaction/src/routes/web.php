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

//Ameria, Ineco, Arca routes
Route::get('/payment/{type}', 'Webs\Transaction\Controllers\TransactionController@callback');

//Idram routes
Route::get('/payment/idram/result', 'Webs\Transaction\Controllers\TransactionController@idramResult');
Route::get('/payment/idram/{success}', 'Webs\Transaction\Controllers\TransactionController@idramCallback');
