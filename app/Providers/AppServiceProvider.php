<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        try {
            // Share categories globally for header menu
            // Register view composer even in console so tests and queues can render views
        View::composer('*', function ($view) {
            static $sharedData = null;

            if ($sharedData === null) {
                $sharedData = [];
                try {
                    // 1. Share categories
                    $sharedData['categories'] = \Illuminate\Support\Facades\Cache::remember('global_navigation_categories', 3600, function () {
                        return Category::whereNull('parent_id')->with('children')->get();
                    });

                    // 2. Chatbot Settings (Gộp kiểm tra Schema)
                    $hasChatbotTable = \Illuminate\Support\Facades\Schema::hasTable('chatbot_settings');
                    if ($hasChatbotTable) {
                        $sharedData['chatbot_enabled'] = \Illuminate\Support\Facades\Cache::remember('chatbot_setting_chatbot_enabled', 3600, function () {
                            return \Illuminate\Support\Facades\DB::table('chatbot_settings')->where('key', 'chatbot_enabled')->first()?->value ?? '0';
                        }) == '1';
                        $sharedData['chatbot_mode'] = \Illuminate\Support\Facades\Cache::remember('chatbot_setting_chatbot_mode', 3600, function () {
                            return \Illuminate\Support\Facades\DB::table('chatbot_settings')->where('key', 'chatbot_mode')->first()?->value ?? 'rules';
                        });
                    } else {
                        $sharedData['chatbot_enabled'] = false;
                        $sharedData['chatbot_mode'] = 'rules';
                    }

                    // 3. Suggested Questions
                    if (\Illuminate\Support\Facades\Schema::hasTable('chatbot_suggested_questions')) {
                        $sharedData['chatbot_suggested_questions'] = \Illuminate\Support\Facades\Cache::remember('chatbot_suggested_questions', 3600, function () {
                            return \Illuminate\Support\Facades\DB::table('chatbot_suggested_questions')->where('is_active', true)
                                ->orderBy('order')
                                ->pluck('question')
                                ->toArray();
                        });
                    } else {
                        $sharedData['chatbot_suggested_questions'] = [];
                    }

                    // 4. Global Settings
                    if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                        $sharedData['settings'] = \Illuminate\Support\Facades\Cache::remember('global_settings', 3600, function () {
                            return \Illuminate\Support\Facades\DB::table('settings')->get()->pluck('value', 'key')->toArray();
                        });
                    } else {
                        $sharedData['settings'] = [];
                    }

                    // 5. Admin Notifications
                    $user = Auth::user();
                    if ($user && $user->role === \App\Models\User::ROLE_ADMIN) {
                        $sharedData['admin_notifications'] = $user->unreadNotifications()->latest()->limit(5)->get();
                        $sharedData['admin_unread_count'] = $user->unreadNotifications()->count();
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('AppServiceProvider Data Prep Error: ' . $e->getMessage());
                }
            }

            // Bind all shared data to the view
            foreach ($sharedData as $key => $value) {
                if (!isset($view->getData()[$key])) {
                    $view->with($key, $value);
                }
            }
        });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AppServiceProvider Boot Error: ' . $e->getMessage());
        }
    }
}
