<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (Auth::check() && (Auth::user()->role == $role || Auth::user()->role == 'admin')) {
            return $next($request);
        }

        return redirect()->route('welcome')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
    }
}