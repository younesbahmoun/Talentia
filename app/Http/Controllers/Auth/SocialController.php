<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class SocialController extends Controller
{
    public function redirect(string $provider){
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider){
        if (request()->has('error')) {
            return redirect()->route('login');
        }
        // stocker les info dans session au lieu de crrer 
        $socialUser = Socialite::driver($provider)->user();

        $user = User::where('email', $socialUser->getEmail())->first();
        if ($user) {
            Auth::login($user);
            if ($user->role !== null) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('profile.complete');
        }

        session([
            'social_user' => [
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'provider' => $provider,
            ]
        ]);

        return redirect()->route('profile.complete');
    }

}

