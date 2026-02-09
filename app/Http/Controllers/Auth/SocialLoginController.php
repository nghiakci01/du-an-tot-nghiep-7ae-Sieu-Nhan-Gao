<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        // Facebook in development mode may not have email permission approved
        // Use setScopes to completely override default scopes (scopes() only adds to defaults)
        if ($provider === 'facebook') {
            return Socialite::driver($provider)
                ->setScopes(['public_profile']) // Only request public_profile, no email
                ->redirect();
        }
        
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            Log::info("Social Login Attempt", ['provider' => $provider, 'social_id' => $socialUser->getId(), 'email' => $socialUser->getEmail()]);
        } catch (\Exception $e) {
            Log::error("Social Login Redirect Error: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('login')->with('error', 'Login failed: ' . $e->getMessage());
        }
        // Check if social account exists
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            Log::info("Existing Social Account Found", ['user_id' => $socialAccount->user_id]);
            // Login existing user
            Auth::login($socialAccount->user, true); // Remember user
            
            // Verify login immediately
            if (Auth::check()) {
                Log::info("Login Verified for Existing User", ['user_id' => Auth::id()]);
                session()->regenerate();
                return redirect()->intended('/');
            }
            Log::error("Auth Check Failed After Login for Existing User");
            return redirect()->route('login')->with('error', 'Authentication failed after login.');
        }

        // Check if user with email exists (or by social ID)
        $email = $socialUser->getEmail();
        
        // If no email (Facebook without email permission), create a placeholder
        if (!$email && $provider === 'facebook') {
            $email = 'fb_' . $socialUser->getId() . '@facebook.placeholder';
        }
        
        $user = $email ? User::where('email', $email)->first() : null;

        DB::beginTransaction();
        try {
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?: ($provider === 'facebook' ? 'Facebook User' : 'Google User'),
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)), // Random password
                    'role' => User::ROLE_USER,
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }

            // Check if social account already exists for this user/provider
            $existingAccount = SocialAccount::where('user_id', $user->id)
                ->where('provider', $provider)
                ->first();

            if (!$existingAccount) {
                // Create social account
                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_user_id' => $socialUser->getId(),
                ]);
            }

            // Update avatar if missing or changed
            if (!$user->avatar) {
                 $user->update(['avatar' => $socialUser->getAvatar()]);
            }

            DB::commit();
            
            Auth::login($user, true); // Remember user
            
            if (Auth::check()) {
                session()->regenerate();
                return redirect()->intended('/');
            }
            return redirect()->route('login')->with('error', 'Authentication failed after registration.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('login')->with('error', 'Login failed: ' . $e->getMessage());
        }
    }
}
