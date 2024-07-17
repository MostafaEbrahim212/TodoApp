<?php

namespace App\Traits;

use Illuminate\Http\Request; // استيراد نوع الطلب الصحيح

use Illuminate\Support\Facades\Storage;

trait UploadsImage
{
    public function uploadImage($image, $folder)
    {
        $path = $image->store($folder, 'public');
        return $path;
    }

    public function updateImage(Request $request, $user, $field, $folder) // استخدام نوع الطلب الصحيح هنا
    {
        $validator = \Validator::make($request->all(), [
            $field => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($user->profile && $user->profile->$field) {
            Storage::disk('public')->delete($user->profile->$field);
        }

        if ($request->hasFile($field)) {
            if (!$user->profile) {
                $user->profile()->create([]);
            }
            $path = $this->uploadImage($request->file($field), $folder);
            $user->profile()->update([$field => $path]);
        }

        return response()->json(['message' => ucfirst($field) . ' uploaded successfully'], 200);
    }
}
