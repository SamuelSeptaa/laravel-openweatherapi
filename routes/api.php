<?php

use App\Http\Controllers\Login;
use App\Http\Controllers\Register;
use App\Http\Controllers\Weather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('/log-in', [Login::class, 'api_login']);
    Route::post('/register-in', [Register::class, 'register']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/weather/get-weather-data', [Weather::class, 'get_weather_data']);

    Route::get('/weather/show', [Weather::class, 'api_show']);
    Route::post('/weather/store', [Weather::class, 'store']);
    Route::delete('/weather/delete/{id}', [Weather::class, 'api_destroy']);

    Route::post('/logout', [Login::class, 'api_logout']);
});
