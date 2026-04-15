<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Not logged in
        if (!Auth::check()) {
            return redirect()->route('admin.login'); 
        }

        // Not admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}