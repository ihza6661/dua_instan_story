<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function processCheckout(Request $request): Order
    {
        $user = $request->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang belanja Anda kosong.');
        }

        return DB::transaction(function () use ($request, $user, $cart) {
            $validated = $request->validated();

            $invitationCategoryId = ProductCategory::where('slug', 'undangan-pernikahan')->firstOrFail()->id;

            $mainOrderItem = null;

            $photoPath = null;
            if ($request->hasFile('prewedding_photo')) {
                $photoPath = $request->file('prewedding_photo')->store('prewedding-photos', 'public');
            }

            $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->unit_price);
            $order = Order::create([
                'customer_id' => $user->id,
                'order_number' => 'INV-' . time() . '-' . Str::upper(Str::random(4)),
                'total_amount' => $cartTotal,
                'shipping_address' => $validated['shipping_address'],
                'order_status' => 'pending_payment',
            ]);

            foreach ($cart->items as $cartItem) {
                $orderItem = $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'sub_total' => $cartItem->quantity * $cartItem->unit_price,
                ]);

                if (!empty($cartItem->customization_details)) {
                    foreach ($cartItem->customization_details['options'] ?? [] as $option) {
                        $orderItem->meta()->create([
                            'meta_key' => $option['name'],
                            'meta_value' => $option['value'],
                            'meta_price' => $option['adjustment'],
                        ]);
                    }
                    foreach ($cartItem->customization_details['add_ons'] ?? [] as $addOn) {
                        $orderItem->meta()->create([
                            'meta_key' => $addOn['name'],
                            'meta_value' => 'Ditambahkan',
                            'meta_price' => $addOn['price'],
                        ]);
                    }
                }

                if ($cartItem->product->category_id === $invitationCategoryId && is_null($mainOrderItem)) {
                    $mainOrderItem = $orderItem;
                }
            }

            if ($mainOrderItem) {
                $mainOrderItem->customData()->create([
                    'form_data' => [
                        'bride_full_name' => $validated['bride_full_name'],
                        'groom_full_name' => $validated['groom_full_name'],
                        'bride_nickname' => $validated['bride_nickname'],
                        'groom_nickname' => $validated['groom_nickname'],
                        'bride_parents' => $validated['bride_parents'],
                        'groom_parents' => $validated['groom_parents'],
                        'akad_date' => $validated['akad_date'],
                        'akad_time' => $validated['akad_time'],
                        'akad_location' => $validated['akad_location'],
                        'reception_date' => $validated['reception_date'],
                        'reception_time' => $validated['reception_time'],
                        'reception_location' => $validated['reception_location'],
                        'gmaps_link' => $validated['gmaps_link'] ?? null,
                        'prewedding_photo_path' => $photoPath,
                    ]
                ]);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}
