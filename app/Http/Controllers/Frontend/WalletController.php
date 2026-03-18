<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WalletTopupRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WalletController extends Controller
{
    public function __construct(private WalletService $wallet) {}

    /**
     * Show wallet page (redirects to account tab).
     */
    public function index()
    {
        return redirect()->route('account.index', ['#wallet']);
    }

    /**
     * Submit a top-up request.
     */
    public function requestTopup(Request $request)
    {
        $request->validate([
            'amount'        => 'required|numeric|min:10000|max:100000000',
            'bank_name'     => 'nullable|string|max:100',
            'transfer_note' => 'nullable|string|max:255',
            'proof_image'   => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('wallet/proofs', 'public');
        }

        WalletTopupRequest::create([
            'user_id'       => Auth::id(),
            'amount'        => $request->amount,
            'bank_name'     => $request->bank_name,
            'transfer_note' => $request->transfer_note,
            'proof_image'   => $imagePath,
            'status'        => 'pending',
        ]);

        return back()->with('wallet_success', 'Yêu cầu nạp tiền đã được gửi! Vui lòng chờ admin xét duyệt.');
    }
}
