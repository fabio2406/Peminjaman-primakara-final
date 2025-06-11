<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (auth::user()->role !== $role) {
            return redirect('/' . auth::user()->role . '/dashboard')->withErrors(['Anda tidak mempunyai akses ke URL tersebut.']);
        }
        

        return $next($request);
    }
}
