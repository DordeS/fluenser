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

Route::middleware(['auth', 'CheckUnread'])->group(function() {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');    

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

    Route::get('/request',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');

    Route::get('/inbox',[App\Http\Controllers\MessageController::class, 'index'])->name('inbox');

    Route::get('/collaborations',[App\Http\Controllers\TaskController::class, 'index'])->name('task');

    Route::get('/collaborations/{request_id}',[App\Http\Controllers\TaskController::class, 'taskDetailShow'])->name('taskDetail');

    Route::get('/search',[App\Http\Controllers\TaskController::class, 'search'])->name('search');

    Route::get('/findInfluencers',[App\Http\Controllers\TaskController::class, 'findInfluencers']);

    Route::get('balance', [App\Http\Controllers\PaymentController::class, 'balance'])->name('balance');

    Route::get('referrals', [App\Http\Controllers\ReferralsController::class, 'index'])->name('referrals');
    
    Route::get('saved', [App\Http\Controllers\ProfileController::class, 'saved'])->name('saved');
    
    Route::get('/collaborate/{user_id}',[App\Http\Controllers\CollaborateController::class, 'index'])->name('collaborate');

    Route::post('/request/save',[App\Http\Controllers\CollaborateController::class, 'saveRequest'])->name('saveRequest');

    Route::get('editProfile/{username}',[App\Http\Controllers\ProfileController::class, 'editProfile'])->name('editProfile');
    
    Route::post('updateProfile/{user_id}',[App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('updateProfile');

    Route::get('leaveReview/{request_id}', [App\Http\Controllers\CollaborateController::class, 'leaveReview'])->name('leaveReview');

    Route::post('submitReview/{request_id}', [App\Http\Controllers\CollaborateController::class, 'submitReview'])->name('submitReview');
});

Route::get('/{username}',[App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

Route::get('referral/{ref_link}', [App\Http\Controllers\ReferralsController::class, 'newUser'])->name('newUser')->where('ref_link', '[a-z0-9]{128}+');