<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['access' => 'Silakan login terlebih dahulu.']);
        }

        $role = Auth::user()->role;

        if (in_array($role, ['staff', 'admin'])) {
            return $next($request);
        }

        // Redirect sesuai role
        $routeMap = [
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'customer' => 'dashboard',
        ];

        $redirectRoute = $routeMap[$role] ?? 'dashboard';

        return redirect()->route($redirectRoute)->withErrors(['access' => 'Anda tidak memiliki akses Staff.']);
    }
}
