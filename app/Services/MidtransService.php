<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken($order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer->name,
                'email' => $order->customer->email,
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'name' => $item->product->name,
                ];
            }),
        ];

        return Snap::getSnapToken($params);
    }
}