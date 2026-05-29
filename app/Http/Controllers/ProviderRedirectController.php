<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;

class ProviderRedirectController extends Controller
{
    public function __invoke(Request $request, string $provider)
    {
        if (!in_array($provider, ['google', 'github'])) {
            return redirect()->route('login')->withErrors(['provider'=>'Invalid provider']);
        };

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Throwable $th) {
            return redirect()->route('login')->withErrors(['provider' => "Something went wrong"]);
        };
    }
}
