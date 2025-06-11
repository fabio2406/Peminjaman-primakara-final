<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect berdasarkan role user
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'peminjam') {
                return redirect('/peminjam/dashboard');
            } elseif ($user->role === 'sdm') {
                return redirect('/sdm/dashboard');
            } elseif ($user->role === 'dala') {
                return redirect('/dala/dashboard');
            } elseif ($user->role === 'warek') {
                return redirect('/warek/dashboard');
            }
        }

        return $next($request);
    }
}
