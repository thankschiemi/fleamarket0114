<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'required',
            'delivery_address' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法は必須です。コンビニ払いまたはカード払いのいずれかを選択してください。',
            'delivery_address.required' => '配送先を選択してください（ご登録住所からお届けします）。',
        ];
    }
}
