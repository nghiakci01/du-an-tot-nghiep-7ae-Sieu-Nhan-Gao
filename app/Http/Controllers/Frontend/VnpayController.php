<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function createPayment(Order $order)
    {
        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_Returnurl = config('services.vnpay.return_url');
        $vnp_TxnRef = $order->id . '_' . time(); // Payment Reference
        $vnp_OrderInfo = "Thanh toan don hang " . $order->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->final_total * 100; // Multiplied by 100 per VNPAY requirement
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Can be left empty for user to choose
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

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

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
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    public function callback(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

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

        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $orderId = explode('_', $request->vnp_TxnRef)[0];
        $order = Order::find($orderId);

        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                if ($order && $order->status == 'pending') {
                    $order->update(['status' => 'confirmed']); // Mark as paid/confirmed

                    try {
                        \Illuminate\Support\Facades\Mail::to($order->email)->send(new \App\Mail\OrderConfirmationMail($order));
                    } catch (\Exception $e) {
                        \Log::error('Có lỗi xảy ra khi gửi email xác nhận đặt hàng VNPAY: ' . $e->getMessage());
                    }
                }
                return redirect()->route('checkout.success', $orderId)->with('success', 'Thanh toán VNPAY thành công!');
            } else {
                if ($order && $order->status == 'pending') {
                    $order->update(['status' => 'cancelled']);
                }
                return redirect()->route('checkout.success', $orderId)->with('error', 'Giao dịch thanh toán bị hủy hoặc không thành công.');
            }
        } else {
            return redirect()->route('checkout.index')->with('error', 'Chữ ký không hợp lệ.');
        }
    }
}
