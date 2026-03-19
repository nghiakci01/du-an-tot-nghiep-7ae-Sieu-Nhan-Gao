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

    /**
     * Submit a withdraw request.
     */
    public function requestWithdraw(Request $request)
    {
        $request->validate([
            'amount'               => 'required|numeric|min:50000',
            'user_bank_account_id' => 'required|exists:user_bank_accounts,id,user_id,' . Auth::id(),
        ]);

        $user = Auth::user();
        $amount = (float) $request->amount;

        if ($user->wallet_balance < $amount) {
            return back()->with('wallet_error', 'Số dư ví không đủ để rút tiền.');
        }

        // Deduct the balance immediately so they can't double spend
        $result = $this->wallet->debit($user, $amount, 'Yêu cầu rút tiền đang được xử lý', 'withdraw_request');

        if ($result === false) {
            return back()->with('wallet_error', 'Số dư ví không đủ. Vui lòng thử lại.');
        }

        \App\Models\WalletWithdrawRequest::create([
            'user_id'              => $user->id,
            'user_bank_account_id' => $request->user_bank_account_id,
            'amount'               => $amount,
            'status'               => 'pending',
        ]);

        return back()->with('wallet_success', 'Yêu cầu rút tiền thành công! Admin sẽ duyệt sớm nhất có thể.');
    }
}
