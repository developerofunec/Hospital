<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;

class UserDetailController extends Controller
{
    
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();
        $detail = $user->detail ?? new UserDetail(['user_id' => $user->id]);

        
        if ($detail->profile_image && $detail->profile_image !== 'default.png') {
            Storage::disk('public')->delete($detail->profile_image);
        }

      
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $detail->profile_image = $path;
        $detail->save();

        return response()->json(['success' => true, 'image' => $path]);
    }

    
    public function deleteProfileImage()
    {
        $user = Auth::user();
        $detail = $user->detail;

        if ($detail && $detail->profile_image && $detail->profile_image !== 'default.png') {
            Storage::disk('public')->delete($detail->profile_image);
            $detail->profile_image = 'default.png';
            $detail->save();
        }

        return response()->json(['success' => true, 'message' => 'Şəkil silindi.']);
    }
}

