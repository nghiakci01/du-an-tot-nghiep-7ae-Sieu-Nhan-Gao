<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->latest()->paginate(10);
        return view('admin.stock.index', compact('products'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        $variant->update([
            'stock_quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tồn kho thành công cho SKU: ' . $variant->sku
        ]);
    }
}
