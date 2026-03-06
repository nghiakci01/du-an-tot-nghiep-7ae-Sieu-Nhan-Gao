<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('welcome');
Route::get('/shop', [App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('product.detail');
Route::post('/product/{id}/review', [App\Http\Controllers\Frontend\ReviewController::class, 'store'])->name('product.review.store');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'send'])->name('contact.send');
Route::get('/about', [App\Http\Controllers\Frontend\HomeController::class, 'about'])->name('about');
Route::get('/news', [App\Http\Controllers\Frontend\HomeController::class, 'news'])->name('news');
Route::get('/news/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'newsDetail'])->name('news.detail');

// Search Routes
Route::get('/search', [App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search.index');
Route::get('/search/suggestions', [App\Http\Controllers\Frontend\SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/cart', [App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\Frontend\CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update', [App\Http\Controllers\Frontend\CartController::class, 'updateCart'])->name('cart.update');
Route::match(['get', 'post', 'delete'], '/cart/remove', [App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\Frontend\CartController::class, 'clearCart'])->name('cart.clear');
Route::post('/cart/apply-coupon', [App\Http\Controllers\Frontend\CartController::class, 'applyCoupon'])->name('cart.apply_coupon');
Route::post('/cart/remove-coupon', [App\Http\Controllers\Frontend\CartController::class, 'removeCoupon'])->name('cart.remove_coupon');
Route::post('/cart/change-variant', [App\Http\Controllers\Frontend\CartController::class, 'changeVariant'])->name('cart.changeVariant');
Route::get('/cart/count', [App\Http\Controllers\Frontend\CartController::class, 'getCartCount'])->name('cart.count');

Route::get('/home', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/apply-coupon', [App\Http\Controllers\Frontend\CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');
Route::post('/checkout/remove-coupon', [App\Http\Controllers\Frontend\CheckoutController::class, 'removeCoupon'])->name('checkout.removeCoupon');
Route::get('/checkout/success/{id}', [App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/order/{id}/confirm-transfer', [App\Http\Controllers\Frontend\CheckoutController::class, 'confirmTransfer'])->name('checkout.confirm_transfer');
Route::post('/checkout/order/{id}/cancel', [App\Http\Controllers\Frontend\CheckoutController::class, 'cancelOrder'])->name('checkout.cancel_order');

// VNPAY Routes
Route::get('/vnpay/payment/{order_id}', [App\Http\Controllers\Frontend\PaymentController::class, 'createPayment'])->name('vnpay.payment');
Route::get('/vnpay/callback', [App\Http\Controllers\Frontend\PaymentController::class, 'vnpayReturn'])->name('vnpay.callback');
Route::get('/vnpay/return', [App\Http\Controllers\Frontend\PaymentController::class, 'vnpayReturn'])->name('vnpay.return');

// Guest Order Tracking Routes
Route::get('/order-tracking', [App\Http\Controllers\Frontend\OrderTrackingController::class, 'index'])->name('order-tracking.index');
Route::post('/order-tracking/search', [App\Http\Controllers\Frontend\OrderTrackingController::class, 'search'])->name('order-tracking.search');
Auth::routes();

// Social Login
Route::get('auth/{provider}', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleProviderCallback'])->name('social.callback');

Route::get('/my-account', [App\Http\Controllers\Frontend\AccountController::class, 'index'])->name('account.index');
Route::get('/view-order/{id}', [App\Http\Controllers\Frontend\GuestOrderController::class, 'show'])->name('guest.order.show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/my-account/orders', function() {
        return redirect()->route('account.index', ['#orders']);
    })->name('account.orders');
    Route::get('/my-account/orders/{id}', [App\Http\Controllers\Frontend\AccountController::class, 'showOrder'])->name('account.orders.show');
    Route::post('/my-account/update', [App\Http\Controllers\Frontend\AccountController::class, 'update'])->name('account.update');
    Route::post('/my-account/orders/{id}/cancel', [App\Http\Controllers\Frontend\AccountController::class, 'cancelOrder'])->name('account.orders.cancel');

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
        Route::get('/api/dashboard/revenue', [\App\Http\Controllers\Admin\DashboardController::class, 'revenueApi'])->name('api.dashboard.revenue');
        
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        // System Settings
        Route::get('/system-settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/system-settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Admin & Staff Routes
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        Route::get('orders/{order}/print', [App\Http\Controllers\Admin\OrderController::class, 'print'])->name('orders.print');
        Route::get('orders/customers/search', [App\Http\Controllers\Admin\OrderController::class, 'customersSearch'])->name('orders.customers.search');
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
        Route::post('orders/{order}/confirm-payment', [App\Http\Controllers\Admin\OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::delete('products/gallery/{image}', [App\Http\Controllers\Admin\ProductController::class, 'deleteGalleryImage'])->name('products.gallery.delete');

        // Product Attributes
        Route::resource('sizes', App\Http\Controllers\Admin\SizeController::class);
        Route::resource('colors', App\Http\Controllers\Admin\ColorController::class);
        
        // General APIs for Admin Panel
        Route::get('api/variants/search', [App\Http\Controllers\Admin\InventoryVoucherController::class, 'variantsSearch'])->name('api.variants.search');

        // Admin Only Routes
        Route::middleware(['admin.only'])->group(function () {
            Route::get('payment-history', [App\Http\Controllers\Admin\PaymentHistoryController::class, 'index'])->name('payment-history.index');
            Route::resource('users', App\Http\Controllers\Admin\UserController::class);
            Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);

            // Inventory Management
            Route::resource('suppliers', App\Http\Controllers\Admin\SupplierController::class);
            Route::resource('warehouses', App\Http\Controllers\Admin\WarehouseController::class);
            Route::resource('vouchers', App\Http\Controllers\Admin\InventoryVoucherController::class);
            Route::post('vouchers/{voucher}/complete', [App\Http\Controllers\Admin\InventoryVoucherController::class, 'complete'])->name('vouchers.complete');
            Route::get('stock', function () {
                return 'Stock Report Page (Coming Soon)';
            })->name('stock.index');

            Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

            // Loyalty Points
            Route::get('loyalty-points', [App\Http\Controllers\Admin\LoyaltyPointController::class, 'index'])->name('loyalty-points.index');
            
            // Cài đặt ngân hàng thanh toán (QR Bank Settings)
            Route::resource('bank-settings', App\Http\Controllers\Admin\BankSettingController::class);
        });

        // Blog Management (Admin & Staff)
        Route::resource('post-categories', App\Http\Controllers\Admin\PostCategoryController::class);
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class);


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

        Route::resource('contact-messages', App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::post('contact-messages/{id}/reply', [App\Http\Controllers\Admin\ContactMessageController::class, 'reply'])->name('contact-messages.reply');

        // Review Management (Admin & Staff)
        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'destroy']);

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
        // Reports & Statistics
        Route::get('/reports/orders/excel', [App\Http\Controllers\Admin\ReportController::class, 'exportOrdersExcel'])->name('reports.orders.excel');
        Route::get('/reports/revenue/pdf', [App\Http\Controllers\Admin\ReportController::class, 'exportRevenuePDF'])->name('reports.revenue.pdf');

        // Audit Logs (Admin only)
        Route::middleware(['admin.only'])->group(function () {
            Route::resource('audit-logs', App\Http\Controllers\Admin\AuditLogController::class)->only(['index', 'show']);
            
            // Notifications
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
            Route::post('notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
            Route::get('notifications/{id}/mark-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
            Route::get('notifications/unread-count', [App\Http\Controllers\Admin\NotificationController::class, 'unreadCount'])->name('notifications.unread_count');
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
            'response' => $response,
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
