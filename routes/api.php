<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;

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

// Auth routes
Route::post('v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);
Route::post('v1/logout', [AuthController::class, 'logout'])->middleware(['auth:api', 'check.blacklist']);


// Transaction routes
Route::post('/v1/transaction/create', [TransactionController::class, 'store'])->middleware(['auth:api', 'check.blacklist']);
Route::get('/v1/transaction/list', [TransactionController::class, 'listTransactions'])->middleware(['auth:api', 'check.blacklist', 'custom.throttle:10,1']);


Route::get('/v1/users', [UserController::class, 'index']);
Route::post('/v1/user/account/create', [UserController::class, 'store']);
Route::get('v1/user/email/{email}', [UserController::class, 'showByEmail']);
Route::get('v1/user/details', [UserController::class, 'userDetail'])->middleware(['auth:api', 'check.blacklist']);

Route::get('v1/check-queue', [QueueController::class, 'checkQueue'])->middleware(['auth:api', 'check.blacklist']);
