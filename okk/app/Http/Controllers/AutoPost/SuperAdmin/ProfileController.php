<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FileManager;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    use ResponseTrait;

    public function myProfile()
    {

        $data['title'] = 'Profile Settings';
        $data['user'] = User::where('id', Auth::id())->first();
        $data['activeProfile'] = 'active';

        return view('auto_posts.super_admin.setting.profile.index', $data);
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->id());

        // Base validation (always required fields)
        $rules = [
            'name'  => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'country' => 'nullable',
        ];

        // If user is trying to change password (any password field is filled)
        if ($request->filled('password') || $request->filled('password_confirmation')) {
            // For super admin panel, we don't force current password check
            $rules['password'] = 'required|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Image upload: store in FileManager and save the ID (same pattern as user/admin)
            if ($request->hasFile('image')) {
                $file = new FileManager();
                $uploaded = $file->upload('user', $request->image);
                $user->image = $uploaded->id;
            }

            // Only update profile fields when they are present (Basic Info form); avoid overwriting with null on password-only submit
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('mobile')) {
                $user->mobile = $request->mobile;
            }
            if ($request->has('country')) {
                $user->country = $request->country;
            }

            $user->save();

            DB::commit();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile update failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}