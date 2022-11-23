<?php

use App\Http\Controllers\FCMController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/withserverkey', [FCMController::class, 'withServerKey'])->name('fcm.with-server-key');
Route::get('/withaccesstoken', [FCMController::class, 'withAccessToken'])->name('fcm.with-access-token');
Route::post('/callback', [FCMController::class, 'callback'])->name('fcm.callback');
