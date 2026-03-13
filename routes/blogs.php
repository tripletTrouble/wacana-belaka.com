<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'index')->name('blogs.index');
});