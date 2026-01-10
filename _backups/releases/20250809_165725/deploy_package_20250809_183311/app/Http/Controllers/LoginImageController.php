<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Utility;
use Illuminate\Support\Str;

class LoginImageController extends Controller
{
    public function index()
    {
        $currentImage = Utility::getValByName('login_right_image') ?: 'https://meishicadi.com/assets/images/auth/img-auth-3.svg';
        $imageUpdatedAt = Utility::getValByName('login_image_updated_at');
        
        return view('admin.login-image.index', compact('currentImage', 'imageUpdatedAt'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'login_image' => 'required|image|mimes:svg,png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            // Delete old image if it exists and is not the default
            $oldImage = Utility::getValByName('login_right_image');
            if ($oldImage && $oldImage !== 'https://meishicadi.com/assets/images/auth/img-auth-3.svg') {
                $oldPath = str_replace('/storage/', '', $oldImage);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store new image
            $file = $request->file('login_image');
            $fileName = 'login-right-image-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/login-images', $fileName, 'public');

            // Update settings
            Utility::setValByName('login_right_image', '/storage/' . $path);
            Utility::setValByName('login_image_updated_at', now()->toDateTimeString());

            // Clear cache
            Cache::forget('login_image_settings');

            return redirect()->back()->with('success', __('Login image updated successfully!'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Error updating login image: ') . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            // Delete current image if it exists and is not the default
            $currentImage = Utility::getValByName('login_right_image');
            if ($currentImage && $currentImage !== 'https://meishicadi.com/assets/images/auth/img-auth-3.svg') {
                $oldPath = str_replace('/storage/', '', $currentImage);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Reset to default
            Utility::setValByName('login_right_image', 'https://meishicadi.com/assets/images/auth/img-auth-3.svg');
            Utility::setValByName('login_image_updated_at', null);

            // Clear cache
            Cache::forget('login_image_settings');

            return redirect()->back()->with('success', __('Login image reset to default successfully!'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Error resetting login image: ') . $e->getMessage());
        }
    }

    public function getCurrentImage()
    {
        $image = Utility::getValByName('login_right_image') ?: 'https://meishicadi.com/assets/images/auth/img-auth-3.svg';
        $updatedAt = Utility::getValByName('login_image_updated_at');
        
        return response()->json([
            'image_url' => $image,
            'updated_at' => $updatedAt,
            'is_default' => $image === 'https://meishicadi.com/assets/images/auth/img-auth-3.svg'
        ]);
    }
} 