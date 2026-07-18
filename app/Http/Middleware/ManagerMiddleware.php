<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array(Auth::user()->role, ['manager', 'admin'])) {
            return $next($request);
        }
        return redirect()->route('dashboard')->withErrors(['access' => 'Anda tidak memiliki akses Manager.']);
    }
}
