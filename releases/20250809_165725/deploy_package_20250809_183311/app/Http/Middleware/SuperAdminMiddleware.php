<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->type !== 'super admin') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Super admin access required.'], 403);
            }
            
            return redirect()->route('home')->with('error', __('Access denied. Super admin privileges required.'));
        }

        return $next($request);
    }
} 