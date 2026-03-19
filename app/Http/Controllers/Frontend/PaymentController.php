<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\VnpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        protected VnpayService $vnpayService,
        protected OrderService $orderService
    ) {}

    /**
     * Redirect user to VNPAY to complete payment.
     */
    public function createPayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        if ($order->payment_status === 'paid') {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                session(['verified_order_id' => $order->id]);
            }
            return redirect()->route('checkout.success', $order->id)
                ->with('info', 'Đơn hàng đã được thanh toán.');
        }

        $paymentUrl = $this->vnpayService->createPaymentUrl($order, $request->ip());

        Log::info('VNPAY: Redirecting order #' . $order->id . ' to payment gateway');

        return redirect($paymentUrl);
    }

    /**
     * Handle Return URL — user redirected back from VNPAY.
     */
    public function vnpayReturn(Request $request)
    {
        $vnpData = $this->vnpayService->extractVnpParams($request->all());

        if (!$this->vnpayService->verifySignature($vnpData)) {
            Log::warning('VNPAY Return: Invalid secure hash', $vnpData);
            return redirect()->route('checkout.index')
                ->with('error', 'Chữ ký không hợp lệ từ VNPAY.');
        }

        $orderId = $this->vnpayService->extractOrderId($vnpData['vnp_TxnRef'] ?? '');
        $order = Order::find($orderId);
        $responseCode = $vnpData['vnp_ResponseCode'] ?? '';

        if ($responseCode === '00') {
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'status'         => Order::STATUS_CONFIRMED,
                    'payment_status' => 'paid',
                    'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
                ]);

                try {
                    Mail::to($order->email)->send(new \App\Mail\OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    Log::error('VNPAY: Email failed for order #' . $orderId . ': ' . $e->getMessage());
                }
            }

            if (!\Illuminate\Support\Facades\Auth::check()) {
                session(['verified_order_id' => $orderId]);
            }

            // Clear the cart upon successful VNPAY payment
            $selectedIds = session('selected_checkout_ids');
            if ($selectedIds && is_array($selectedIds)) {
                app(\App\Services\CartService::class)->removeItems($selectedIds);
            } else {
                app(\App\Services\CartService::class)->clearCart();
            }
            session()->forget(['coupon_code', 'discount_amount', 'selected_checkout_ids']);

            return redirect()->route('checkout.success', $orderId)
                ->with('success', 'Thanh toán VNPAY thành công! 🎉');
        }

        $message = VnpayService::getResponseMessage($responseCode);

        if ($order && $order->payment_status !== 'paid' && $order->status === Order::STATUS_PENDING) {
            $order->update(['payment_status' => 'failed']);
            try {
                $this->orderService->updateOrderStatus($order, Order::STATUS_CANCELLED, null, 'Hủy đơn do thanh toán VNPAY thất bại/khách tự hủy: ' . $message);
            } catch (\Exception $e) {
                Log::error('VNPAY Return: Failed to cancel order #' . $orderId . ': ' . $e->getMessage());
            }
        }

        Log::warning('VNPAY Return: Payment failed', ['order_id' => $orderId, 'code' => $responseCode, 'message' => $message]);

        return redirect()->route('checkout.index')
            ->with('error', 'Thanh toán VNPAY thất bại: ' . $message);
    }

    /**
     * Handle IPN URL — server-to-server notification from VNPAY.
     * Must return JSON {RspCode, Message}.
     */
    public function ipn(Request $request)
    {
        $vnpData = $this->vnpayService->extractVnpParams($request->all());

        // Step 1: Verify checksum
        if (!$this->vnpayService->verifySignature($vnpData)) {
            Log::warning('VNPAY IPN: Invalid checksum', $vnpData);
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid Checksum']);
        }

        // Step 2: Find order
        $orderId = $this->vnpayService->extractOrderId($vnpData['vnp_TxnRef'] ?? '');
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning('VNPAY IPN: Order not found', ['txnRef' => $vnpData['vnp_TxnRef'] ?? '']);
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        // Step 3: Check amount
        $vnpAmount = (int) ($vnpData['vnp_Amount'] ?? 0);
        $expectedAmount = (int) ($order->final_total * 100);

        if ($vnpAmount !== $expectedAmount) {
            Log::warning('VNPAY IPN: Amount mismatch', [
                'order_id' => $orderId,
                'vnp_amount' => $vnpAmount,
                'expected' => $expectedAmount,
            ]);
            return response()->json(['RspCode' => '04', 'Message' => 'Invalid Amount']);
        }

        // Step 4: Check if already processed
        if ($order->payment_status === 'paid') {
            Log::info('VNPAY IPN: Order #' . $orderId . ' already confirmed');
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
        }

        // Step 5: Update order based on response code
        $responseCode = $vnpData['vnp_ResponseCode'] ?? '';

        if ($responseCode === '00') {
            $order->update([
                'status'         => Order::STATUS_CONFIRMED,
                'payment_status' => 'paid',
                'transaction_id' => $vnpData['vnp_TransactionNo'] ?? null,
            ]);

            try {
                Mail::to($order->email)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Exception $e) {
                Log::error('VNPAY IPN: Email failed for order #' . $orderId . ': ' . $e->getMessage());
            }

            Log::info('VNPAY IPN: Order #' . $orderId . ' payment confirmed');
        } else {
            if ($order->status === Order::STATUS_PENDING) {
                $order->update(['payment_status' => 'failed']);
                try {
                    $this->orderService->updateOrderStatus($order, Order::STATUS_CANCELLED, null, 'Hủy đơn qua IPN do giao dịch lỗi VNPAY mã: ' . $responseCode);
                } catch (\Exception $e) {
                    Log::error('VNPAY IPN: Failed to cancel order #' . $orderId . ': ' . $e->getMessage());
                }
            } else {
                $order->update(['payment_status' => 'failed']);
            }
            Log::warning('VNPAY IPN: Order #' . $orderId . ' payment failed with code ' . $responseCode);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }
}
