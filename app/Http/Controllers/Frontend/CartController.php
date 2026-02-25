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
        
        // Enrich cart data with available variants for selection in UI
        foreach ($cart as $id => &$details) {
            $total += $details['price'] * $details['quantity'];
            
            $product = Product::with('variants.sizeRelationship', 'variants.colorRelationship')->find($details['product_id']);
            if ($product) {
                // Get unique sizes and colors available for this product
                $details['available_sizes'] = $product->variants->pluck('sizeRelationship')->unique('id')->whereNotNull();
                $details['available_colors'] = $product->variants->pluck('colorRelationship')->unique('id')->whereNotNull();
                
                // Also get all valid variant combinations for this product to help client-side selection
                $details['product_variants'] = $product->variants;
            }
        }
        
        return view('frontend.cart.index', compact('cart', 'total'));
    }

    public function changeVariant(Request $request)
    {
        $request->validate([
            'old_variant_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable',
            'color_id' => 'nullable',
        ]);

        $oldVariantId = $request->old_variant_id;
        $productId = $request->product_id;
        $sizeId = $request->size_id;
        $colorId = $request->color_id;

        $cart = session()->get('cart', []);

        if (!isset($cart[$oldVariantId])) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        // Find the new variant based on product, size, and color
        $query = ProductVariant::where('product_id', $productId);
        if ($sizeId) $query->where('size_id', $sizeId);
        if ($colorId) $query->where('color_id', $colorId);
        
        $newVariant = $query->first();

        if (!$newVariant) {
            return response()->json(['success' => false, 'message' => 'Phiên bản sản phẩm này không tồn tại'], 404);
        }

        // Check stock for the new variant
        if ($newVariant->stock_quantity < $cart[$oldVariantId]['quantity']) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không đủ hàng'], 400);
        }

        $oldQuantity = $cart[$oldVariantId]['quantity'];
        $product = Product::find($productId);

        // Remove old variant from cart session
        unset($cart[$oldVariantId]);

        // Determine price for new variant
        $itemPrice = $newVariant->price ?? $product->price;
        if ($newVariant->sale_price && $newVariant->sale_price < ($newVariant->price ?? PHP_INT_MAX)) {
            $itemPrice = $newVariant->sale_price;
        }

        // Add or merge new variant in cart
        if (isset($cart[$newVariant->id])) {
            $cart[$newVariant->id]['quantity'] += $oldQuantity;
        } else {
            $cart[$newVariant->id] = [
                "product_id" => $productId,
                "variant_id" => $newVariant->id,
                "name" => $product->name,
                "quantity" => $oldQuantity,
                "price" => $itemPrice,
                "image" => $product->image,
                "size" => $newVariant->size,
                "color" => $newVariant->color,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Đã đổi phiên bản sản phẩm',
            'redirect' => route('cart.index') // Refresh the page to update all UI elements easily
        ]);
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
                    ->with('info', 'Please select size and color before adding to cart.');
            } else {
                return redirect()->back()->with('error', 'This product has no available variants.');
            }
        }

        $variant = ProductVariant::findOrFail($variantId);
        $cart = session()->get('cart', []);

        // Check availability
        if ($variant->stock_quantity < $request->quantity) {
             return redirect()->back()->with('error', 'Product does not have enough stock.');
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
