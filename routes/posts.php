<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->controller(App\Http\Controllers\PostController::class)
    ->group(function () {
        Route::get('/posts', 'index');
        Route::get('/posts/create', 'create');
        Route::post('/posts', 'store');
    });