<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\UserCheck;
use App\Http\Middleware\AdminCheckq;

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


// Route::post('register', 'UserController@register');
// Route::post('login', 'UserController@login');


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [UserController::class, 'logout']);

    Route::group(['prefix' => 'public', ], function () {
        Route::resource('ticket', AdminController::class, ['only' => ['index']]);
        Route::resource('transaction', TransactionController::class, ['only' => ['store', 'index']]);
    });
    
    Route::group(['prefix' => 'admin'], function () {
        Route::resource('transaction', TransactionController::class, ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
        Route::resource('ticket', AdminController::class, ['only' => ['index', 'store', 'update', 'destroy', 'show']]);        
    });
});


