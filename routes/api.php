<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\BudgetController;

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

// <--- GRUPO DE ROTAS PROTEGIDAS POR AUTH:SANCTUM
Route::middleware('auth:sanctum')->group(function () {

    // Rotas personalizadas de transactions
    Route::prefix('transactions')->group(function () {
        Route::get('balance', [TransactionController::class, 'getBalance']);
        Route::get('period', [TransactionController::class, 'getByPeriod']);
        Route::get('summary', [TransactionController::class, 'getSummaryByPeriod']);
    });

    // Rotas de recurso
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('transactions', TransactionController::class)->except(['show']);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('budgets', BudgetController::class);
});
