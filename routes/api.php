<?php

use App\Http\Controllers\Question\StoreController;
use App\Http\Controllers\Question\UpdateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(
    function () {
        Route::post('/question.store', [StoreController::class, '__invoke'])
            ->name('question.store');
        Route::put('question/{id}', UpdateController::class, '__invoke')
            ->name('question.update');
    }
);
