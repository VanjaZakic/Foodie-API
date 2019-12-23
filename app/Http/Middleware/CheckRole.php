<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckRole
 * @package App\Http\Middleware
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @param array   $roles
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if (Auth::user()->role == trim($role)) {
                return $next($request);
            }
        }

        return response()->json([
            'Unauthorized'
        ]);
    }
}
