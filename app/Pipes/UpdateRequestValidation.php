<?php

namespace App\Pipes;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateRequestValidation
{
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), $request->requestRules());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
