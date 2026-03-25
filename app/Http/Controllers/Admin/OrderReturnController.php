<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturnRequest;
use App\Models\User;
use App\Services\ReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderReturnController extends Controller
{
    protected $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->returnService = $returnService;
    }
    public function index(Request $request)
    {
        $query = OrderReturnRequest::with(['user', 'order']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        
        $requests = $query->latest()->paginate(15);
        $tab = $request->input('status', 'all');
        
        return view('admin.returns.index', compact('requests', 'tab'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000'
        ]);
        
        $returnReq = OrderReturnRequest::findOrFail($id);
        // if (!$returnReq->isPending()) { // This check is now handled by the service
        //     return redirect()->back()->with('error', 'Chỉ có thể duyệt yêu cầu đang chờ xử lý.');
        // }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->returnService->approve($returnReq, $user, $request->admin_note);

            return redirect()->back()->with('success', 'Đã duyệt yêu cầu trả hàng.');
        } catch (\Exception $e) {
            \Log::error("Approve return error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000'
        ]);
        
        $returnReq = OrderReturnRequest::findOrFail($id);
        // if ($returnReq->isCompleted()) { // This check is now handled by the service
        //     return redirect()->back()->with('error', 'Không thể từ chối yêu cầu đã hoàn thành.');
        // }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->returnService->reject($returnReq, $user, $request->admin_note);

            return redirect()->back()->with('success', 'Yêu cầu trả hàng đã bị từ chối.');
        } catch (\Exception $e) {
            \Log::error("Reject return error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối: ' . $e->getMessage());
        }
    }

    public function markAsShipping($id)
    {
        $returnReq = OrderReturnRequest::findOrFail($id);
        // if (!$returnReq->isApproved()) { // This check is now handled by the service
        //     return redirect()->back()->with('error', 'Chỉ có thể chuyển sang trạng thái đang di chuyển khi đã duyệt.');
        // }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $this->returnService->markAsShipping($returnReq, $user);
        
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đang di chuyển.');
    }

    public function markAsReceived($id)
    {
        try {
            $returnReq = OrderReturnRequest::findOrFail($id);
            $this->returnService->markAsReceived($returnReq);
            
            return redirect()->back()->with('success', 'Đã xác nhận nhận hàng tại kho.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function completeRefund(Request $request, $id)
    {
        try {
            $returnReq = OrderReturnRequest::findOrFail($id);
            
            // if (!in_array($returnReq->status, ['approved', 'shipping', 'received'])) { // This check is now handled by the service
            //     return redirect()->back()->with('error', 'Chỉ có thể hoàn tiền cho yêu cầu đã được duyệt.');
            // }

            /** @var \App\Models\User $user */
            $user = auth()->user();
            $this->returnService->complete($returnReq, $user);
            
            return redirect()->back()->with('success', 'Đã hoàn tất quy trình trả hàng và hoàn tiền cho khách.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
