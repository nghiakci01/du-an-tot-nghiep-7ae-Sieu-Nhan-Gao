<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VnpayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $paymentUrl;
    protected string $returnUrl;
    protected string $apiUrl;

    public function __construct()
    {
        // Read from DB settings first, fallback to config/env
        $this->tmnCode     = \App\Models\Setting::get('vnpay_tmn_code')
                              ?: config('services.vnpay.tmn_code', '');
        $this->hashSecret  = \App\Models\Setting::get('vnpay_hash_secret')
                              ?: config('services.vnpay.hash_secret', '');
        $this->paymentUrl  = \App\Models\Setting::get('vnpay_url')
                              ?: config('services.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->returnUrl   = \App\Models\Setting::get('vnpay_return_url')
                              ?: config('services.vnpay.return_url', url('/vnpay/return'));
        $this->apiUrl      = config('services.vnpay.api_url', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction');
    }

    /**
     * Build VNPAY payment URL and redirect user.
     */
    public function createPaymentUrl(Order $order, string $ipAddr = '127.0.0.1'): string
    {
        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_Command'    => 'pay',
            'vnp_TmnCode'    => $this->tmnCode,
            'vnp_Amount'     => (int) ($order->final_total * 100),
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $ipAddr,
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order->id,
            'vnp_OrderType'  => 'billpayment',
            'vnp_ReturnUrl'  => $this->returnUrl,
            'vnp_TxnRef'     => $order->id . '_' . time(),
            'vnp_ExpireDate' => date('YmdHis', strtotime('+15 minutes')),
        ];

        ksort($inputData);
        $hashData = $this->buildHashData($inputData);
        $query = $this->buildQueryString($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return $this->paymentUrl . '?' . $query . 'vnp_SecureHash=' . $secureHash;
    }

    /**
     * Verify VNPAY response signature (for Return URL and IPN).
     */
    public function verifySignature(array $data): bool
    {
        $vnpSecureHash = $data['vnp_SecureHash'] ?? '';
        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);
        ksort($data);

        $hashData = $this->buildHashData($data);
        $computedHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return hash_equals($computedHash, $vnpSecureHash);
    }

    /**
     * Extract only vnp_ parameters from request data.
     */
    public function extractVnpParams(array $allParams): array
    {
        return array_filter($allParams, fn($key) => str_starts_with($key, 'vnp_'), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Extract order ID from vnp_TxnRef (format: orderId_timestamp).
     */
    public function extractOrderId(string $txnRef): ?int
    {
        $parts = explode('_', $txnRef);
        return isset($parts[0]) && is_numeric($parts[0]) ? (int) $parts[0] : null;
    }

    /**
     * Query transaction result from VNPAY (QueryDR).
     * vnp_Command = querydr
     */
    public function queryTransaction(Order $order): array
    {
        $requestId = Str::uuid()->toString();
        $txnRef = $order->id . '_' . strtotime($order->created_at);
        $transactionDate = $order->created_at->format('YmdHis');

        $inputData = [
            'vnp_RequestId'      => $requestId,
            'vnp_Version'        => '2.1.0',
            'vnp_Command'        => 'querydr',
            'vnp_TmnCode'        => $this->tmnCode,
            'vnp_TxnRef'         => $txnRef,
            'vnp_OrderInfo'      => 'Truy van GD don hang ' . $order->id,
            'vnp_TransactionNo'  => $order->transaction_id ?? '0',
            'vnp_TransactionDate'=> $transactionDate,
            'vnp_CreateDate'     => date('YmdHis'),
            'vnp_IpAddr'         => request()->ip() ?? '127.0.0.1',
        ];

        // QueryDR hash = RequestId|Version|Command|TmnCode|TxnRef|TransactionDate|CreateDate|IpAddr|OrderInfo
        $hashRaw = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        $inputData['vnp_SecureHash'] = hash_hmac('sha512', $hashRaw, $this->hashSecret);

        Log::info('VNPAY QueryDR request for order #' . $order->id, $inputData);

        try {
            $response = Http::post($this->apiUrl, $inputData);
            $result = $response->json();
            Log::info('VNPAY QueryDR response for order #' . $order->id, $result ?? []);
            return $result ?? ['vnp_ResponseCode' => '99', 'vnp_Message' => 'No response'];
        } catch (\Exception $e) {
            Log::error('VNPAY QueryDR error: ' . $e->getMessage());
            return ['vnp_ResponseCode' => '99', 'vnp_Message' => $e->getMessage()];
        }
    }

    /**
     * Request refund via VNPAY (Refund API).
     * vnp_Command = refund
     */
    public function refundTransaction(Order $order, int $amount, string $createdBy, string $transactionType = '02'): array
    {
        $requestId = Str::uuid()->toString();
        $txnRef = $order->id . '_' . strtotime($order->created_at);
        $transactionDate = $order->created_at->format('YmdHis');

        $inputData = [
            'vnp_RequestId'       => $requestId,
            'vnp_Version'         => '2.1.0',
            'vnp_Command'         => 'refund',
            'vnp_TmnCode'         => $this->tmnCode,
            'vnp_TransactionType' => $transactionType, // 02 = hoàn toàn phần, 03 = hoàn một phần
            'vnp_TxnRef'          => $txnRef,
            'vnp_Amount'          => $amount * 100, // VND * 100
            'vnp_OrderInfo'       => 'Hoan tien don hang ' . $order->id,
            'vnp_TransactionNo'   => $order->transaction_id ?? '0',
            'vnp_TransactionDate' => $transactionDate,
            'vnp_CreateBy'        => $createdBy,
            'vnp_CreateDate'      => date('YmdHis'),
            'vnp_IpAddr'          => request()->ip() ?? '127.0.0.1',
        ];

        // Refund hash = RequestId|Version|Command|TmnCode|TransactionType|TxnRef|Amount|TransactionNo|TransactionDate|CreateBy|CreateDate|IpAddr|OrderInfo
        $hashRaw = implode('|', [
            $inputData['vnp_RequestId'],
            $inputData['vnp_Version'],
            $inputData['vnp_Command'],
            $inputData['vnp_TmnCode'],
            $inputData['vnp_TransactionType'],
            $inputData['vnp_TxnRef'],
            $inputData['vnp_Amount'],
            $inputData['vnp_TransactionNo'],
            $inputData['vnp_TransactionDate'],
            $inputData['vnp_CreateBy'],
            $inputData['vnp_CreateDate'],
            $inputData['vnp_IpAddr'],
            $inputData['vnp_OrderInfo'],
        ]);

        $inputData['vnp_SecureHash'] = hash_hmac('sha512', $hashRaw, $this->hashSecret);

        Log::info('VNPAY Refund request for order #' . $order->id, $inputData);

        try {
            $response = Http::post($this->apiUrl, $inputData);
            $result = $response->json();
            Log::info('VNPAY Refund response for order #' . $order->id, $result ?? []);
            return $result ?? ['vnp_ResponseCode' => '99', 'vnp_Message' => 'No response'];
        } catch (\Exception $e) {
            Log::error('VNPAY Refund error: ' . $e->getMessage());
            return ['vnp_ResponseCode' => '99', 'vnp_Message' => $e->getMessage()];
        }
    }

    /**
     * Build hash data string from sorted params.
     */
    private function buildHashData(array $params): string
    {
        $hashData = '';
        $i = 0;
        foreach ($params as $key => $value) {
            if ($i === 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }
        return $hashData;
    }

    /**
     * Build query string from params.
     */
    private function buildQueryString(array $params): string
    {
        $query = '';
        foreach ($params as $key => $value) {
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        return $query;
    }

    /**
     * Get human-readable response code description.
     */
    public static function getResponseMessage(string $code): string
    {
        return match ($code) {
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Thẻ/Tài khoản chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch',
            '12' => 'Thẻ/Tài khoản bị khóa',
            '13' => 'Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)',
            '24' => 'Khách hàng hủy giao dịch',
            '51' => 'Tài khoản không đủ số dư để thực hiện giao dịch',
            '65' => 'Tài khoản đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Lỗi không xác định',
            default => 'Mã lỗi: ' . $code,
        };
    }
}
