<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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



Route::middleware('auth:api')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/inbox', [App\Http\Controllers\MessageController::class, 'inbox']);

    Route::get('/request', [App\Http\Controllers\MessageController::class, 'requests']);

    Route::get('/chat/{inbox_id}', [App\Http\Controllers\MessageController::class, 'chat']);

    Route::get('/sendMessage/{inbox_id}/{message}', [App\Http\Controllers\MessageController::class, 'receiveMessage']);
});

