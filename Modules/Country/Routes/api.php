<?php

use Illuminate\Support\Facades\Route;
use Modules\Country\Http\Controllers\CityController;

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

Route::group(['prefix' => 'cities'], function () {
    Route::get('popular', [CityController::class, 'popular']);
});
