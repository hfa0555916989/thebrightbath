<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->canAccessAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح بالوصول'], 403);
            }
            
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول إلى لوحة التحكم');
        }

        return $next($request);
    }
}






