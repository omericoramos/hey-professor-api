<?php

use App\Http\Controllers\Question\{ArchiveController, DeleteController, RestoreController, StoreController, UpdateController};
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('question')->group(
    function () {
        Route::post('/store', [StoreController::class, '__invoke'])
            ->name('question.store');
        Route::put('/{question}', UpdateController::class, '__invoke')
            ->name('question.update');
        Route::delete('/{question}', DeleteController::class, '__invoke')
            ->name('question.delete');
        Route::delete('/{question}/archive', ArchiveController::class)
            ->name('question.archive');
        Route::put('/{question}/restore', RestoreController::class)
            ->name('question.restore');
    }
);
