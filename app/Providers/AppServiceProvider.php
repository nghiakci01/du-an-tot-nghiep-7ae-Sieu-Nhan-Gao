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
                try {
                // Check if categories is already set to avoid double query or overriding
                if (! isset($view->getData()['categories'])) {
                    $categories = \Illuminate\Support\Facades\Cache::remember('global_navigation_categories', 3600, function () {
                        return Category::whereNull('parent_id')->with('children')->get();
                    });
                    $view->with('categories', $categories);
                }

                // Share chatbot settings
                if (\Illuminate\Support\Facades\Schema::hasTable('chatbot_settings')) {
                    $chatbotEnabled = \Illuminate\Support\Facades\Cache::remember('chatbot_setting_chatbot_enabled', 3600, function () {
                        return \Illuminate\Support\Facades\DB::table('chatbot_settings')->where('key', 'chatbot_enabled')->first()?->value ?? '0';
                    });
                    $chatbotMode = \Illuminate\Support\Facades\Cache::remember('chatbot_setting_chatbot_mode', 3600, function () {
                        return \Illuminate\Support\Facades\DB::table('chatbot_settings')->where('key', 'chatbot_mode')->first()?->value ?? 'rules';
                    });

                    $view->with('chatbot_enabled', $chatbotEnabled == '1');
                    $view->with('chatbot_mode', $chatbotMode);
                } else {
                    $view->with('chatbot_enabled', false);
                    $view->with('chatbot_mode', 'rules');
                }

                // Share chatbot suggested questions
                if (\Illuminate\Support\Facades\Schema::hasTable('chatbot_suggested_questions')) {
                    $suggestedQuestions = \Illuminate\Support\Facades\Cache::remember('chatbot_suggested_questions', 3600, function () {
                        return \Illuminate\Support\Facades\DB::table('chatbot_suggested_questions')->where('is_active', true)
                            ->orderBy('order')
                            ->pluck('question')
                            ->toArray();
                    });
                    $view->with('chatbot_suggested_questions', $suggestedQuestions);
                } else {
                    $view->with('chatbot_suggested_questions', []);
                }

                // Share Global Settings
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $settings = \Illuminate\Support\Facades\Cache::remember('global_settings', 3600, function () {
                        return \Illuminate\Support\Facades\DB::table('settings')->get()->pluck('value', 'key')->toArray();
                    });
                    $view->with('settings', $settings);
                } else {
                    $view->with('settings', []);
                }

                // Share notifications for Admin
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                if ($user && $user->role === \App\Models\User::ROLE_ADMIN) {
                    $notifications = $user->unreadNotifications()->latest()->limit(5)->get();
                    $unreadCount = $user->unreadNotifications()->count();
                    $view->with('admin_notifications', $notifications);
                    $view->with('admin_unread_count', $unreadCount);
                }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('AppServiceProvider View Composer Error: ' . $e->getMessage());
                }
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('AppServiceProvider Boot Error: ' . $e->getMessage());
        }
    }
}
