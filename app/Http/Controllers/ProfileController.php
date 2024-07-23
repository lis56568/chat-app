<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function upload(Request $request)
    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 限制檔案類型和大小
//        ]);
//
//        // 取得上傳的檔案
//        $image = $request->file('image');
//
//        // 將檔案存儲到 storage/app/public 目錄下
//        $path = $image->store('public');
//
//        User::create([
//            'user_id' => auth()->id(),
//            'avatar_path' => $path,
//        ]);
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 限制檔案類型和大小
        ]);

        // 取得上傳的檔案
        $image = $request->file('image');

        // 將檔案存儲到 storage/app/public/avatars 目錄下
        $path = $image->store('public');

        // 獲取當前已認證的使用者
        $user = Auth::user();

        // 更新使用者的 avatar_path 欄位
        $user->update([
            'avatar' => $path,
        ]);

        return response()->json(['result' => 'success']);
    }
}
