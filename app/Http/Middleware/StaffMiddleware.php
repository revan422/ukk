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
        if (Auth::check() && in_array(Auth::user()->role, ['staff', 'admin'])) {
            return $next($request);
        }
        return redirect()->route('dashboard')->withErrors(['access' => 'Anda tidak memiliki akses Staff.']);
    }
}
