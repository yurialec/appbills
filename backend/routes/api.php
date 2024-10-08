<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('bills')->group(callback: function () {
    Route::get('/', [BillController::class, 'index']);
    Route::get('/{bill}', [BillController::class, 'show']);
    Route::post('/', [BillController::class, 'store']);
    Route::put('/{bill}', [BillController::class, 'update']);
});

Route::prefix('users')->group(callback: function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update']);
});
