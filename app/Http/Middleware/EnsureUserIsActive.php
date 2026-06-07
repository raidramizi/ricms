<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status !== 'Active') {

            Auth::logout();

            return redirect('/login')->withErrors([
                'email' => 'Your account is inactive. Please contact Head.'
            ]);
        }

        return $next($request);
    }
}
