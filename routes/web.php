<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
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


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');



    // ROUTES FOR USERS
	Route::get('users', [UserController::class, 'index'])->name('users');
    Route::post('/users/store', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/validate-unique-field', [UserController::class, 'validateUniqueField']);

    // ROUTES FOR EVENTS
    Route::get('events', [EventController::class, 'index'])->name('events');
    Route::post('/events/store', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::post('/events/validate-unique-field', [EventController::class, 'validateUniqueField']);

    // ROUTES FOR TECHNOLOGIES
    Route::get('technologies', [EventController::class, 'index'])->name('events');
    Route::post('/technologies/store', [EventController::class, 'store']);
    Route::put('/technologies/{id}', [EventController::class, 'update']);
    Route::delete('/technologies/{id}', [EventController::class, 'destroy']);
    Route::post('/technologies/validate-unique-field', [EventController::class, 'validateUniqueField']);

     // ROUTES FOR ROUTES
     Route::get('routes', [EventController::class, 'index'])->name('events');
     Route::post('/routes/store', [EventController::class, 'store']);
     Route::put('/routes/{id}', [EventController::class, 'update']);
     Route::delete('/routes/{id}', [EventController::class, 'destroy']);
     Route::post('/routes/validate-unique-field', [EventController::class, 'validateUniqueField']);

    Route::get('inventory', function () {
		return view('pages/inventory/index');
	})->name('inventory');

    Route::get('materials', function () {
        return view('pages/materials/index');
    })->name('materials');


    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
