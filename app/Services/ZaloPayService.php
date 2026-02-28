<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ZaloPayService
{
    protected $config;

    public function __construct()
    {
        $this->config = [
            'app_id' => env('ZALOPAY_APP_ID', '2553'),
            'key1'   => env('ZALOPAY_KEY1', 'PcY4iZnrpoBWStatusS6vp3G9JS64m4drY5'),
            'key2'   => env('ZALOPAY_KEY2', 'kLvgI8oH96ubS7Anf99797AAt0Ym3F3q'),
            'endpoint' => env('ZALOPAY_ENDPOINT', 'https://sb-openapi.zalopay.vn/v2/create'),
        ];
    }

    public function createPayment($order)
    {
        $embeddata = json_encode(['redirecturl' => route('zalopay.return')]);
        $items = json_encode([]); // Can add item details if needed
        $transID = rand(0, 1000000); // Random trans id for testing
        $appTransID = date("ymd") . "_" . $transID; 
        
        $orderData = [
            'app_id' => $this->config['app_id'],
            'app_time' => round(microtime(true) * 1000),
            'app_trans_id' => $appTransID,
            'app_user' => $order->name ?: 'Guest',
            'amount' => (int) $order->final_total,
            'item' => $items,
            'embed_data' => $embeddata,
            'description' => "Thanh toan don hang #" . $order->id,
            'bank_code' => "zalopayapp",
        ];

        // appid|apptransid|appuser|amount|apptime|embeddata|item
        $data = $orderData['app_id'] . "|" . $orderData['app_trans_id'] . "|" . $orderData['app_user'] . "|" . $orderData['amount']
            . "|" . $orderData['app_time'] . "|" . $orderData['embed_data'] . "|" . $orderData['item'];
        
        $orderData['mac'] = hash_hmac('sha256', $data, $this->config['key1']);

        // In a real scenario, we'd use Guzzle to POST to $this->config['endpoint']
        // Since user doesn't have setup, we will perform a MOCK REDIRECT
        
        Log::info('ZaloPay Payment Created (Mock)', ['app_trans_id' => $appTransID, 'order_id' => $order->id]);

        // Simulating the URL that ZaloPay would return
        // For testing, we just redirect to our own "Mock ZaloPay" page or directly to return with success
        return route('zalopay.mock_gateway', [
            'app_trans_id' => $appTransID,
            'order_id' => $order->id,
            'amount' => $order->final_total
        ]);
    }

    public function verifyCallback($data)
    {
        $key2 = $this->config['key2'];
        $mac = hash_hmac('sha256', $data['data'], $key2);

        return $mac === $data['mac'];
    }
}
