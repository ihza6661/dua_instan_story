<?php

namespace App\Http\Requests\Api\V1\Admin\Attribute;

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
            'name' => ['required', 'string', 'max:100', 'unique:attributes,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama atribut wajib diisi.',
            'name.unique' => 'Nama atribut ini sudah ada.',
        ];
    }
}
