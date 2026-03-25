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
     * VNPay IPN (Server-to-Server callback)
     */
    public function vnpayCallback(Request $request)
    {
        Log::info('VNPay IPN received:', $request->all());

        $result = $this->vnpayService->verifyCallback($request->all());

        $orderId = $this->vnpayService->getOrderIdFromRef($result['data']['txn_ref'] ?? '');

        if (!$orderId) {
            Log::error('Invalid order ID from VNPay IPN');
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        // Kiểm tra trùng lặp: nếu đã paid thì không update lại
        if ($order->payment_status === 'paid') {
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
        }

        if ($result['success']) {
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $result['data']['transaction_id'] ?? null,
            ]);

            Log::info('VNPay IPN: Payment successful for order #' . $orderId);

            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        } else {
            $order->update([
                'payment_status' => 'failed',
            ]);

            Log::warning('VNPay IPN: Payment failed for order #' . $orderId . ': ' . $result['message']);

            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }
    }

    /**
     * VNPay Return Page (User returns from VNPay)
     */
    public function vnpayReturn(Request $request)
    {
        Log::info('VNPay Return received:', $request->all());

        $result = $this->vnpayService->verifyCallback($request->all());

        $orderId = $this->vnpayService->getOrderIdFromRef($result['data']['txn_ref'] ?? '');

        if (!$orderId) {
            return redirect()->route('shop')->with('error', 'Không tìm thấy đơn hàng từ VNPay.');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('shop')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Set session for guest verification
        if (!Auth::check()) {
            session(['verified_order_id' => $order->id]);
        }

        if ($result['success']) {
            // Cập nhật trạng thái thanh toán (nếu IPN chưa xử lý)
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $result['data']['transaction_id'] ?? null,
                ]);
            }

            // Gửi email xác nhận đơn hàng
            try {
                \Illuminate\Support\Facades\Mail::to($order->email)
                    ->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('Lỗi gửi email xác nhận đơn hàng VNPAY #' . $orderId . ': ' . $e->getMessage());
            }

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Thanh toán VNPay thành công!');
        } else {
            // Thanh toán thất bại - vẫn redirect đến trang success (hiển thị trạng thái thất bại)
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'failed',
                ]);
            }

            return redirect()->route('checkout.success', $order->id)
                ->with('error', 'Thanh toán VNPay không thành công. Bạn có thể thử lại.');
        }
    }

    /**
     * Check payment status (AJAX)
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

    /**
     * Retry VNPay payment for a failed/pending order
     */
    public function retryVnpay($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->payment_method !== 'VNPAY') {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đơn hàng này đã được thanh toán.');
        }

        // Reset payment status to pending
        $order->update(['payment_status' => 'pending']);

        $paymentUrl = $this->vnpayService->getPaymentUrl(
            $order->id,
            $order->final_total
        );

        return redirect($paymentUrl);
    }
}

