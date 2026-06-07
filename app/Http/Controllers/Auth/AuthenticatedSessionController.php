<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // EMAIL NOT FOUND
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email does not exist in our system.'
            ]);
        }

        // PASSWORD WRONG
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Incorrect password. Please try again.'
            ]);
        }

        // ❌ INACTIVE USER CHECK
        if ($user->status !== 'Active') {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact Head.'
            ]);
        }

        // LOGIN USER
        Auth::login($user);
        $request->session()->regenerate();

        //  ROLE REDIRECTS
        if ($user->role === 'R&I Staff') {
            return redirect()->route('admin.submissions.index');
        }

        if ($user->role === 'Head') {
            return redirect()->route('head.dashboardreview');
        }

        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
