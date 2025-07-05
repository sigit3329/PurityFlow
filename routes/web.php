<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TDSController;

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

Route::get('/', function () { return view('home');})->name('home');

Route::get('/get', [TDSController::class, 'get']);

Route::get('/tds', [TDSController::class, 'index'])->name('tds');
Route::post('/tds', [TDSController::class, 'store']);

Route::get('/values/{tdsValue}/{getTotalGalonFromEEPROM}', [App\Http\Controllers\TDSController::class, 'value']);