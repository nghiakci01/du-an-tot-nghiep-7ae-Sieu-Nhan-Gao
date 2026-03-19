<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Events\CartUpdatedEvent;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $total = 0;

        // Enrich cart data with available variants for selection in UI
        foreach ($cart as $id => &$details) {
            $total += $details['price'] * $details['quantity'];

            $product = Product::with(['category', 'variants.sizeRelationship', 'variants.colorRelationship'])->find($details['product_id']);
            
            // Set default flags
            $details['is_deleted'] = false;
            $details['is_inactive'] = false;
            $details['variant_exists'] = true;
            $details['is_out_of_stock'] = false;

            if ($product) {
                // Check if product is active
                if (!$product->is_active) {
                    $details['is_inactive'] = true;
                }

                // Determine stock
                $currentVariant = $product->variants->find($id);
                if (!$currentVariant) {
                    $details['variant_exists'] = false;
                } else {
                    $details['stock_quantity'] = $currentVariant->stock_quantity;
                    $details['is_out_of_stock'] = $details['stock_quantity'] <= 0;
                }

                $details['category_slug'] = $product->category ? $product->category->slug : null;


                // Get unique sizes and colors available for this product (supporting both legacy strings and IDs)
                $details['available_sizes_array'] = [];
                $details['available_colors_array'] = [];
                foreach ($product->variants as $variant) {
                    if ($variant->size_id && $variant->sizeRelationship) {
                        $details['available_sizes_array'][$variant->size_id] = $variant->sizeRelationship->name;
                    } elseif ($variant->size) {
                        $details['available_sizes_array'][$variant->size] = $variant->size;
                    }
                    
                    if ($variant->color_id && $variant->colorRelationship) {
                        $details['available_colors_array'][$variant->color_id] = $variant->colorRelationship->name;
                    } elseif ($variant->color) {
                        $details['available_colors_array'][$variant->color] = $variant->color;
                    }
                }

                // Also get all valid variant combinations for this product to help client-side selection
                $details['product_variants'] = $product->variants;

                // Set current IDs if not present (for migration of existing carts)
                if (! isset($details['size_id']) || ! isset($details['color_id'])) {
                    if ($currentVariant) {
                        $details['size_id'] = $currentVariant->size_id;
                        $details['color_id'] = $currentVariant->color_id;
                    }
                }

                // Get other products in the same category
                if ($product->category_id) {
                    $details['category_products'] = Product::where('category_id', $product->category_id)
                        ->where('is_active', true)
                        ->get(['id', 'name']);
                } else {
                    $details['category_products'] = collect([]);
                }
            } else {
                $details['is_deleted'] = true;
                $details['variant_exists'] = false;
                $details['is_out_of_stock'] = true;
            }
        }

        // Sort cart to put unavailable/out of stock items at the bottom
        uasort($cart, function ($a, $b) {
            $aUnavailable = (isset($a['is_deleted']) && $a['is_deleted']) || (isset($a['is_inactive']) && $a['is_inactive']) || (isset($a['is_out_of_stock']) && $a['is_out_of_stock']) ? 1 : 0;
            $bUnavailable = (isset($b['is_deleted']) && $b['is_deleted']) || (isset($b['is_inactive']) && $b['is_inactive']) || (isset($b['is_out_of_stock']) && $b['is_out_of_stock']) ? 1 : 0;
            return $aUnavailable <=> $bUnavailable;
        });

        // Get applied coupon from session
        $couponCode = session()->get('coupon_code');
        $discount = session()->get('discount_amount', 0);
        $coupon = null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            // Re-validate coupon if total changed
            if ($coupon) {
                if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
                    // Remove coupon if min order not met
                    session()->forget(['coupon_code', 'discount_amount']);
                    $discount = 0;
                    $coupon = null;
                } else {
                    // Recalculate discount
                    $discount = $coupon->calculateDiscount($total);
                    session()->put('discount_amount', $discount);
                }
            }
        }

        // Cross-sell: products from same categories, excluding items already in cart
    $cartProductIds = collect($cart)->pluck('product_id')->unique()->toArray();
    $cartCategoryIds = Product::whereIn('id', $cartProductIds)->pluck('category_id')->unique()->toArray();
    $crossSellProducts = Product::where('is_active', true)
        ->whereIn('category_id', $cartCategoryIds)
        ->whereNotIn('id', $cartProductIds)
        ->with(['variants', 'reviews', 'images'])
        ->inRandomOrder()
        ->take(8)
        ->get();

        return view('frontend.cart.index', compact('cart', 'total', 'coupon', 'discount', 'crossSellProducts'));
    }

    public function changeVariant(Request $request)
    {
        $request->validate([
            'old_variant_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'new_product_id' => 'nullable|exists:products,id',
            'size_id' => 'nullable',
            'color_id' => 'nullable',
            'changed_type' => 'nullable|string', // 'size', 'color', or 'product'
        ]);

        $oldVariantId = $request->old_variant_id;
        $currentProductId = $request->product_id;
        $newProductId = $request->new_product_id ?? $currentProductId;
        $sizeId = $request->size_id;
        $colorId = $request->color_id;
        $changedType = $request->changed_type;
        $productId = $newProductId;

        $cart = $this->cartService->getCart();

        if (! isset($cart[$oldVariantId])) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        // Try to find the exact combination first
        $query = ProductVariant::where('product_id', $productId);
        if ($sizeId) {
            $query->where(function ($q) use ($sizeId) {
                $q->where('size_id', $sizeId)->orWhere('size', $sizeId);
            });
        }
        if ($colorId) {
            $query->where(function ($q) use ($colorId) {
                $q->where('color_id', $colorId)->orWhere('color', $colorId);
            });
        }
        $newVariant = $query->first();

        // If exact combination doesn't exist, try to find a variant matching the CHANGED attribute
        if (! $newVariant && $changedType) {
            $query = ProductVariant::where('product_id', $productId);
            if ($changedType === 'size' && $sizeId) {
                $query->where(function ($q) use ($sizeId) {
                    $q->where('size_id', $sizeId)->orWhere('size', $sizeId);
                });
            } elseif ($changedType === 'color' && $colorId) {
                $query->where(function ($q) use ($colorId) {
                    $q->where('color_id', $colorId)->orWhere('color', $colorId);
                });
            } elseif ($changedType === 'product') {
                // If product changed, just pick the first available variant
            }
            $newVariant = $query->first(); // Get first available alternative
        }

        if (! $newVariant) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy phiên bản phù hợp cho sản phẩm này'], 404);
        }

        $product = Product::find($productId);
        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không hợp lệ'], 404);
        }

        // Check stock for the new variant
        if ($newVariant->stock_quantity < $cart[$oldVariantId]['quantity']) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không đủ hàng cho phiên bản này'], 400);
        }

        $oldQuantity = $cart[$oldVariantId]['quantity'];

        // Determine price
        $itemPrice = $newVariant->price ?? $product->price;
        if ($newVariant->sale_price && $newVariant->sale_price < ($newVariant->price ?? PHP_INT_MAX)) {
            $itemPrice = $newVariant->sale_price;
        }

        $this->cartService->updateCart(function(&$cart) use ($oldVariantId, $newVariant, $productId, $product, $oldQuantity, $itemPrice) {
            unset($cart[$oldVariantId]); // Remove old variant

            if (isset($cart[$newVariant->id])) {
                $cart[$newVariant->id]['quantity'] += $oldQuantity; // Merge
            } else {
                // Add new variant
                $cart[$newVariant->id] = [
                    'product_id' => $productId,
                    'variant_id' => $newVariant->id,
                    'name' => $product->name,
                    'quantity' => $oldQuantity,
                    'price' => $itemPrice,
                    'image' => $product->image,
                    'size' => $newVariant->sizeRelationship ? $newVariant->sizeRelationship->name : $newVariant->size,
                    'color' => $newVariant->colorRelationship ? $newVariant->colorRelationship->name : $newVariant->color,
                    'size_id' => $newVariant->size_id,
                    'color_id' => $newVariant->color_id,
                    'slug' => $product->slug,
                ];
            }
        });

        // Fire event
        $cartCount = array_sum(array_column($this->cartService->getCart(), 'quantity'));
        CartUpdatedEvent::dispatch($cartCount, session()->getId(), auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng',
            'redirect' => route('cart.index', ['editing' => $newVariant->id]),
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variantId = $request->variant_id;

        // Validate: Nếu sản phẩm có variants thì bắt buộc phải chọn
        if (! $variantId) {
            $variants = $product->variants;
            if ($variants->count() === 1) {
                // Tự động chọn nếu chỉ có 1 variant
                $variantId = $variants->first()->id;
            } elseif ($variants->count() > 1) {
                // Có nhiều variant → bắt buộc phải chọn
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.',
                    ], 422);
                }

                return redirect()->route('product.detail', $product->slug)
                    ->with('error', 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');
            } else {
                return redirect()->back()->with('error', 'Sản phẩm này hiện không có biến thể nào.');
            }
        }

        $variant = ProductVariant::findOrFail($variantId);
        $cart = $this->cartService->getCart();

        // Check tồn kho — tính cả số lượng đã có trong giỏ
        $existingQty = isset($cart[$variant->id]) ? $cart[$variant->id]['quantity'] : 0;
        $requestedQty = $request->quantity ?? 1;
        $totalQty = $existingQty + $requestedQty;

        if ($variant->stock_quantity <= 0) {
            $msg = 'Sản phẩm này đã hết hàng.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        if ($totalQty > $variant->stock_quantity) {
            $available = $variant->stock_quantity - $existingQty;
            if ($available <= 0) {
                $msg = "Bạn đã có {$existingQty} sản phẩm này trong giỏ hàng. Không thể thêm, đã đạt giới hạn tồn kho ({$variant->stock_quantity}).";
            } else {
                $msg = "Chỉ còn {$variant->stock_quantity} sản phẩm trong kho. Bạn đã có {$existingQty} trong giỏ, chỉ có thể thêm tối đa {$available} sản phẩm.";
            }
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->back()->with('error', $msg);
        }

        $this->cartService->updateCart(function(&$cart) use ($variant, $request, $product) {
            if (isset($cart[$variant->id])) {
                $cart[$variant->id]['quantity'] += $request->quantity;
            } else {
                // Determine price: Use variant's sale_price if it exists and is less than price, else use variant price
                // Fallback to product price if variant price is null
                $itemPrice = $variant->price ?? $product->price;
                if ($variant->sale_price && $variant->sale_price < ($variant->price ?? PHP_INT_MAX)) {
                    $itemPrice = $variant->sale_price;
                }

                $cart[$variant->id] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant->id,
                    'name' => $product->name,
                    'quantity' => $request->quantity,
                    'price' => $itemPrice,
                    'image' => $product->image,
                    'size' => $variant->sizeRelationship ? $variant->sizeRelationship->name : $variant->size,
                    'color' => $variant->colorRelationship ? $variant->colorRelationship->name : $variant->color,
                    'size_id' => $variant->size_id,
                    'color_id' => $variant->color_id,
                    'slug' => $product->slug,
                ];
            }
        });

        // Fire Event
        $cartAfterEdit = $this->cartService->getCart();
        $cartCount = array_sum(array_column($cartAfterEdit, 'quantity'));
        CartUpdatedEvent::dispatch($cartCount, session()->getId(), auth()->id());

        if ($request->input('action') === 'buy_now') {
            // Set session for checkout
            session(['selected_checkout_ids' => [(string)$variant->id]]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.index'),
                ]);
            }

            return redirect()->route('checkout.index');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                'count' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = $this->cartService->getCart();
            $variant = ProductVariant::find($request->id);

            if ($variant && $variant->stock_quantity >= $request->quantity) {
                $this->cartService->updateCart(function(&$cart) use ($request) {
                    $cart[$request->id]['quantity'] = $request->quantity;
                });
                
                $cart = $this->cartService->getCart();

                // Calculate new totals
                $itemTotal = $cart[$request->id]['price'] * $request->quantity;
                $subtotal = 0;
                $cartCount = 0;
                foreach ($cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                    $cartCount += $item['quantity'];
                }

                $shippingFee = \App\Models\Setting::getShippingFee($subtotal);

                // Recalculate discount if coupon applied
                $discount = 0;
                $couponCode = session()->get('coupon_code');
                if ($couponCode) {
                    $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                    if ($coupon) {
                        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                            session()->forget(['coupon_code', 'discount_amount']);
                        } else {
                            $discount = $coupon->calculateDiscount($subtotal);
                            session()->put('discount_amount', $discount);
                        }
                    }
                }

                $grandTotal = $subtotal - $discount + $shippingFee;

                CartUpdatedEvent::dispatch($cartCount, session()->getId(), auth()->id());

                return response()->json([
                    'success' => true,
                    'message' => 'Giỏ hàng đã được cập nhật',
                    'item_total' => number_format($itemTotal).' đ',
                    'cart_total' => number_format($subtotal).' đ',
                    'discount' => number_format($discount).' đ',
                    'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee).' đ') : 'Miễn phí',
                    'grand_total' => number_format($grandTotal).' đ',
                    'cart_count' => $cartCount,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng không hợp lệ hoặc vượt quá tồn kho.',
                ], 400);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
    }

    public function remove(Request $request)
    {
        $id = $request->id ?: $request->get('id');
        $cart = $this->cartService->getCart();

        \Log::info('Cart Remove Request', [
            'method' => $request->method(),
            'id' => $id,
            'cart_keys' => array_keys($cart),
        ]);

        if ($id !== null) {
            // Find the key in the cart - sometimes keys might be strings even if numeric
            $foundKey = null;
            if (isset($cart[$id])) {
                $foundKey = $id;
            } else {
                foreach (array_keys($cart) as $key) {
                    if ((string) $key === (string) $id) {
                        $foundKey = $key;
                        break;
                    }
                }
            }

            if ($foundKey !== null) {
                $this->cartService->updateCart(function(&$cart) use ($foundKey) {
                    unset($cart[$foundKey]);
                });
                
                $cart = $this->cartService->getCart();

                // Calculate new totals
                $subtotal = 0;
                $cartCount = 0;
                foreach ($cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                    $cartCount += $item['quantity'];
                }

                $shippingFee = \App\Models\Setting::getShippingFee($subtotal);

                // Recalculate discount if coupon applied
                $discount = 0;
                $couponCode = session()->get('coupon_code');
                if ($couponCode) {
                    $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
                    if ($coupon) {
                        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                            session()->forget(['coupon_code', 'discount_amount']);
                        } else {
                            $discount = $coupon->calculateDiscount($subtotal);
                            session()->put('discount_amount', $discount);
                        }
                    }
                }

                $grandTotal = $subtotal - $discount + $shippingFee;

                CartUpdatedEvent::dispatch($cartCount, session()->getId(), auth()->id());

                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
                    'cart_total' => number_format($subtotal).' đ',
                    'discount' => number_format($discount).' đ',
                    'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee).' đ') : 'Miễn phí',
                    'grand_total' => number_format($grandTotal).' đ',
                    'cart_count' => $cartCount,
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    public function clearCart(Request $request)
    {
        $this->cartService->clearCart();
        session()->forget(['coupon_code', 'discount_amount']); // Also clear coupon info

        // Recalculate totals for an empty cart
        $subtotal = 0;
        $shippingFee = \App\Models\Setting::getShippingFee($subtotal);
        $grandTotal = $subtotal + $shippingFee;

        CartUpdatedEvent::dispatch(0, session()->getId(), auth()->id());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Giỏ hàng đã được xóa!',
                'cart_total' => number_format($subtotal).' đ',
                'discount' => number_format(0).' đ',
                'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee).' đ') : 'Miễn phí',
                'grand_total' => number_format($grandTotal).' đ',
                'cart_count' => 0,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart has been cleared');
    }

    public function getCartCount()
    {
        $cart = $this->cartService->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return response()->json(['count' => $count]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $cart = $this->cartService->getCart();
        if (count($cart) == 0) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng của bạn đang trống.'], 400);
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $couponCode = strtoupper(trim($request->coupon_code));
        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();

        if (! $coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.'], 404);
        }

        if (! $coupon->is_active || $coupon->isExpired() || $coupon->isNotYetStarted() || $coupon->hasReachedUsageLimit()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'], 400);
        }

        if ($coupon->user_id && $coupon->user_id != \Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá này không dành cho bạn.'], 400);
        }

        if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu '.number_format($coupon->min_order_amount).' đ để sử dụng mã này.',
            ], 400);
        }

        $discount = $coupon->calculateDiscount($total);
        session()->put('coupon_code', $coupon->code);
        session()->put('discount_amount', $discount);

        $shippingFee = \App\Models\Setting::getShippingFee($total - $discount);
        $grandTotal = $total - $discount + $shippingFee;

        return response()->json([
            'success' => true,
            'message' => 'Đã áp dụng mã giảm giá thành công!',
            'data' => [
                'coupon_code' => $coupon->code,
                'discount' => number_format($discount).' đ',
                'subtotal' => number_format($total).' đ',
                'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee).' đ') : 'Miễn phí',
                'grand_total' => number_format($grandTotal).' đ',
            ],
        ]);
    }

    public function removeCoupon()
    {
        session()->forget(['coupon_code', 'discount_amount']);

        $cart = $this->cartService->getCart();
        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $shippingFee = \App\Models\Setting::getShippingFee($total);
        $grandTotal = $total + $shippingFee;

        return response()->json([
            'success' => true,
            'message' => 'Đã gỡ bỏ mã giảm giá.',
            'data' => [
                'subtotal' => number_format($total).' đ',
                'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee).' đ') : 'Miễn phí',
                'grand_total' => number_format($grandTotal).' đ',
            ],
        ]);
    }
}
