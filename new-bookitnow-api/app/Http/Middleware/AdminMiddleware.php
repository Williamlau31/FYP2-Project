<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Admin privileges required.'
                ], 403);
            }
            
            return redirect()->route('dashboard')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}
