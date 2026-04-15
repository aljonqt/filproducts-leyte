<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StaffAuthController extends Controller
{
    /**
     * SHOW LOGIN PAGE
     */
    public function showLogin()
    {
        return view('staff.login');
    }

    /**
     * HANDLE LOGIN
     */
    public function login(Request $request)
    {
        // VALIDATION
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // ATTEMPT LOGIN USING USERNAME
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // OPTIONAL: CHECK IF STAFF ONLY
            if ($user->role !== 'staff' && $user->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Unauthorized access.');
            }

            // SUCCESS LOGIN
            return redirect()->route('staff.home')
                ->with('success', 'Welcome ' . $user->firstname . '!');
        }

        // FAILED LOGIN
        return back()->with('error', 'Invalid username or password');
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')
            ->with('success', 'Logged out successfully');
    }
}