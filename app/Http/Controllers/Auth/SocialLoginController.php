<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login failed: ' . $e->getMessage());
        }

        // Check if social account exists
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            // Login existing user
            Auth::login($socialAccount->user);
            return redirect()->intended('/');
        }

        // Check if user with email exists
        $email = $socialUser->getEmail();
        $user = User::where('email', $email)->first();

        DB::beginTransaction();
        try {
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)), // Random password
                    'role' => User::ROLE_USER,
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }

            // Create social account
            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_user_id' => $socialUser->getId(),
            ]);

            // Update avatar if missing or changed
            if (!$user->avatar) {
                 $user->update(['avatar' => $socialUser->getAvatar()]);
            }

            DB::commit();
            Auth::login($user);
            return redirect()->intended('/');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('login')->with('error', 'Login failed: ' . $e->getMessage());
        }
    }
}
