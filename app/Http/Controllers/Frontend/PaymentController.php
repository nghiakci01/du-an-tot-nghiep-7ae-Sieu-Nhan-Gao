<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Redirect user to VNPAY Sandbox to complete payment.
     */
    public function createPayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        $vnp_TmnCode    = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url        = config('services.vnpay.url');
        $vnp_Returnurl  = config('services.vnpay.return_url');

        $vnp_TxnRef    = $order->id . '_' . time();
        $vnp_OrderInfo = 'Thanh toan don hang #' . $order->id;
        $vnp_Amount    = (int) ($order->final_total * 100); // VND * 100
        $vnp_IpAddr    = $request->ip();

        $inputData = [
            'vnp_Version'   => '2.1.0',
            'vnp_Command'   => 'pay',
            'vnp_TmnCode'   => $vnp_TmnCode,
            'vnp_Amount'    => $vnp_Amount,
            'vnp_CreateDate'=> date('YmdHis'),
            'vnp_CurrCode'  => 'VND',
            'vnp_IpAddr'    => $vnp_IpAddr,
            'vnp_Locale'    => 'vn',
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => 'billpayment',
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef'    => $vnp_TxnRef,
        ];

        ksort($inputData);

        $hashdata = '';
        $query    = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $paymentUrl    = $vnp_Url . '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        return redirect($paymentUrl);
    }

    /**
     * Handle return callback from VNPAY.
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');

        // Collect all vnp_ params
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Extract order ID from TxnRef (format: orderId_timestamp)
        $orderId = explode('_', $inputData['vnp_TxnRef'] ?? '')[0];
        $order   = Order::find($orderId);

        if ($secureHash !== $vnp_SecureHash) {
            Log::warning('VNPAY: Invalid secure hash for order #' . $orderId);
            return redirect()->route('checkout.index')->with('error', 'Chữ ký không hợp lệ từ VNPAY.');
        }

        $responseCode = $inputData['vnp_ResponseCode'] ?? '';

        if ($responseCode === '00') {
            // Payment success
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'status'         => Order::STATUS_CONFIRMED,
                    'payment_status' => 'paid',
                    'transaction_id' => $inputData['vnp_TransactionNo'] ?? null,
                ]);

                try {
                    Mail::to($order->email)->send(new \App\Mail\OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    Log::error('VNPAY: Không gửi được email xác nhận đơn #' . $orderId . ': ' . $e->getMessage());
                }
            }

            return redirect()->route('checkout.success', $orderId)
                ->with('success', 'Thanh toán VNPAY thành công! 🎉');
        } else {
            // Payment failed or cancelled
            if ($order && $order->payment_status !== 'paid') {
                $order->update(['payment_status' => 'failed']);
            }

            return redirect()->route('checkout.index')
                ->with('error', 'Thanh toán VNPAY thất bại hoặc đã bị hủy. Vui lòng thử lại.');
        }
    }
}
