<?php

use App\Http\Controllers\api\CustomerController;
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




Route::group(['prefix' => 'api'], function () {
    Route::post('register', [CustomerController::class, 'register']);
    Route::post('login', [CustomerController::class, 'login']);
});


// Protected routes that require authentication
Route::middleware('auth:api')->group(function () {
    Route::get('customer/profile', [CustomerController::class, 'profile']);
    // Route::put('customer/update', 'CustomerController@update');
    // Route::post('logout', 'AuthController@logout');
});