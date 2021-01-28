<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $setting = $user->setting;
        return view('client.pengaturan', compact('user', 'setting'));
    }

    public function store(Request $request)
    {
        if (!empty($request->avatar)) {
            $fileName = (string) Str::uuid() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move('avatars', $fileName);
            User::where('username', $request->username)->update([
                'avatar' => 'avatars/'.$fileName,
            ]);
        }

        Setting::find(Auth::id())->update([
            'confidence' => $request->confidence,
            'support'    => $request->support,
        ]);

        return redirect()->route('pengaturan')->with('status', 'Data updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return redirect()->route('pengaturan')->with('error', 'Your password does not match.');
        }

        User::find(Auth::id())->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('pengaturan')->with('status', 'Password updated successfully.');
    }
}
