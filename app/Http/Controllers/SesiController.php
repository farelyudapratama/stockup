<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class SesiController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('login');
    }

    public function login(Request $request)
    {
        try {
            $key = Str::lower($request->input('email')) . '|' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                throw ValidationException::withMessages([
                    'email' => ['Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.'],
                ]);
            }

            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
                'password.' => 'Password minimal 8 karakter'
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                RateLimiter::clear($key);

                return $this->redirectBasedOnRole();
            }

            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => ['Email atau password salah'],
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $e->getMessage()]);
        }
    }

    protected function redirectBasedOnRole()
    {
        $roleRedirects = [
            'admin' => '/admin',
            'stocker' => '/stocker',
            'purchaser' => '/purchaser',
            'seller' => '/seller',
        ];

        $userRole = Auth::user()->role;
        return redirect($roleRedirects[$userRole] ?? '/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('')->with('status', 'Anda telah berhasil logout');
    }
}