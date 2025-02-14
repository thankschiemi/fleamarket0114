<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $profileRequest = app(\App\Http\Requests\ProfileRequest::class);
        $profileRequest->validateResolved();

        $addressRequest = app(\App\Http\Requests\AddressRequest::class);
        $addressData = $addressRequest->validated();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($request->hasFile('image')) {
            if ($user->profile_image_path && file_exists(public_path($user->profile_image_path))) {
                unlink(public_path($user->profile_image_path));
            }

            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->profile_image_path = $imagePath;
        }

        $user->name = $addressData['username'];
        $user->postal_code = $addressData['postal_code'];
        $user->address = $addressData['address'];
        $user->building_name = $addressData['building'];

        if ($user->is_first_login) {
            $user->is_first_login = false;
        }
        $user->save();

        return redirect('/')->with('status', 'プロフィールを更新しました！');
    }

    public function editProfile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return view('auth.update-profile-information', compact('user'));
    }
}
