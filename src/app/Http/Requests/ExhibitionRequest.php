<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'image' => 'required|mimes:jpeg,png',
            'category' => 'required',
            'condition' => 'required',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'image.required' => '商品画像をアップロードしてください。',
            'image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください。',
            'category.required' => 'カテゴリーを選択してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '商品価格を入力してください。',
            'price.integer' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で設定してください。',
        ];
    }
}
