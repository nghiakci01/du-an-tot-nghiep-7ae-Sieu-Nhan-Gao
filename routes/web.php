<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('welcome');
Route::get('/shop', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('product.detail');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'send'])->name('contact.send');
Route::get('/about', [App\Http\Controllers\Frontend\HomeController::class, 'about'])->name('about');
Route::get('/news', [App\Http\Controllers\Frontend\HomeController::class, 'news'])->name('news');

// Search Routes
Route::get('/search', [App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggestions', [App\Http\Controllers\Frontend\SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\Frontend\CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update', [App\Http\Controllers\Frontend\CartController::class, 'updateCart'])->name('cart.update');
Route::match(['get', 'post', 'delete'], '/cart/remove', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\Frontend\CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [App\Http\Controllers\Frontend\CartController::class, 'getCartCount'])->name('cart.count');

Route::get('/home', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{id}', [App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('checkout.success');
Auth::routes();

// Social Login
Route::get('auth/{provider}', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleProviderCallback'])->name('social.callback');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/my-account', [App\Http\Controllers\Frontend\AccountController::class, 'index'])->name('account.index');
    Route::get('/my-account/orders/{id}', [App\Http\Controllers\Frontend\AccountController::class, 'showOrder'])->name('account.orders.show');

    // Wishlist Routes
    Route::get('/wishlist', [App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('wishlist.index');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\Frontend\WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

// Wishlist Add (Handled with manual auth check for AJX)
Route::post('/wishlist/add', [App\Http\Controllers\Frontend\WishlistController::class, 'store'])->name('wishlist.add');

// Public Chatbot Route (Moved from API to Web to access Session)
Route::post('/api/chat/send', [App\Http\Controllers\Api\ChatController::class, 'sendMessage'])->name('api.chat.send');
Route::get('/api/chat/messages', [App\Http\Controllers\Api\ChatController::class, 'getMessages'])->name('api.chat.messages');

// Admin & Staff Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Lock Screen Routes (No admin.lock)
    Route::get('/lock', [\App\Http\Controllers\Admin\LockScreenController::class, 'lock'])->name('lock');
    Route::get('/unlock', function () {
        return view('admin.auth.lock-screen');
    })->name('unlock');
    Route::post('/unlock', [\App\Http\Controllers\Admin\LockScreenController::class, 'unlock'])->name('unlock.submit');

    // Protected Routes
    Route::middleware(['admin.lock'])->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        // System Settings
        Route::get('/system-settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/system-settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Admin Only Routes
        Route::middleware(['admin.only'])->group(function () {
            Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
            Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
            Route::resource('users', App\Http\Controllers\Admin\UserController::class);
            Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
            Route::resource('contact-messages', App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
            Route::delete('products/gallery/{image}', [App\Http\Controllers\Admin\ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');

            // Product Attributes
            Route::resource('sizes', App\Http\Controllers\Admin\SizeController::class);
            Route::resource('colors', App\Http\Controllers\Admin\ColorController::class);
            Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
        });

        // Admin & Staff Routes (Stock only)
        Route::get('stock', [App\Http\Controllers\Admin\StockController::class, 'index'])->name('stock.index');
        Route::post('stock/update', [App\Http\Controllers\Admin\StockController::class, 'update'])->name('stock.update');

        // Chatbot Management (Admin & Staff)
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ChatManagementController::class, 'index'])->name('index');
            Route::get('/trash', [\App\Http\Controllers\Admin\ChatManagementController::class, 'trash'])->name('trash');
            Route::get('/{sessionId}', [\App\Http\Controllers\Admin\ChatManagementController::class, 'show'])->name('show');
            Route::post('/{sessionId}/reply', [\App\Http\Controllers\Admin\ChatManagementController::class, 'reply'])->name('reply');
            Route::delete('/{sessionId}', [\App\Http\Controllers\Admin\ChatManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{sessionId}/restore', [\App\Http\Controllers\Admin\ChatManagementController::class, 'restore'])->name('restore');
            Route::delete('/{sessionId}/permanent', [\App\Http\Controllers\Admin\ChatManagementController::class, 'permanentDelete'])->name('permanent');
            Route::post('/{sessionId}/toggle-bot', [\App\Http\Controllers\Admin\ChatManagementController::class, 'toggleBot'])->name('toggle_bot');
            Route::delete('/message/{id}', [\App\Http\Controllers\Admin\ChatManagementController::class, 'destroyMessage'])->name('destroy_message');
        });

        // Chatbot Questions (Admin only)
        Route::middleware(['admin.only'])->group(function () {
            Route::prefix('chatbot')->name('chatbot.')->group(function () {
                Route::resource('questions', \App\Http\Controllers\Admin\ChatbotSuggestedQuestionController::class);
            });
        });

        // Chatbot Settings (Admin only)
        Route::middleware(['admin.only'])->group(function () {
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/chatbot', [\App\Http\Controllers\Admin\ChatbotSettingController::class, 'index'])->name('chatbot');
                Route::post('/chatbot', [\App\Http\Controllers\Admin\ChatbotSettingController::class, 'update'])->name('chatbot.update');
                Route::post('/chatbot/test', [\App\Http\Controllers\Admin\ChatbotSettingController::class, 'testConnection'])->name('chatbot.test');
            });
        });
    });
});

// Test Route for Gemini Chatbot
Route::get('/test-gemini', function () {
    $chatService = app(\App\Services\ChatService::class);

    $tests = [
        'Xin chào, bạn có thể giúp gì cho tôi?' => 'Simple Greeting',
        'Có sản phẩm laptop không?' => 'Product Query (RAG)',
        'Cho tôi xem sản phẩm iPhone' => 'Specific Product Search',
    ];

    $results = [];
    foreach ($tests as $question => $testName) {
        $response = $chatService->generateResponse($question);
        $results[] = [
            'test' => $testName,
            'question' => $question,
            'response' => $response
        ];
    }

    return view('test-gemini', compact('results'));
});

// Quick Test Route
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'vi'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
