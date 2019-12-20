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
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check()) {
            if ($request->user()->role == $role) {
                return $next($request);
            }

            return response()->json([
                'Unauthorized'
            ]);
        }

        return response()->json([
            'Pease login'
        ]);
    }
}
