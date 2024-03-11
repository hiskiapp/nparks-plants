<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Version 1 Routes
Route::prefix('v1')->group(function () {
    Route::get('field-groups', 'App\Http\Controllers\API\V1\FieldGroupController@index');
    Route::get('fields', 'App\Http\Controllers\API\V1\FieldController@index');

    Route::get('plants', 'App\Http\Controllers\API\V1\PlantController@index');
    Route::get('plants/{id}', 'App\Http\Controllers\API\V1\PlantController@show');
});
