<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RecoverPasswordController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('forgot-password', [RecoverPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [RecoverPasswordController::class, 'resetPasswordCode']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::prefix('bills')->group(function () {
        Route::get('/', [BillController::class, 'index']);
        Route::get('/{bill}', [BillController::class, 'show']);
        Route::post('/', [BillController::class, 'store']);
        Route::put('/{bill}', [BillController::class, 'update']);
        Route::delete('/{bill}', [BillController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});
