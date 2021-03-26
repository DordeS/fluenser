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
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('message', function() {
    $message['user'] = "John Doe";
    $message['message'] = "Prueba measdlkfjals";
    $success = event(new App\Events\NewMessage($message));
    return $success;
});

Route::middleware(['auth'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');    
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/request',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');
    Route::get('/inbox',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');
    Route::get('/task',[App\Http\Controllers\TaskController::class, 'index'])->name('task');
    Route::get('/search',[App\Http\Controllers\TaskController::class, 'search'])->name('search');
    Route::get('/findInfluencers',[App\Http\Controllers\TaskController::class, 'findInfluencers']);
    Route::get('/collaborate/{user_id}',[App\Http\Controllers\CollaborateController::class, 'index'])->name('collaborate');
    Route::post('/request/save',[App\Http\Controllers\CollaborateController::class, 'saveRequest'])->name('saveRequest');
    Route::get('/payment',[App\Http\Controllers\PaymentController::class, 'index'])->name('payment');
    Route::post('/transaction',[App\Http\Controllers\PaymentController::class, 'makePayment'])->name('make-payment');
    Route::get('/{username}',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::get('editProfile/{username}',[App\Http\Controllers\ProfileController::class, 'editProfile'])->name('editProfile');
    Route::get('updateProfile/{user_id}',[App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('updateProfile');
});
