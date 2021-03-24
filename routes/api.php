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

    Route::get('/requestDetail/{request_id}', [App\Http\Controllers\MessageController::class, 'requestDetaliShow']);

    Route::get('/chat/{inbox_id}', [App\Http\Controllers\MessageController::class, 'chat']);

    Route::get('/sendMessage/{inbox_id}/{message}', [App\Http\Controllers\MessageController::class, 'receiveMessage']);

    Route::get('/checkInbox/{user1_id}/{user2_id}', [App\Http\Controllers\MessageController::class, 'checkInbox']);

    Route::get('/updateRequest/{request_id}/{price}/{unit}', [App\Http\Controllers\MessageController::class, 'updateRequest']);

    Route::get('/saveRequestChat/{request_id}/{send_id}/{receive_id}/{message}', [App\Http\Controllers\MessageController::class, 'saveRequestChat']);
    
    Route::get('/acceptRequest/{request_id}', [App\Http\Controllers\MessageController::class, 'acceptRequest']);
    Route::get('/declineRequest/{request_id}', [App\Http\Controllers\MessageController::class, 'declineRequest']);
});