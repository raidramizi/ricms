<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Must be logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 2. Check account status FIRST (important)
        if ($user->status !== 'Active') {

            Auth::logout();

            return redirect('/login')->withErrors([
                'email' => 'Your account is inactive. Please contact Head.'
            ]);
        }

        // 3. Check role
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
