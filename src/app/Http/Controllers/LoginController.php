<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->is_first_login) {
                $user->is_first_login = false;
                $user->save();
                return redirect()->route('mypage.profile');
            }

            return redirect('/'); // 通常ログイン時
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ])->withInput($request->only('email'));
    }
}
