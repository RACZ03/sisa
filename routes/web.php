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
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoutesController;
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
    Route::get('dashboard', [HomeController::class, 'home'])->name('dashboard');


	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change-password');


    // ROUTES FOR USERS
	Route::get('users', [UserController::class, 'index'])->name('users');
    Route::post('/users/store', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/validate-unique-field', [UserController::class, 'validateUniqueField']);
    Route::post('/users/change-password/{id}', [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::post('/users/change-status/{id}', [UserController::class, 'changeStatus'])->name('users.changeStatus');

    // ROUTES FOR EVENTS
    // Route::get('events', [EventController::class, 'index'])->name('events');
    // Route::post('/events/store', [EventController::class, 'store']);
    // Route::put('/events/{id}', [EventController::class, 'update']);
    // Route::delete('/events/{id}', [EventController::class, 'destroy']);
    // Route::post('/events/validate-unique-field', [EventController::class, 'validateUniqueField']);

    // ROUTES FOR TECHNOLOGIES
    Route::get('technologies', [TechnologyController::class, 'index'])->name('technologies');
    Route::post('/technologies/store', [TechnologyController::class, 'store']);
    Route::put('/technologies/{id}', [TechnologyController::class, 'update']);
    Route::delete('/technologies/{id}', [TechnologyController::class, 'destroy']);
    Route::post('/technologies/validate-unique-field', [TechnologyController::class, 'validateUniqueField']);

    // ROUTES FOR ROUTES
    Route::get('rutas', [RoutesController::class, 'index'])->name('rutas');
    Route::post('/rutas/store', [RoutesController::class, 'store']);
    Route::put('/rutas/{id}', [RoutesController::class, 'update']);
    Route::delete('/rutas/{id}', [RoutesController::class, 'destroy']);
    Route::post('/rutas/validate-unique-field', [RoutesController::class, 'validateUniqueField']);


    // ROUTES FOR MATERIALS
    Route::get('materials', [MaterialController::class, 'index'])->name('materials');
    Route::post('/materials/store', [MaterialController::class, 'store']);
    Route::put('/materials/{id}', [MaterialController::class, 'update']);
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy']);
    Route::post('/materials/validate-unique-field', [MaterialController::class, 'validateUniqueField']);

    // ROUTES FOR INVENTORY
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');

    Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::get('/inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::post('/inventory/store', [InventoryController::class, 'store']);
    Route::post('/inventory/change-status/{id}', [InventoryController::class, 'changeStatus'])->name('inventory.changeStatus');
    Route::post('/inventory/validate-unique-field', [InventoryController::class, 'validateUniqueField']);

    // ROUTES FOR REPORTS
    Route::get('reports', [ReportsController::class, 'index'])->name('reports');
    Route::post('/reports/findData', [ReportsController::class, 'findData'])->name('reports.findData');

    // ROUTES EXPORTS
    Route::get('iventory/exports/{id}', [InventoryController::class, 'exportToExcel'])->name('inventory.exports');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	// Route::get('/user-profile', [InfoUserController::class, 'create']);
	// Route::post('/user-profile', [InfoUserController::class, 'store']);
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
