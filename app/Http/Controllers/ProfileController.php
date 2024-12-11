<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    //
    public function index()
    {
        $users = User::all();

        return view('profile.profile', compact('users'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($user->role !== 'admin' && $request->has('role')) {
            return redirect()->back()->withErrors(['role' => 'Only admin can modify roles.']);
        }

        $adminCount = User::where('role', 'admin')->count();
        if ($user->role === 'admin' && $adminCount === 1 && $request->has('role') && $request->role !== 'admin') {
            return redirect()->back()->withErrors(['role' => 'Cannot change role of the only admin.']);
        }

        $user->DB::update($request->only('name', 'email', 'role'));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->DB::update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
