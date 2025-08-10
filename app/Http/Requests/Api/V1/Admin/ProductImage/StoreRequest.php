<?php

namespace App\Http\Requests\Api\V1\Admin\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'alt_texts' => ['nullable', 'array'],
            'alt_texts.*' => ['nullable', 'string', 'max:255'],
            'is_featured_index' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Minimal satu file gambar wajib diunggah.',
            'images.*.image' => 'Setiap file yang diunggah harus berupa gambar.',
            'images.*.max' => 'Ukuran setiap gambar tidak boleh lebih dari 5MB.',
            'alt_texts.array' => 'Alt text harus dalam format array.',
            'is_featured_index.integer' => 'Index gambar utama harus berupa angka.',
        ];
    }
}
