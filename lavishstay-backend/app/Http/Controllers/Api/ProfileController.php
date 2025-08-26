<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Get current user profile
     */
    public function me()
    {
        $user = auth()->user()->load('roles');
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserResource($user)
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => new UserResource($user->load('roles'))
            ]
        ]);
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete old avatar if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('profile-photos', 'public');
        
        $user->update(['profile_photo_path' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'user' => new UserResource($user->load('roles'))
            ]
        ]);
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = auth()->user();

        // Delete avatar file if exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->update(['profile_photo_path' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar deleted successfully',
            'data' => [
                'user' => new UserResource($user->load('roles'))
            ]
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
            'new_password_confirmation' => 'required|string|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['Current password is incorrect']
                ]
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
}
