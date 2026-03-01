<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);

        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('admin.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Warehouse::create($request->all());

        return redirect()->route('admin.warehouses.index')->with('success', 'Kho hàng đã được tạo thành công.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('admin.warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $warehouse->update($request->all());

        return redirect()->route('admin.warehouses.index')->with('success', 'Kho hàng đã được cập nhật thành công.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')->with('success', 'Kho hàng đã được xóa thành công.');
    }
}
