<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TwoFactorAuthenticationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('user-password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance.edit');

    // Post categories management
    Route::get('post-categories', [\App\Http\Controllers\CategoryController::class, 'index'])
        ->name('settings.post-categories.index');

    Route::post('post-categories', [\App\Http\Controllers\CategoryController::class, 'store'])
        ->name('post-categories.store');

    Route::put('post-categories/{postCategory}', [\App\Http\Controllers\CategoryController::class, 'update'])
        ->name('post-categories.update');

    Route::delete('post-categories/{postCategory}', [\App\Http\Controllers\CategoryController::class, 'destroy'])
        ->name('post-categories.destroy');

    Route::get('settings/two-factor', [TwoFactorAuthenticationController::class, 'show'])
        ->name('two-factor.show');
});
