<?php

namespace App\Pipes;

use App\Http\Requests\UserUpdateRequest;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateAuthorization
{
    public function handle(UserUpdateRequest $request, Closure $next)
    {
        $authorized = $request->user()->can('update', $request->user);

        if (!$authorized) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
