<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CartService
{
    public function getOrCreateCart(Request $request): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = $request->header('X-Session-ID');
        if ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)->first();
            if ($cart) {
                return $cart;
            }
        }

        return Cart::create([
            'session_id' => Str::uuid()->toString(),
        ]);
    }

    public function addItem(Request $request): Cart
    {
        $validated = $request->validated();
        $product = Product::with(['options.attributeValue.attribute', 'addOns'])->findOrFail($validated['product_id']);
        $cart = $this->getOrCreateCart($request);

        $unitPrice = $product->base_price;
        $customizationDetails = ['options' => [], 'add_ons' => []];

        if (!empty($validated['options'])) {
            foreach ($validated['options'] as $optionId) {
                $option = $product->options->find($optionId);
                if (!$option) throw new InvalidArgumentException("Opsi dengan ID {$optionId} tidak valid untuk produk ini.");
                $unitPrice += $option->price_adjustment;
                $customizationDetails['options'][] = [
                    'name' => $option->attributeValue->attribute->name,
                    'value' => $option->attributeValue->value,
                    'adjustment' => $option->price_adjustment,
                ];
            }
        }

        if (!empty($validated['add_ons'])) {
            foreach ($validated['add_ons'] as $addOnId) {
                $addOn = $product->addOns->find($addOnId);
                if (!$addOn) throw new InvalidArgumentException("Item tambahan dengan ID {$addOnId} tidak valid untuk produk ini.");
                $unitPrice += $addOn->price;
                $customizationDetails['add_ons'][] = [
                    'name' => $addOn->name,
                    'price' => $addOn->price,
                ];
            }
        }

        $existingItem = $cart->items()->where('product_id', $product->id)
            ->whereJsonContains('customization_details', $customizationDetails)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $validated['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $unitPrice,
                'customization_details' => $customizationDetails,
            ]);
        }

        $cart->load('items.product.featuredImage');
        return $cart;
    }

    public function getCartContents(Request $request): Cart
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.product.featuredImage');
        return $cart;
    }

    public function updateItemQuantity(int $cartItemId, int $quantity): Cart
    {
        $item = CartItem::findOrFail($cartItemId);
        $item->update(['quantity' => $quantity]);

        return $this->getCartContents(request());
    }

    public function removeItem(int $cartItemId): Cart
    {
        $item = CartItem::findOrFail($cartItemId);
        $cart = $item->cart;
        $item->delete();

        $cart->load('items.product.featuredImage');
        return $cart;
    }

    public function clearCart(Request $request): Cart
    {
        $cart = $this->getOrCreateCart($request);

        $cart->items()->delete();

        $cart->load('items');

        return $cart;
    }
}
