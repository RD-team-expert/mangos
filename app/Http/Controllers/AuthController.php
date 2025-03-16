<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Create a Blade view for the login form
    }

    // Handle the login request
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in with Sanctum session authentication
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            // If you want to issue a Sanctum token for API use (optional)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Redirect to inventory page with success message
            return redirect()->intended('/inventory')->with('success', 'Logged in successfully');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Log the user out
    public function logout(Request $request)
    {
        // Revoke all tokens for the user (optional for API use)
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }

        Auth::logout(); // Log the user out
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect('/login')->with('success', 'Logged out successfully');
    }

    // Optional: Get authenticated user (for API)
    public function user(Request $request)
    {
        return $request->user();
    }
}
