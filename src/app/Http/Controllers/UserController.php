<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        // 入力データのバリデーション
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        // 現在の認証ユーザーを取得
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($request->hasFile('image')) {
            // 古い画像を削除（必要に応じて）
            if ($user->profile_image_path) {
                Storage::delete('public/' . $user->profile_image_path);
            }

            // 新しい画像を保存
            $imagePath = $request->file('image')->store('profile_images', 'public');

            // ユーザーのプロフィール画像パスを更新
            $user->update(['profile_image_path' => $imagePath]);
        }


        // ユーザー情報を更新
        $user->update([
            'name' => $request->input('username'),
            'postal_code' => $request->input('postal_code'),
            'address' => $request->input('address'),
            'building_name' => $request->input('building'),
        ]);

        // フォーム送信後に http://localhost/ にリダイレクト
        return redirect('/');
    }

    public function editProfile()
    {
        // 現在の認証ユーザーを取得
        $user = auth()->user();

        // ビューにユーザー情報を渡して表示
        return view('auth.update-profile-information', compact('user'));
    }
}
