<?php

use App\Actions\Oauth\HandleUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get(
        '/oauth/redirect',
        fn() => Socialite::driver('google')->redirect()
    )->name('oauth.redirect');

    Route::get(
        '/oauth/callback',
        HandleUser::class
    )->name('oauth.callback');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/posts.php';
require __DIR__ . '/blogs.php';
