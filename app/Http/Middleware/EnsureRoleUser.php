<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleId): Response
    {
        if (!session('userCheck')) {
            // Hapus session lama
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect ke halaman login
            return redirect()->route('login')->withErrors([
                'session-expired' => 'Sesi Anda sudah habis, silakan login kembali.',
            ]);
        }

        if (session('userCheck') && session('userData')->role_id == $roleId) {
            return $next($request);
        }

        return redirect('/login');
    }
}
