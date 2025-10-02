<?php

namespace App\Services;

use App\Models\InvitationDetail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


use Illuminate\Support\Facades\Auth;

class CheckoutService
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function processCheckout(Request $request): Order
    {
        $user = $request->user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang belanja Anda kosong.');
        }

        return DB::transaction(function () use ($request, $user, $cart) {
            $validated = $request->validated();

            $photoPath = null;
            if ($request->hasFile('prewedding_photo')) {
                $photoPath = $request->file('prewedding_photo')->store('prewedding-photos', 'public');
            }

            $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->unit_price);

            $shippingCost = $validated['shipping_cost'] ?? 0;
            $shippingService = $validated['shipping_service'] ?? null;
            $courier = $validated['courier'] ?? null;

            // 1. Create the Order
            $order = Order::create([
                'customer_id' => $user->id,
                'order_number' => 'INV-' . time() . '-' . Str::upper(Str::random(4)),
                'total_amount' => $cartTotal + $shippingCost,
                'shipping_address' => $validated['shipping_address'],
                'order_status' => 'pending_payment',
                'shipping_cost' => $shippingCost,
                'shipping_service' => $shippingService,
                'courier' => $courier,
            ]);

            // 2. Create the Invitation Detail
            $order->invitationDetail()->create([
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
            ]);

            // 3. Create Order Items from Cart
            foreach ($cart->items as $cartItem) {
                $orderItem = $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'sub_total' => $cartItem->quantity * $cartItem->unit_price,
                ]);

                // Copy customization details if any
                if (!empty($cartItem->customization_details)) {
                    foreach ($cartItem->customization_details['options'] ?? [] as $option) {
                        $orderItem->meta()->create([
                            'meta_key' => $option['name'],
                            'meta_value' => $option['value'],
                            'meta_price' => 0,
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
            }

            // 4. Clear the cart
            $cart->items()->delete();

            return $order;
        });
    }

    public function calculateShippingCost(array $data)
    {
        $user = Auth::user();
        $originCityId = $user->city_id;

        if (!$originCityId) {
            throw new \Exception('Alamat pengguna tidak lengkap. Mohon perbarui profil Anda.');
        }

        $response = $this->rajaOngkirService->getCost(
            $originCityId,
            $data['destination'],
            $data['weight'],
            $data['courier']
        );

        if (isset($response['rajaongkir']['status']['code']) && $response['rajaongkir']['status']['code'] == 200) {
            return $response['rajaongkir'];
        } else {
            throw new \Exception('Gagal menghitung biaya pengiriman. Silakan coba lagi.');
        }
    }
}
