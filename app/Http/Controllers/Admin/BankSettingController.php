<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSetting;
use Illuminate\Http\Request;

class BankSettingController extends Controller
{
    public function index()
    {
        $banks = BankSetting::latest()->paginate(10);
        return view('admin.bank-settings.index', compact('banks'));
    }

    public function create()
    {
        return view('admin.bank-settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_id' => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
        ]);

        if ($request->is_default) {
            BankSetting::where('is_default', true)->update(['is_default' => false]);
        }

        BankSetting::create($request->all());

        return redirect()->route('admin.bank-settings.index')->with('success', 'Thêm ngân hàng thành công.');
    }

    public function edit($id)
    {
        $bank = BankSetting::findOrFail($id);
        return view('admin.bank-settings.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_id' => 'required|string|max:50',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:255',
        ]);

        $bank = BankSetting::findOrFail($id);

        if ($request->has('is_default') && $request->is_default) {
            BankSetting::where('id', '!=', $id)->update(['is_default' => false]);
        }

        $bank->update([
            'bank_name' => $request->bank_name,
            'bank_id' => $request->bank_id,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'is_active' => $request->has('is_active'),
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('admin.bank-settings.index')->with('success', 'Cập nhật ngân hàng thành công.');
    }

    public function destroy($id)
    {
        $bank = BankSetting::findOrFail($id);
        $bank->delete();

        return redirect()->route('admin.bank-settings.index')->with('success', 'Xóa ngân hàng thành công.');
    }
}
