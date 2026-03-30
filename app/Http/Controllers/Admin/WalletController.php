<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTopupRequest;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    /**
     * List all top-up requests.
     */
    public function index(Request $request)
    {
        $status   = $request->input('status', 'pending');
        $requests = WalletTopupRequest::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.wallet.index', compact('requests', 'status'));
    }

    /**
     * Approve a top-up request and credit user wallet.
     */
    public function approve(WalletTopupRequest $topupRequest)
    {
        if (! $topupRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $this->walletService->credit(
            $topupRequest->user,
            (float) $topupRequest->amount,
            'Nạp tiền ví - Yêu cầu #' . $topupRequest->id,
            'topup',
            $topupRequest->id
        );

        $topupRequest->update([
            'status'       => 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã duyệt và cộng ' . number_format($topupRequest->amount) . '₫ vào ví.');
    }

    /**
     * Reject a top-up request.
     */
    public function reject(Request $request, WalletTopupRequest $topupRequest)
    {
        if (! $topupRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $topupRequest->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã từ chối yêu cầu nạp tiền.');
    }

    /**
     * Manually adjust a user's wallet balance.
     */
    public function manualAdjust(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'type'        => 'required|in:credit,debit',
            'amount'      => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($request->type === 'credit') {
            $this->walletService->credit($user, $request->amount, $request->description, 'manual');
            $msg = 'Đã cộng ' . number_format($request->amount) . '₫ vào ví của ' . $user->name;
        } else {
            $result = $this->walletService->debit($user, $request->amount, $request->description, 'manual');
            if ($result === false) {
                return back()->with('error', 'Số dư ví không đủ để trừ.');
            }
            $msg = 'Đã trừ ' . number_format($request->amount) . '₫ từ ví của ' . $user->name;
        }

        return back()->with('success', $msg);
    }

    /**
     * List all withdrawal requests.
     */
    public function withdrawals(Request $request)
    {
        $status   = $request->input('status', 'pending');
        $requests = \App\Models\WalletWithdrawRequest::with(['user', 'bankAccount'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.wallet.withdrawals', compact('requests', 'status'));
    }

    /**
     * Approve a withdrawal request.
     */
    public function approveWithdraw(Request $request, \App\Models\WalletWithdrawRequest $withdrawRequest)
    {
        if (! $withdrawRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        $request->validate([
            'proof_image' => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('wallet/withdraw_proofs', 'public');
        }

        $withdrawRequest->update([
            'status'       => 'approved',
            'proof_image'  => $imagePath,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã duyệt yêu cầu rút '.number_format($withdrawRequest->amount).'₫.');
    }

    /**
     * Reject a withdrawal request.
     */
    public function rejectWithdraw(Request $request, \App\Models\WalletWithdrawRequest $withdrawRequest)
    {
        if (! $withdrawRequest->isPending()) {
            return back()->with('error', 'Yêu cầu này đã được xử lý.');
        }

        // Hoàn lại tiền vào ví
        $this->walletService->credit(
            $withdrawRequest->user,
            (float) $withdrawRequest->amount,
            'Hoàn lại tiền rút ví - Yêu cầu bị từ chối: ' . $request->admin_note,
            'withdraw_refund',
            $withdrawRequest->id
        );

        $withdrawRequest->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Đã từ chối và hoàn tiền lại vào ví.');
    }
}
