<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }
        return view('frontend.cart.index', compact('cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variantId = $request->variant_id;

        // Validate: Nếu sản phẩm có variants thì bắt buộc phải chọn
        if (!$variantId) {
            $variants = $product->variants;
            if ($variants->count() === 1) {
                // Tự động chọn nếu chỉ có 1 variant
                $variantId = $variants->first()->id;
            } elseif ($variants->count() > 1) {
                // Có nhiều variant → bắt buộc phải chọn
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.'
                    ], 422);
                }
                return redirect()->route('product.detail', $product->slug)
                    ->with('error', 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');
            } else {
                return redirect()->back()->with('error', 'Sản phẩm này hiện không có biến thể nào.');
            }
        }

        $variant = ProductVariant::findOrFail($variantId);
        $cart = session()->get('cart', []);

        // Check tồn kho — tính cả số lượng đã có trong giỏ
        $existingQty = isset($cart[$variant->id]) ? $cart[$variant->id]['quantity'] : 0;
        $requestedQty = $request->quantity ?? 1;
        $totalQty = $existingQty + $requestedQty;

        if ($variant->stock_quantity <= 0) {
            $msg = 'Sản phẩm này đã hết hàng.';
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
            return redirect()->back()->with('error', $msg);
        }

        if ($totalQty > $variant->stock_quantity) {
            $available = $variant->stock_quantity - $existingQty;
            if ($available <= 0) {
                $msg = "Bạn đã có {$existingQty} sản phẩm này trong giỏ hàng. Không thể thêm, đã đạt giới hạn tồn kho ({$variant->stock_quantity}).";
            } else {
                $msg = "Chỉ còn {$variant->stock_quantity} sản phẩm trong kho. Bạn đã có {$existingQty} trong giỏ, chỉ có thể thêm tối đa {$available} sản phẩm.";
            }
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 422);
            return redirect()->back()->with('error', $msg);
        }

        if(isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += $request->quantity;
        } else {
            // Determine price: Use variant's sale_price if it exists and is less than price, else use variant price
            // Fallback to product price if variant price is null
            $itemPrice = $variant->price ?? $product->price;
            if ($variant->sale_price && $variant->sale_price < ($variant->price ?? PHP_INT_MAX)) {
                $itemPrice = $variant->sale_price;
            }

            $cart[$variant->id] = [
                "product_id" => $product->id,
                "variant_id" => $variant->id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $itemPrice,
                "image" => $product->image,
                "size" => $variant->size,
                "color" => $variant->color,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);
        
        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart', []);
            $variant = ProductVariant::find($request->id);

            if ($variant && $variant->stock_quantity >= $request->quantity) {
                 $cart[$request->id]["quantity"] = $request->quantity;
                 session()->put('cart', $cart);
                 
                  // Calculate new totals
                  $itemTotal = $cart[$request->id]['price'] * $request->quantity;
                  $subtotal = 0;
                  $cartCount = 0;
                  foreach($cart as $item) {
                     $subtotal += $item['price'] * $item['quantity'];
                     $cartCount += $item['quantity'];
                  }

                  $shippingFee = \App\Models\Setting::getShippingFee($subtotal);
                  $grandTotal = $subtotal + $shippingFee;

                  return response()->json([
                      'success' => true,
                      'message' => 'Giỏ hàng đã được cập nhật',
                      'item_total' => number_format($itemTotal) . ' đ',
                      'cart_total' => number_format($subtotal) . ' đ',
                      'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee) . ' đ') : 'Miễn phí',
                      'grand_total' => number_format($grandTotal) . ' đ',
                      'cart_count' => $cartCount
                  ]);
            } else {
                 return response()->json([
                     'success' => false,
                     'message' => 'Invalid quantity or exceeds stock'
                 ], 400);
            }
            
            session()->flash('error', 'Invalid quantity or exceeds stock');
            return response()->json(['success' => false], 400);
        }
        
        return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
    }

    public function remove(Request $request)
    {
        $id = $request->id ?: $request->get('id');
        $cart = session()->get('cart', []);
        
        \Log::info('Cart Remove Request', [
            'method' => $request->method(),
            'id' => $id,
            'cart_keys' => array_keys($cart)
        ]);

        if ($id !== null) {
            // Find the key in the cart - sometimes keys might be strings even if numeric
            $foundKey = null;
            if (isset($cart[$id])) {
                $foundKey = $id;
            } else {
                foreach (array_keys($cart) as $key) {
                    if ((string)$key === (string)$id) {
                        $foundKey = $key;
                        break;
                    }
                }
            }

            if ($foundKey !== null) {
                unset($cart[$foundKey]);
                session()->put('cart', $cart);
                
                // Calculate new totals
                 $subtotal = 0;
                 $cartCount = 0;
                 foreach($cart as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                    $cartCount += $item['quantity'];
                 }
                
                $shippingFee = \App\Models\Setting::getShippingFee($subtotal);
                $grandTotal = $subtotal + $shippingFee;

                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
                    'cart_total' => number_format($subtotal) . ' đ',
                    'shipping_fee' => $shippingFee > 0 ? (number_format($shippingFee) . ' đ') : 'Miễn phí',
                    'grand_total' => number_format($grandTotal) . ' đ',
                    'cart_count' => $cartCount
                ]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Product not found in cart'], 404);
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart has been cleared'
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart has been cleared');
    }

    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach($cart as $item) {
            $count += $item['quantity'];
        }
        return response()->json(['count' => $count]);
    }
}
