<?php

namespace App\Actions\Oauth;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Request;

final class HandleUser
{
    public function __invoke(Request $request)
    {
        // Handle the OAuth user data here.
        /**
         * @var \Laravel\Socialite\Two\User
         */
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'password' => null,
            'email_verified_at' => now(),
        ]);

        \Illuminate\Support\Facades\Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
