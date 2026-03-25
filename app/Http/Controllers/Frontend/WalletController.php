<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WalletTopupRequest;
use App\Models\BankSetting;
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
            'amount'          => 'required|numeric|min:10000|max:100000000',
            'bank_setting_id' => 'required|exists:bank_settings,id,is_active,1',
            'transfer_note'   => 'nullable|string|max:255',
            'proof_image'     => 'nullable|image|max:5120',
        ]);

        $bankSetting = BankSetting::find($request->bank_setting_id);
        
        // Generate a unique transfer note if not provided
        $transferNote = $request->transfer_note ?: ('NAP' . Auth::id() . strtoupper(\Illuminate\Support\Str::random(4)));

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('wallet/proofs', 'public');
        }

        $topup = WalletTopupRequest::create([
            'user_id'             => Auth::id(),
            'bank_setting_id'     => $bankSetting->id,
            'amount'              => $request->amount,
            'dest_bank_name'      => $bankSetting->bank_name,
            'dest_account_number' => $bankSetting->account_number,
            'dest_account_name'   => $bankSetting->account_name,
            'transfer_note'       => $transferNote,
            'proof_image'         => $imagePath,
            'status'              => 'pending',
        ]);

        return back()->with([
            'wallet_success' => 'Yêu cầu nạp tiền đã được khởi tạo! Vui lòng chuyển khoản theo thông tin bên dưới.',
            'show_qr_id'     => $topup->id
        ]);
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
