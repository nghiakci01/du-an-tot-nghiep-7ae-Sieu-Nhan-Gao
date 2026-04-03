<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class VnpayService
{
    private $tmnCode;
    private $hashSecret;
    private $vnpayUrl;
    private $returnUrl;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->vnpayUrl = config('vnpay.url');
        $this->returnUrl = config('vnpay.return_url');
    }

    /**
     * Tạo URL thanh toán VNPay
     *
     * @param int $orderId
     * @param float $amount Số tiền tính theo VND
     * @param string $bankCode (optional)
     * @return string
     */
    public function getPaymentUrl($orderId, $amount, $bankCode = null)
    {
        // Tạo input data cho VNPay
        $vnp_TxnRef = $orderId . '_' . time();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->tmnCode,
            "vnp_Amount" => intval($amount * 100), // VNPay dùng đơn vị là 100 đồng
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $this->getClientIp(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toán đơn hàng #" . $orderId . " tại Elite Shop",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $this->returnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Thêm mã ngân hàng nếu có
        if (!empty($bankCode)) {
            $inputData["vnp_BankCode"] = $bankCode;
        }

        // Sắp xếp theo thứ tự alphabetical
        ksort($inputData);

        // Tạo query string
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $key = (string) $key;
            $value = (string) $value;
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $i = 1;
                $hashdata .= urlencode($key) . "=" . urlencode($value);
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo secure hash
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        $paymentUrl = $this->vnpayUrl . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return $paymentUrl;
    }

    /**
     * Xác thực callback từ VNPay
     *
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'data' => array]
     */
    public function verifyCallback($data)
    {
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? null;

        if (empty($vnp_SecureHash)) {
            return [
                'success' => false,
                'message' => 'Secure Hash không hợp lệ',
                'data' => null
            ];
        }

        // Bỏ vnp_SecureHash và vnp_SecureHashType khỏi dữ liệu
        $inputData = $data;
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sắp xếp theo thứ tự alphabetical
        ksort($inputData);

        // Tạo hash data
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            $key = (string) $key;
            $value = (string) $value;
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $i = 1;
                $hashdata .= urlencode($key) . "=" . urlencode($value);
            }
        }

        // Tính toán secure hash
        $secureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);

        // So sánh secure hash
        if ($secureHash !== $vnp_SecureHash) {
            return [
                'success' => false,
                'message' => 'Secure Hash không hợp lệ',
                'data' => null
            ];
        }

        // Kiểm tra response code
        $responseCode = $data['vnp_ResponseCode'] ?? null;

        if ($responseCode === '00') {
            return [
                'success' => true,
                'message' => 'Thanh toán thành công',
                'data' => [
                    'txn_ref' => $data['vnp_TxnRef'] ?? null,
                    'transaction_id' => $data['vnp_TransactionNo'] ?? null,
                    'amount' => isset($data['vnp_Amount']) ? intval($data['vnp_Amount']) / 100 : 0,
                    'response_code' => $responseCode,
                    'order_info' => $data['vnp_OrderInfo'] ?? null,
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Thanh toán không thành công. Mã lỗi: ' . $responseCode,
                'data' => [
                    'txn_ref' => $data['vnp_TxnRef'] ?? null,
                    'response_code' => $responseCode,
                ]
            ];
        }
    }

    /**
     * Lấy IP client
     *
     * @return string
     */
    private function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }

        return $ip ?: '127.0.0.1';
    }

    /**
     * Parse transaction ref để lấy order ID
     *
     * @param string $txnRef
     * @return int
     */
    public function getOrderIdFromRef($txnRef)
    {
        $parts = explode('_', $txnRef);
        return intval($parts[0] ?? 0);
    }
}
