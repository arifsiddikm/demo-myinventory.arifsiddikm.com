<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
