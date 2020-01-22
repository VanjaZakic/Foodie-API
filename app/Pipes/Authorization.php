<?php

namespace App\Pipes;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class UpdateAuthorization
 * @package App\Pipes
 */
class Authorization
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
        $authorized = $request->isAuthorized();

        if (!$authorized) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
