<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    // Redirect user to VNPAY
    public function createPayment(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');

        $vnp_TxnRef = $order->id . '_' . time(); // Append time to avoid duplicate txnref
        $vnp_OrderInfo = "Thanh toan don hang " . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->final_total * 100; // VNPAY expects amount in VND multiplied by 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    // Handle return from VNPAY
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        // Retrieve Order ID from txn ref
        $order_id = explode('_', $inputData['vnp_TxnRef'])[0];
        $order = Order::find($order_id);

        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                // Success
                if ($order && $order->payment_status !== 'paid') {
                    $order->payment_status = 'paid';
                    $order->status = Order::STATUS_CONFIRMED;
                    $order->transaction_id = $inputData['vnp_TransactionNo'];
                    $order->save();
                    
                    // Add order history log
                    $order->histories()->create([
                        'status' => Order::STATUS_CONFIRMED,
                        'note' => 'Thanh toán thành công qua VNPAY. Mã GD: ' . $inputData['vnp_TransactionNo']
                    ]);
                }
                return redirect()->route('checkout.success', $order->id)->with('success', 'Thanh toán thành công qua VNPAY!');
            } else {
                // Failed or cancelled
                if ($order && $order->payment_status !== 'paid') {
                    $order->payment_status = 'failed';
                    $order->save();
                }
                return redirect()->route('checkout.index')->with('error', 'Thanh toán VNPAY thất bại hoặc đã bị hủy.');
            }
        } else {
            return redirect()->route('checkout.index')->with('error', 'Chữ ký báo mật VNPAY không hợp lệ.');
        }
    }
}
