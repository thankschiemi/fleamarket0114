<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required',
            'postal_code' => 'required|size:8',  // ハイフンあり8文字
            'address' => 'required',
            'building' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'お名前を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.size' => '郵便番号はハイフンを含む8文字で入力してください。',
            'address.required' => '住所を入力してください。',
            'building.required' => '建物名を入力してください。',
        ];
    }
}
