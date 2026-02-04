<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WholesalerAuth
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
          if (!Auth::guard('wholesaler')->check()) {
            return redirect()->route('wholesaler.login');
        }

        // Check if wholesaler is active
        if (Auth::guard('wholesaler')->user()->status !== 'active') {
            Auth::guard('wholesaler')->logout();
            return redirect()->route('wholesaler.login')->with('error', 'Your account is not active.');
        }

        return $next($request);
    }
}
