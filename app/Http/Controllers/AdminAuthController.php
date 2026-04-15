<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show Admin Login Page
     */
    public function showLogin()
    {
        return view('login.admin-login');
    }

    /**
     * Handle Admin Login (username-based)
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Attempt login
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // Only allow admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Unauthorized access');
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome Admin!');
        }

        return back()->with('error', 'Invalid username or password');
    }

    /**
     * Logout Admin
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}