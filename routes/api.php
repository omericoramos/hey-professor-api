<?php

use App\Http\Controllers\Question\StoreController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(
    function () {
        Route::post('/question.store', [StoreController::class, '__invoke'])->name('question.store');
    }
);
