<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

// Rotas personalizadas de transactions antes do resource
Route::prefix('transactions')->middleware('auth:sanctum')->group(function () {
    Route::get('balance', [TransactionController::class, 'getBalance']);
    Route::get('period', [TransactionController::class, 'getByPeriod']);
    Route::get('summary', [TransactionController::class, 'getSummaryByPeriod']);
});

// Rotas de recurso
Route::apiResource('categories', CategoryController::class)->middleware('auth:sanctum');
Route::apiResource('transactions', TransactionController::class)->middleware('auth:sanctum')->except(['show']);
