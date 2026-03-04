<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('variants')->latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Tìm kiếm theo tên sản phẩm
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  // Hoặc tìm kiếm theo SKU củabiến thể
                  ->orWhereHas('variants', function($sq) use ($searchTerm) {
                      $sq->where('sku', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $products = $query->paginate(10);
        $products->appends(['search' => $request->search]); // keep context for pagination

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
            'stock_quantity' => $request->quantity,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tồn kho thành công cho SKU: '.$variant->sku,
        ]);
    }
}
