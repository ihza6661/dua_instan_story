<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'required_with:province_name,city_name,postal_code', 'string', 'max:255'],
            'province_name' => ['nullable', 'required_with:address,city_name,postal_code', 'string', 'max:255'],
            'city_name' => ['nullable', 'required_with:address,province_name,postal_code', 'string', 'max:255'],
            'postal_code' => ['nullable', 'required_with:address,province_name,city_name', 'string', 'max:10'],
        ];
    }
}
