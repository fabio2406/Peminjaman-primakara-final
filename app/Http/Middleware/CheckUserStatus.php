<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (auth::check() && auth::user()->status !== 'active') {
            auth::logout();
            return redirect('/login')->withErrors(['Akunmu belum aktif dikarenakan ; '. auth::user()->keterangan ]);
        }

        return $next($request);
    }
}
