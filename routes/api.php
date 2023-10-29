<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->name('v1.')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth:api', 'check.blacklist', 'custom.throttle:10,1'])->group(function() {

        Route::prefix('transaction')->name('transactions.')->group(function() {
            Route::post('/create', [TransactionController::class, 'store'])->name('store');
            Route::get('/list', [TransactionController::class, 'listTransactions'])->name('list');
        });

        // User routes
        Route::prefix('users')->name('users.')->group(function() {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/email/{email}', [UserController::class, 'showByEmail'])->name('showByEmail');
            Route::get('/latest', [UserController::class, 'showLatest'])->name('showLatest');
        });
        
    });
});