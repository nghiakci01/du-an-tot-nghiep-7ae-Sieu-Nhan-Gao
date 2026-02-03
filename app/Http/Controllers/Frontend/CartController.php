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
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = ProductVariant::findOrFail($request->variant_id);

        $cart = session()->get('cart', []);

        // Check availability
        if ($variant->stock_quantity < $request->quantity) {
             return redirect()->back()->with('error', 'Sản phẩm không đủ số lượng tồn kho.');
        }

        if(isset($cart[$variant->id])) {
            $cart[$variant->id]['quantity'] += $request->quantity;
        } else {
            $cart[$variant->id] = [
                "product_id" => $product->id,
                "variant_id" => $variant->id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "image" => $product->image,
                "size" => $variant->size,
                "color" => $variant->color,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $variant = ProductVariant::find($request->id);

            if ($variant && $variant->stock_quantity >= $request->quantity) {
                 $cart[$request->id]["quantity"] = $request->quantity;
                 session()->put('cart', $cart);
                 
                 // Calculate new totals
                 $itemTotal = $cart[$request->id]['price'] * $request->quantity;
                 $cartTotal = 0;
                 $cartCount = 0;
                 foreach($cart as $item) {
                    $cartTotal += $item['price'] * $item['quantity'];
                    $cartCount += $item['quantity'];
                 }

                 return response()->json([
                     'success' => true,
                     'message' => 'Giỏ hàng đã được cập nhật',
                     'item_total' => number_format($itemTotal) . ' đ',
                     'cart_total' => number_format($cartTotal) . ' đ',
                     'cart_count' => $cartCount
                 ]);
            } else {
                 return response()->json([
                     'success' => false,
                     'message' => 'Số lượng không hợp lệ hoặc vượt quá tồn kho'
                 ], 400);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Yêu cầu không hợp lệ'], 400);
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                
                // Calculate new totals
                 $cartTotal = 0;
                 $cartCount = 0;
                 foreach($cart as $item) {
                    $cartTotal += $item['price'] * $item['quantity'];
                    $cartCount += $item['quantity'];
                 }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng',
                    'cart_total' => number_format($cartTotal) . ' đ',
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
