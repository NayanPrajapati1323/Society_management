<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSocietyIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Super Admin is always allowed
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user belongs to a society and if it's active
        if ($user && $user->society_id) {
            if (!$user->society || !$user->society->is_active) {
                auth()->logout();
                return redirect()->route('society.login')->with('error', 'Your society is not active. Please ask Super Admin to activate it.');
            }
        }

        // Check user's own active status as well
        if ($user && !$user->is_active) {
            auth()->logout();
            return redirect()->route('society.login')->with('error', 'Your account is currently inactive. Please contact your society admin.');
        }

        return $next($request);
    }
}
