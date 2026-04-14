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
        return back()->with('wallet_error', 'Tính năng nạp tiền tạm thời bị khóa.');
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

        /** @var \App\Models\User $user */
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
