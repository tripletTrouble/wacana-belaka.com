<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->controller(App\Http\Controllers\PostController::class)
    ->group(function () {
        Route::get('/posts', 'index')->name('posts.index');
        Route::get('/posts/create', 'create');
        Route::get('/posts/archived', 'archived')->name('posts.archived');
        Route::post('/posts', 'store');
        Route::get('/posts/{post}/edit', 'edit');
        Route::put('/posts/{post}', 'update');
        Route::post('/posts/{post}/restore', 'restore')->name('posts.restore');
        Route::delete('/posts/{post}/force', 'forceDelete')->name('posts.force');
        Route::delete('/posts/{post}', 'destroy');
        Route::get('/posts/{post}', 'show')->name('posts.show');
        Route::post('/posts/{post}/toggle-publish', 'togglePublish')->name('posts.toggle-publish');
    });