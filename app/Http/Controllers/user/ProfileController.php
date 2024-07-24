<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Traits\UploadsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use UploadsImage;

    public function profile()
    {
        $user = Auth::user()->load('profile');
        if ($user->profile) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->profile->phone,
                'job' => $user->profile->job,
                'bio' => $user->profile->bio,
                'city' => $user->profile->city,
                'country' => $user->profile->country,
                'avatar' => 'storage/' . $user->profile->avatar,
                'cover' => 'storage/' . $user->profile->cover,
            ];
        } else {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',
                'job' => '',
                'bio' => '',
                'city' => '',
                'country' => '',
                'avatar' => 'storage/avatars/default.png',
                'cover' => 'storage/covers/default.jpg',
            ];
        }
        if (request()->ajax()) {
            return response()->json($userData, 200);
        }
        return view('profile')->with('userData', $userData);
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        return $this->updateImage($request, $user, 'avatar', 'avatars');
    }

    public function updateCover(Request $request)
    {
        $user = Auth::user();
        return $this->updateImage($request, $user, 'cover', 'covers');
    }

    public function UpdateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'string|max:255',
                'job' => 'string|max:255',
                'bio' => 'string|max:255',
                'city' => 'string|max:255',
                'country' => 'string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $user->profile()->update([
                'phone' => $request->phone,
                'job' => $request->job,
                'bio' => $request->bio,
                'city' => $request->city,
                'country' => $request->country,
            ]);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            return response()->json(['message' => 'Profile Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
