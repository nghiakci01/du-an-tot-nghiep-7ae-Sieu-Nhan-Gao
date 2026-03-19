<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\OrderReturnRequest::with(['user', 'order']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $requests = $query->latest()->paginate(15);
        $tab = $request->get('status', 'all');
        
        return view('admin.returns.index', compact('requests', 'tab'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000'
        ]);
        
        $returnReq = \App\Models\OrderReturnRequest::findOrFail($id);
        if (!$returnReq->isPending()) {
            return redirect()->back()->with('error', 'Chỉ có thể duyệt yêu cầu đang chờ xử lý.');
        }

        $returnReq->update([
            'status' => 'approved',
            'admin_note' => $request->admin_note,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã duyệt yêu cầu hoàn trả và cấp hướng dẫn/mã vận chuyển.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000'
        ]);
        
        $returnReq = \App\Models\OrderReturnRequest::findOrFail($id);
        if ($returnReq->isCompleted()) {
            return redirect()->back()->with('error', 'Không thể từ chối yêu cầu đã hoàn thành.');
        }

        $returnReq->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Yêu cầu hoàn trả đã bị từ chối.');
    }

    public function completeRefund(Request $request, $id, \App\Services\WalletService $walletService, \App\Services\OrderService $orderService)
    {
        $returnReq = \App\Models\OrderReturnRequest::with(['user', 'order'])->findOrFail($id);
        
        if (!$returnReq->isApproved()) {
            return redirect()->back()->with('error', 'Chỉ có thể hoàn tiền cho yêu cầu đã được duyệt chờ nhận hàng.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($returnReq, $walletService, $orderService) {
                // 1. Update request status
                $returnReq->update([
                    'status' => 'completed',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                // 2. Add money to wallet
                $walletService->credit(
                    $returnReq->user, 
                    $returnReq->refund_amount, 
                    'Hoàn tiền đơn hàng #' . $returnReq->order_id, 
                    'order_return', 
                    $returnReq->id
                );

                // 3. Update order status to STATUS_RETURNED
                $orderService->updateOrderStatus($returnReq->order, \App\Models\Order::STATUS_RETURNED, auth()->user(), 'Admin xác nhận hoàn tiền');
            });

            return redirect()->back()->with('success', 'Đã xác nhận nhận hàng và hoàn trả tiền vào ví khách hàng thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
