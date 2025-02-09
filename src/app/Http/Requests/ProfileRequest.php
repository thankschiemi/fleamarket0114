<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'nullable|mimes:jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
        ];
    }
}
