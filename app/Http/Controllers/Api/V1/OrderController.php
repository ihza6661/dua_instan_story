<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the user's orders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $orders = $this->orderService->getOrdersByUser($user);

        return OrderResource::collection($orders)->response();
    }

    /**
     * Display the specified order.
     *
     * @param Request $request
     * @param int $order
     * @return JsonResponse
     */
    public function show(Request $request, int $order): JsonResponse
    {
        $user = $request->user();
        $foundOrder = $this->orderService->getOrderByIdForUser($user, $order);

        if (!$foundOrder) {
            return response()->json(['message' => 'Order not found or does not belong to user.'], 404);
        }

        return (new OrderResource($foundOrder))->response();
    }
}
