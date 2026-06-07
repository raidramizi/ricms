<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ FORCE EMAIL TO LOWERCASE BEFORE VALIDATION
        $request->merge([
            'email' => strtolower($request->email),
        ]);

        $request->validate(
            [
                // NAME
                'name' => ['required', 'string', 'max:255'],

                // STAFF ID
                'staff_id' => [
                    'required',
                    'digits:6',
                    'unique:users,staff_id'
                ],

                // EMAIL
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users',
                    'ends_with:@unikl.edu.my',
                    'regex:/^[a-z0-9._%+-]+@unikl\.edu\.my$/'
                ],

                // PASSWORD
                'password' => [
                    'required',
                    'confirmed',
                    Rules\Password::defaults()
                ],
            ],
            [
                // STAFF ID ERRORS
                'staff_id.required' => 'Please enter your Staff ID.',
                'staff_id.digits' => 'Staff ID must be exactly 6 digits.',
                'staff_id.unique' => 'This Staff ID is already registered.',

                // EMAIL ERRORS
                'email.ends_with' => 'Only UniKL email (@unikl.edu.my) is allowed.',
                'email.regex' => 'Email must be in lowercase only (no uppercase letters allowed).',
            ]
        );

        // CREATE USER
        $user = User::create([
            'name' => $request->name,
            'staff_id' => $request->staff_id,
            'email' => $request->email, // already forced lowercase
            'password' => Hash::make($request->password),
        ]);

        // EVENT
        event(new Registered($user));

        // LOGIN
        Auth::login($user);

        // REDIRECT
        return redirect()->route('home');
    }
}
