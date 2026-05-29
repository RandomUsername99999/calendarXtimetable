<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite; // Fixed: Correct Facade Namespace
use Illuminate\Support\Str;

class ProviderCallbackController extends Controller
{
    public function __invoke(String $provider)
    {
        if (!in_array($provider, ['google', 'github'])){
            return redirect()->route('login')->withErrors(['provider' => 'Invalid Provider']);
        }

        $socialUser = Socialite::driver($provider)->user();

        // 1. Find if a user already exists with this provider ID OR this email address
        $user = User::where('provider_id', $socialUser->getId())
                    ->orWhere('email', $socialUser->getEmail())
                    ->first();

        if ($user) {
            // 2. Existing user: Link the accounts or refresh tokens
            $user->update([
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);
        } else {
            // 3. Brand new user: Generate username and create record
            $username = $this->generateUsername($socialUser);

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'username' => $username,
                'password' => Hash::make(Str::random(32)),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);
        }
 
        Auth::login($user);
        session(['auth.password_confirmed_at' => time()]);

        return redirect('/dashboard');
    }

    private function generateUsername($socialUser)
    {
        $username = $socialUser->getNickname() ?? null; 

        if (!$username) {  
            if (!empty($socialUser->getName())) { 
                $username = Str::lower(str_replace(' ', '', $socialUser->getName())) . '_' . rand(1000, 9999);
            } else { 
                $emailBeforeAt = Str::before($socialUser->getEmail(), '@');
                $username = Str::lower(str_replace(' ', '', $emailBeforeAt)) . '_' . rand(1000, 9999);
            }
        }

        $username = preg_replace('/[^A-Za-z0-9_]/', '', $username); 

        // Check if username exists (Fixed: Swapped to dynamic magic method to silence the IDE error)
        $baseUsername = $username;
        $count = 1;
        while (User::whereUsername($username)->exists()) {
            $username = $baseUsername . '_' . $count;
            $count++;
        }

        return $username;
    }
}