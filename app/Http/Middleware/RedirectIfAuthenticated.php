<?php

namespace App\Http\Middleware;

use App\Const\RoleConst;
use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('userCheck')) {
            if (session('userData')->role_id == RoleEnum::ADMIN) {
                return redirect(route('admin.dashboard'));
            } elseif (session('userData')->role_id == RoleEnum::STUDENT) {
                return redirect(route('student.student_dashboard'));
            }
        }

        return $next($request);
    }
}
