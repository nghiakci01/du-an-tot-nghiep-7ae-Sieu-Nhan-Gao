<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDeliveryProof;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShipperOrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of assigned orders for the shipper.
     */
    public function index()
    {
        $shipper = Auth::user();
        $query = Order::where('shipper_id', $shipper->id)->latest();

        if (request()->has('status') && request()->status != '') {
            $query->where('status', request('status'));
        }

        $orders = $query->paginate(10);

        return view('staff.orders.index', compact('orders'));
    }

    /**
     * Display the specific order details.
     */
    public function show($id)
    {
        $shipper = Auth::user();
        $order = Order::where('shipper_id', $shipper->id)
            ->with(['items.product', 'items.variant', 'user'])
            ->findOrFail($id);

        return view('staff.orders.show', compact('order'));
    }

    /**
     * Shipper accepts the order to start delivery.
     */
    public function accept(Order $order)
    {
        $this->authorizeShipper($order);

        if ($order->status !== Order::STATUS_CONFIRMED) {
            return back()->with('error', 'Chỉ có thể nhận giao các đơn hàng đang ở trạng thái Đã xác nhận.');
        }

        try {
            $this->orderService->updateOrderStatus($order, Order::STATUS_SHIPPED, Auth::user(), 'Shipper đã xác nhận đi giao hàng.');
            return redirect()->route('staff.orders.show', $order->id)->with('success', 'Bạn đã nhận đơn hàng này. Chúc bạn giao hàng thuận lợi!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Shipper marks the order as completed (delivered successfully).
     */
    public function complete(Request $request, Order $order)
    {
        $this->authorizeShipper($order);

        if ($request->isMethod('get')) {
            return redirect()->route('staff.orders.show', $order->id)
                ->with('info', 'Để hoàn thành đơn hàng, vui lòng sử dụng nút "Hoàn thành" trong trang chi tiết đơn hàng.');
        }

        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->with('error', 'Vui lòng xác nhận đi giao đơn hàng này trước khi báo Hoàn thành.');
        }

        $request->validate([
            'delivery_image' => 'required|image|max:10240', // 10MB max
        ], [
            'delivery_image.required' => 'Vui lòng cung cấp hình ảnh xác nhận đã giao hàng.',
            'delivery_image.image' => 'File tải lên phải là định dạng hình ảnh.',
        ]);

        try {
            // Store Image
            if ($request->hasFile('delivery_image')) {
                $file = $request->file('delivery_image');
                
                if (!$file->isValid()) {
                    throw new \Exception('File tải lên không hợp lệ hoặc bị lỗi: ' . $file->getErrorMessage());
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $targetDir = storage_path('app/public/delivery_proofs/images');
                
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Di chuyển file thủ công để tránh lỗi "Path cannot be empty" từ FilesystemAdapter
                $file->move($targetDir, $filename);
                $imagePath = 'delivery_proofs/images/' . $filename;
                
                OrderDeliveryProof::create([
                    'order_id' => $order->id,
                    'file_path' => $imagePath,
                    'file_type' => 'image'
                ]);
            }

            $this->orderService->updateOrderStatus($order, Order::STATUS_COMPLETED, Auth::user(), 'Shipper báo giao hàng thành công.');
            return redirect()->route('staff.orders.index')->with('success', 'Đơn hàng đã được giao thành công. Giỏi quá!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Shipper marks the order as failed delivery.
     */
    public function fail(Request $request, Order $order)
    {
        $this->authorizeShipper($order);

        $request->validate([
            'delivery_note' => 'required|string|max:1000',
        ]);

        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->with('error', 'Chỉ có thể báo Thất bại cho những đơn hàng đang trong quá trình đi giao.');
        }

        try {
            $order->update(['delivery_note' => $request->delivery_note]);
            $this->orderService->updateOrderStatus($order, Order::STATUS_FAILED, Auth::user(), 'Shipper báo giao hàng thất bại: ' . $request->delivery_note);
            return redirect()->route('staff.orders.index')->with('warning', 'Đã ghi nhận giao hàng thất bại cho đơn hàng này.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Ensure the current user is the assigned shipper for this order.
     */
    protected function authorizeShipper(Order $order)
    {
        if ($order->shipper_id !== Auth::id()) {
            abort(403, 'Bạn không được phép thực hiện thao tác trên đơn hàng này.');
        }
    }
}
