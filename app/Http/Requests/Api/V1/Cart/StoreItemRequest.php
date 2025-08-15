<?php

namespace App\Http\Requests\Api\V1\Cart;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variant = ProductVariant::with('product')->find($this->input('variant_id'));

        return [
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($variant) {
                if ($variant && $value < $variant->product->min_order_quantity) {
                    $fail("Jumlah minimal pesanan untuk produk ini adalah {$variant->product->min_order_quantity}.");
                }
            }],
            'add_ons' => ['nullable', 'array'],
            'add_ons.*' => ['integer', 'exists:add_ons,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.required' => 'Varian produk wajib dipilih.',
            'variant_id.exists' => 'Varian produk yang dipilih tidak ditemukan.',
            'quantity.required' => 'Jumlah pesanan wajib diisi.',
            'quantity.min' => 'Jumlah pesanan minimal 1 buah.',
        ];
    }
}
