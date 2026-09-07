<?php

namespace App\Http\Controllers\AutoPost\Admin;

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

        return view('auto_posts.admin.profile.index', $data);
    }

    public function changePasswordUpdate(Request $request)
    {
        $user = User::find(auth()->id());

        // Base validation (always required fields)
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // If user is trying to change password (any password field is filled)
        if ($request->filled('password') || $request->filled('password_confirmation')) {
            // For admin panel, we don't force current password check
            $rules['password'] = 'required|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                $file = new FileManager();
                $uploaded = $file->upload('user', $request->image);
                $user->image = $uploaded->id;
            }

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->country = $request->country;
            $user->save();

            DB::commit();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }
}