<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please log in to access the admin panel.')
                ->with('intended_url', $request->fullUrl());
        }

        $staff = Auth::user();

        // Check if staff member is active
        if (! $staff->isActive()) {
            Auth::logout();

            return redirect()->route('admin.login')->withErrors([
                'username' => 'Your account has been deactivated. Please contact an administrator.',
            ]);
        }

        // Check if staff has admin privileges (optional - for future role-based access)
        // Temporarily disabled for UI testing
        /*
        $adminRoles = ['system_admin', 'administrator', 'admin'];
        if (!in_array($staff->staffType->name, $adminRoles)) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'username' => 'You do not have permission to access the admin panel.',
            ]);
        }
        */

        return $next($request);
    }
}
