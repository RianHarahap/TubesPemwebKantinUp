<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // INI YANG TADI HILANG

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah sudah login
        if (!Auth::check()) {
            return redirect('/');
        }

        // 2. Cek apakah role user ada di dalam daftar yang diizinkan
        $user = Auth::user();
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika login tapi role salah, lempar ke halaman terlarang
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}