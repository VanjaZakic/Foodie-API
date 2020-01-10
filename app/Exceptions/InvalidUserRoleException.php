<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class InvalidUserRoleForCompanyException
 * @package App\Exceptions
 */
class InvalidUserRoleException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json(['error' => 'Invalid user role.'], 406);
    }
}
