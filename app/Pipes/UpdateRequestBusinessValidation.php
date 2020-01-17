<?php

namespace App\Pipes;

use App\Http\Requests\UserUpdateRequest;
use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class UpdateRequestBusinessValidation
 * @package App\Pipes
 */
class UpdateRequestBusinessValidation
{
    /**
     * @param UserUpdateRequest $request
     * @param Closure           $next
     *
     * @return mixed
     * @throws ValidationException
     */
    public function handle(UserUpdateRequest $request, Closure $next)
    {
        $validator = Validator::make($request->all(), $request->businessRequestRules());
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
