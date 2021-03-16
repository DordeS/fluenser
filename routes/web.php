<?php

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
Auth::routes();

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('message', function() {
    $message['user'] = "John Doe";
    $message['message'] = "Prueba measdlkfjals";
    $success = event(new App\Events\NewMessage($message));
    return $success;
});

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');    
    Route::get('/request',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');
    Route::get('/inbox',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');
    Route::get('/task',[App\Http\Controllers\TaskController::class, 'index'])->name('task');
    Route::get('/search',[App\Http\Controllers\TaskController::class, 'search'])->name('search');
    Route::get('/findInfluencers',[App\Http\Controllers\TaskController::class, 'findInfluencers']);
    Route::get('/collaborate/{user_id}',[App\Http\Controllers\CollaborateController::class, 'index'])->name('collaborate');
    Route::get('/profile/{user_id}',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
});