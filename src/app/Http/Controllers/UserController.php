<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($request->hasFile('image')) {
            // 既存の画像があれば削除
            if ($user->profile_image_path && file_exists(public_path($user->profile_image_path))) {
                unlink(public_path($user->profile_image_path));
            }

            // 新しい画像を `storage/app/public/profile_images/` に保存
            $imagePath = $request->file('image')->store('profile_images', 'public');

            // 🔥 `public/storage/` にアクセスできるようにパスを変更
            $user->profile_image_path = $imagePath;
        }

        // ユーザー情報の更新
        $user->name = $request->input('username');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building_name = $request->input('building');

        // 初回ログイン時のフラグを解除
        if ($user->is_first_login) {
            $user->is_first_login = false;
        }

        $user->save();

        return redirect()->route('mypage')->with('status', 'プロフィールを更新しました！');
    }




    public function editProfile()
    {
        /** @var \App\Models\User $user */
        // 現在の認証ユーザーを取得
        $user = auth()->user();

        // ビューにユーザー情報を渡して表示
        return view('auth.update-profile-information', compact('user'));
    }
}
