<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
