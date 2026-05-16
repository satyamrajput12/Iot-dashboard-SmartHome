<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * AuthController
 * Handles user registration, login, and logout.
 */
class AuthController extends Controller
{
    // =============================================
    // REGISTRATION
    // =============================================

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration form submission.
     */
    public function register(Request $request)
    {
        // Validate the incoming form data
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Create the new user (default role is 'user')
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Automatically log them in after registration
        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Welcome, ' . $user->name . '! Your account has been created.');
    }

    // =============================================
    // LOGIN
    // =============================================

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate(); // Prevent session fixation attacks

            $user = Auth::user();

            // Check if account is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Contact the administrator.',
                ]);
            }

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin ' . $user->name . '!');
            }

            return redirect()->route('user.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // =============================================
    // LOGOUT
    // =============================================

    /**
     * Log the user out and redirect to login page.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
