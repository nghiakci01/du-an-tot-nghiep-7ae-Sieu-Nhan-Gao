<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ZaloPayService;
use Illuminate\Http\Request;

class ZaloPayController extends Controller
{
    protected $zaloPayService;

    public function __construct(ZaloPayService $zaloPayService)
    {
        $this->zaloPayService = $zaloPayService;
    }

    public function pay(Order $order)
    {
        $paymentUrl = $this->zaloPayService->createPayment($order);

        return redirect($paymentUrl);
    }

    public function mockGateway(Request $request)
    {
        $orderId = $request->order_id;
        $appTransId = $request->app_trans_id;
        $amount = $request->amount;

        return view('frontend.zalopay.mock', compact('orderId', 'appTransId', 'amount'));
    }

    public function processMock(Request $request)
    {
        $status = $request->status; // 1: success, 2: fail
        $order = Order::findOrFail($request->order_id);

        if ($status == 1) {
            // Success: update order status (In real life, this happens via callback)
            $order->update([
                'status' => 'processing', // or 'paid'
                'payment_status' => 'paid',
            ]);

            return redirect()->route('checkout.success', $order->id)->with('success', 'Thanh toán ZaloPay thành công!');
        } else {
            // Fail/Cancel: redirect back to cart as requested
            return redirect()->route('cart.index')->with('error', 'Thanh toán ZaloPay đã bị hủy hoặc thất bại. Vui lòng thử lại.');
        }
    }

    public function callback(Request $request)
    {
        $result = [];
        try {
            $dataStr = $request->input('data');
            $mac = $request->input('mac');

            if ($this->zaloPayService->verifyCallback(['data' => $dataStr, 'mac' => $mac])) {
                $dataJson = json_decode($dataStr, true);

                // Extract order from app_trans_id or embed_data
                // For mock, we've already handled status in processMock,
                // but we implement this for architectural completeness.

                $result['return_code'] = 1;
                $result['return_message'] = 'success';
            } else {
                $result['return_code'] = 0;
                $result['return_message'] = 'mac calculation error';
            }
        } catch (\Exception $e) {
            $result['return_code'] = 0;
            $result['return_message'] = $e->getMessage();
        }

        return response()->json($result);
    }

    public function return(Request $request)
    {
        // This is where ZaloPay redirects the user back
        // For mock, handled via processMock
        return redirect()->route('home');
    }
}
