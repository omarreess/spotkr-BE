<?php

use App\Http\Controllers\SelectMenuController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'select_menu'], function(){
    Route::group(['prefix' => 'countries'], function(){
        Route::get('', [SelectMenuController::class, 'countries']);
        Route::get('{country}/cities', [SelectMenuController::class, 'cities']);
    });
});
