<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryVoucher;
use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryVoucherController extends Controller
{
    public function index()
    {
        $vouchers = InventoryVoucher::with(['warehouse', 'supplier', 'user'])->latest()->paginate(10);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $warehouses = Warehouse::active()->get();
        $suppliers = Supplier::active()->get();

        return view('admin.vouchers.create', compact('warehouses', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:INBOUND,OUTBOUND',
            'warehouse_id' => 'required|exists:warehouses,id',
            'voucher_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $voucher = InventoryVoucher::create([
                'voucher_code' => 'IV'.date('YmdHis').strtoupper(Str::random(4)),
                'type' => $request->type,
                'warehouse_id' => $request->warehouse_id,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'voucher_date' => $request->voucher_date,
                'status' => 'PENDING',
                'note' => $request->note,
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $unitPrice = $item['unit_price'] ?? 0;
                $voucher->details()->create([
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                ]);
                $total += ($item['quantity'] * $unitPrice);
            }

            $voucher->update(['total_amount' => $total]);

            DB::commit();

            return redirect()->route('admin.vouchers.index')->with('success', 'Phiếu kho đã được tạo.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
        }
    }

    public function show(InventoryVoucher $voucher)
    {
        $voucher->load('details.variant.product');

        return view('admin.vouchers.show', compact('voucher'));
    }

    public function complete(InventoryVoucher $voucher)
    {
        if ($voucher->status !== 'PENDING') {
            return back()->with('error', 'Chỉ có thể hoàn tất phiếu ở trạng thái chờ.');
        }

        try {
            DB::beginTransaction();

            foreach ($voucher->details as $detail) {
                $variant = $detail->variant;
                $currentStock = $variant->stock_quantity;
                $currentCostPrice = $variant->cost_price;

                // 1. Update Warehouse specific stock
                $stock = WarehouseStock::firstOrNew([
                    'warehouse_id' => $voucher->warehouse_id,
                    'product_variant_id' => $detail->product_variant_id,
                ]);

                if ($voucher->type === 'INBOUND') {
                    // Weighted Average Cost Calculation
                    $newTotalStock = $currentStock + $detail->quantity;
                    if ($newTotalStock > 0) {
                        $newCostPrice = (($currentCostPrice * $currentStock) + ($detail->unit_price * $detail->quantity)) / $newTotalStock;
                        $variant->cost_price = $newCostPrice;
                    }

                    $stock->quantity += $detail->quantity;
                } else {
                    if ($stock->quantity < $detail->quantity) {
                        throw new \Exception("Sản phẩm {$variant->sku} không đủ tồn kho trong kho này.");
                    }
                    $stock->quantity -= $detail->quantity;
                }
                $stock->save();

                // 2. Update Global stock
                $variant->stock_quantity = WarehouseStock::where('product_variant_id', $detail->product_variant_id)->sum('quantity');
                $variant->save();
            }

            $voucher->update(['status' => 'COMPLETED']);

            DB::commit();

            return back()->with('success', 'Đã xác nhận hoàn tất phiếu kho. Hệ thống đã cập nhật tồn kho và tính toán lại giá vốn.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function variantsSearch(Request $request)
    {
        $q = $request->q;
        $variants = ProductVariant::with('product')
            ->where('sku', 'like', "%$q%")
            ->orWhereHas('product', function ($query) use ($q) {
                $query->where('name', 'like', "%$q%");
            })
            ->limit(10)
            ->get();

        $results = $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'size' => $variant->size,
                'color' => $variant->color,
                'price' => $variant->price ?? ($variant->product->price ?? 0),
                'product' => [
                    'name' => $variant->product->name,
                ],
            ];
        });

        return response()->json($results);
    }

    public function destroy(InventoryVoucher $voucher)
    {
        if ($voucher->status === 'COMPLETED') {
            return back()->with('error', 'Không thể xóa phiếu đã hoàn tất.');
        }
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')->with('success', 'Đã xóa phiếu kho.');
    }
}
