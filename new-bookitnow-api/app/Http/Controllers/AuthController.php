<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException; // Make sure this is imported

class AuthController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // If the request expects JSON (e.g., from an API client), return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => route('dashboard')
                ]);
            }
            // Otherwise, redirect for web requests
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // If login attempt fails
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials. Please try again.'
            ], 401);
        }
        // For web requests, redirect back with error
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Laravel's default failed authentication message
        ])->redirectTo(route('login')); // Or just redirect()->back()->withErrors(...)
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the application's registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // If user is already logged in, redirect them away from registration
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register'); // Assuming your registration view is at resources/views/auth/register.blade.php
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        // For web forms, 'role' should usually be implicitly set (e.g., to 'user' or 'patient')
        // unless you have an admin form where an admin assigns roles.
        // I've modified the validation to remove 'role' if you're registering a standard user.
        // If your frontend form *does* send a 'role' field, keep it here.
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' checks for password_confirmation field
            // 'role' => ['required', 'in:admin,user'], // Uncomment this line if your form explicitly sends 'role'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'patient', // Assign a default role for new registrations (e.g., 'patient' or 'user')
            ]);

            Auth::login($user); // Log the user in after successful registration

            // Determine response based on whether the request expects JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User registered successfully!',
                    'user' => $user->only('id', 'name', 'email', 'role'), // Return specific user data
                    'redirect' => route('dashboard') // Include redirect for frontend handling
                ], 201); // 201 Created status
            }

            // For web requests, redirect to dashboard after successful registration
            return redirect()->route('dashboard')->with('success', 'Registration successful!');

        } catch (ValidationException $e) {
            // If validation fails
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
            // For web requests, redirect back with errors
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}