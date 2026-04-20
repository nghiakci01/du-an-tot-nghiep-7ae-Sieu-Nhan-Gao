<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturnRequest;
use App\Models\User;
use App\Services\ReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        return view('admin.returns.index', compact('requests', 'tab', 'settings'));
    }

    public function approve(\App\Http\Requests\Generated\OrderReturnAdminNoteRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $returnReq = OrderReturnRequest::findOrFail($id);
            $this->returnService->approve($returnReq, $user, $validated['admin_note']);

            return redirect()->back()->with('success', 'Đã duyệt yêu cầu trả hàng.');
        } catch (\Exception $e) {
            Log::error("Approve return error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi duyệt: ' . $e->getMessage());
        }
    }

    public function reject(\App\Http\Requests\Generated\OrderReturnAdminNoteRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $returnReq = OrderReturnRequest::findOrFail($id);
            $this->returnService->reject($returnReq, $user, $validated['admin_note']);

            return redirect()->back()->with('success', 'Yêu cầu trả hàng đã bị từ chối.');
        } catch (\Exception $e) {
            Log::error("Reject return error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi từ chối: ' . $e->getMessage());
        }
    }

    public function markAsShipping($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $returnReq = OrderReturnRequest::findOrFail($id);
            $this->returnService->markAsShipping($returnReq, $user);

            return redirect()->back()->with('success', 'Đã cập nhật trạng thái khách đang gửi hàng về.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
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

    public function complete(Request $request, $id)
    {
        try {
            $returnReq = OrderReturnRequest::findOrFail($id);

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->returnService->complete($returnReq, $user);

            $msg = 'Đã hoàn tất quy trình trả hàng và hoàn tiền cho khách.';

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function generateGhnCode($id)
    {
        try {
            $returnRequest = OrderReturnRequest::with(['items.orderItem.product', 'order'])->findOrFail($id);
            
            // Check if GHN is enabled
            $ghnProvider = app(\App\Services\Shipping\GhnShippingProvider::class);
            
            $result = $ghnProvider->createReturnOrder($returnRequest);
            $trackingCode = data_get($result, 'data.order_code');

            if ($trackingCode) {
                $returnRequest->update(['tracking_code' => $trackingCode]);
                
                return response()->json([
                    'success' => true,
                    'tracking_code' => $trackingCode,
                    'message' => 'Đã tạo mã vận đơn GHN thành công.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy mã vận đơn trong phản hồi từ GHN.'
            ], 400);

        } catch (\Exception $e) {
            Log::error("GHN return code error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
