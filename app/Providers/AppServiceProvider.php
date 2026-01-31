<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        try {
            // Share categories globally for header menu
            // Using View::composer to avoid query on console commands if DB not ready, 
            // but for simplicity in this context View::share or composer with closure is fine.
            // Using composer is safer for performance if not all views need it, but header is on almost all.
            View::composer('*', function ($view) {
                // Check if categories is already set to avoid double query or overriding
                if (!isset($view->getData()['categories'])) {
                     $categories = Category::whereNull('parent_id')->take(6)->get();
                     $view->with('categories', $categories);
                }

                // Share chatbot status
                $chatbotEnabled = \Illuminate\Support\Facades\Cache::remember('chatbot_setting_chatbot_enabled', 3600, function () {
                    return \App\Models\ChatbotSetting::where('key', 'chatbot_enabled')->first()?->value ?? '0';
                });
                $view->with('chatbot_enabled', $chatbotEnabled == '1');
            });
        } catch (\Exception $e) {
            // Log or ignore if DB connection fails during boot (e.g. composer install)
        }
    }
}
