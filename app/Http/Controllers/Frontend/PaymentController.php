<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VnpayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    /**
     * VNPay Callback Handler
     */
    public function vnpayCallback(Request $request)
    {
        Log::info('VNPay Callback received:', $request->all());

        // Xác thực callback
        $result = $this->vnpayService->verifyCallback($request->all());

        // Lấy order ID từ TxnRef
        $orderId = $this->vnpayService->getOrderIdFromRef($result['data']['txn_ref'] ?? '');

        if (!$orderId) {
            Log::error('Invalid order ID from VNPay callback');
            return response()->json(['success' => false, 'message' => 'Order ID không hợp lệ']);
        }

        $order = Order::findOrFail($orderId);

        if ($result['success']) {
            // Thanh toán thành công
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $result['data']['transaction_id'] ?? null,
            ]);

            Log::info('Payment successful for order ' . $orderId);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công',
                'order_id' => $orderId
            ]);
        } else {
            // Thanh toán thất bại
            $order->update([
                'payment_status' => 'failed',
            ]);

            Log::warning('Payment failed for order ' . $orderId . ': ' . $result['message']);

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'order_id' => $orderId
            ]);
        }
    }

    /**
     * VNPay Return Page (User returns from VNPay)
     */
    public function vnpayReturn(Request $request)
    {
        Log::info('VNPay Return received:', $request->all());

        // Xác thực callback
        $result = $this->vnpayService->verifyCallback($request->all());

        // Lấy order ID từ TxnRef
        $orderId = $this->vnpayService->getOrderIdFromRef($result['data']['txn_ref'] ?? '');

        if (!$orderId) {
            return redirect()->route('checkout.index')->with('error', 'Order ID không hợp lệ');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Đơn hàng không tồn tại');
        }

        if ($result['success']) {
            // Cập nhật trạng thái thanh toán
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $result['data']['transaction_id'] ?? null,
            ]);

            // Set session for guest verification if not logged in
            if (!Auth::check()) {
                session(['verified_order_id' => $order->id]);
            }

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Thanh toán VNPay thành công!');
        } else {
            return redirect()->route('checkout.index')
                ->with('error', 'Thanh toán VNPay thất bại: ' . $result['message']);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!Auth::check() && session('verified_order_id') != $orderId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method,
            'transaction_id' => $order->transaction_id,
        ]);
    }
}
