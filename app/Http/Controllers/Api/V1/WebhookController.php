<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CheckoutService;
use App\Models\Order;
use App\Models\User;

class WebhookController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function midtrans(Request $request)
    {
        $payload = $request->all();

        $signatureKey = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.server_key'));

        if ($payload['signature_key'] != $signatureKey) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $order = Order::where('order_number', $payload['order_id'])->first();

        if ($order) {
            switch ($payload['transaction_status']) {
                case 'capture':
                case 'settlement':
                    if ($payload['fraud_status'] == 'accept') {
                        $user = User::find($order->customer_id);
                        $this->checkoutService->clearCart($user);
                        $order->order_status = 'processing';
                        $order->save();

                        $payment = $order->payment;
                        if ($payment) {
                            $payment->status = 'paid';
                            $payment->save();
                        }
                    }
                    break;
                case 'pending':
                    $order->order_status = 'pending';
                    $order->save();

                    $payment = $order->payment;
                    if ($payment) {
                        $payment->status = 'pending';
                        $payment->save();
                    }
                    break;
                case 'deny':
                    $order->order_status = 'failed';
                    $order->save();

                    $payment = $order->payment;
                    if ($payment) {
                        $payment->status = 'failed';
                        $payment->save();
                    }
                    break;
                case 'cancel':
                case 'expire':
                    $order->order_status = 'cancelled';
                    $order->save();

                    $payment = $order->payment;
                    if ($payment) {
                        $payment->status = 'cancelled';
                        $payment->save();
                    }
                    break;
                case 'refund':
                    $order->order_status = 'refunded';
                    $order->save();

                    $payment = $order->payment;
                    if ($payment) {
                        $payment->status = 'refunded';
                        $payment->save();
                    }
                    break;
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
