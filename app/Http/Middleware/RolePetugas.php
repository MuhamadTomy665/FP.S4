<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePetugas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'petugas') {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Akses tidak diizinkan.');
    }
}
