<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Checkout\StoreRequest;
use App\Http\Resources\OrderResource;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(StoreRequest $request, CheckoutService $checkoutService): JsonResponse
    {
        try {
            $order = $checkoutService->processCheckout($request);
            $order->load('items.product', 'invitationDetail');

            return response()->json([
                'message' => 'Pesanan Anda berhasil dibuat dan menunggu pembayaran.',
                'data' => new OrderResource($order),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function calculateShippingCost(Request $request, CheckoutService $checkoutService): JsonResponse
    {
        try {
            $cost = $checkoutService->calculateShippingCost($request->all());
            return response()->json($cost);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
