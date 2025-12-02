<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiXenditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $callbackToken = $request->header('x-callback-token');

        if (!$callbackToken || $callbackToken !== config('services.xendit.callback_token')) {
            return response()->json(['error' => 'Invalid callback token'], 401);
        }

        return $next($request);
    }
}
