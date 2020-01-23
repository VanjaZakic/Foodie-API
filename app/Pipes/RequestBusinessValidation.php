<?php

namespace App\Pipes;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class UpdateRequestBusinessValidation
 * @package App\Pipes
 */
class RequestBusinessValidation
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request->all(), $request->businessRequestRules());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
