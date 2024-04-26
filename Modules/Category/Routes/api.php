<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\ParentCategoryController;

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

Route::group(['prefix' => 'categories'], function () {
    Route::get('', [ParentCategoryController::class, 'parentCategories']);
    Route::get('{parentCategory}/sub_categories', [ParentCategoryController::class, 'subCategories']);
});
