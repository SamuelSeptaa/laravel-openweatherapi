<?php

use App\Http\Controllers\Home;
use App\Http\Controllers\Login;
use App\Http\Controllers\Register;
use App\Http\Controllers\Weather;
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

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [Register::class, 'index'])->name('register');
    Route::post('/register-in', [Register::class, 'register'])->name('register-in');
    Route::get('/login', [Login::class, 'index'])->name('login');
    Route::post('/loging-in', [Login::class, 'loging_in'])->name('loging-in');
});



Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [Login::class, 'logout'])->name('logout');
    Route::get('/home', [Home::class, 'index'])->name('home');

    Route::post('/weather/get-weather-data', [Weather::class, 'get_weather_data'])->name('get-weather-data');
    Route::post('/weather/store', [Weather::class, 'store'])->name('store-weather');
});
