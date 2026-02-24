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

        // Auto-select variant if missing
        if (!$variantId) {
            $variants = $product->variants;
            if ($variants->count() === 1) {
                $variantId = $variants->first()->id;
            } elseif ($variants->count() > 1) {
                return redirect()->route('product.detail', $product->slug)
                    ->with('info', 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');
            } else {
                return redirect()->back()->with('error', 'Sản phẩm này hiện chưa có biến thể sẵn sàng.');
            }
        }

        $variant = ProductVariant::findOrFail($variantId);
        $cart = session()->get('cart', []);

        // Check availability
        if ($variant->stock_quantity < $request->quantity) {
             return redirect()->back()->with('error', 'Sản phẩm không đủ số lượng tồn kho.');
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

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
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
                     'message' => 'Số lượng không hợp lệ hoặc vượt quá tồn kho'
                 ], 400);
            }
            
            session()->flash('error', 'Số lượng không hợp lệ hoặc vượt quá tồn kho');
            return response()->json(['success' => false], 400);
        }
        
        return response()->json(['success' => false, 'message' => 'Yêu cầu không hợp lệ'], 400);
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
        
        return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ'], 404);
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Giỏ hàng đã được xóa'
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được xóa');
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
