<?php

use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('bills')->group(function () {
    Route::get('/', [BillController::class, 'index']);
    Route::get('/{bill}', [BillController::class, 'show']);
    Route::post('/', [BillController::class, 'store']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
});
