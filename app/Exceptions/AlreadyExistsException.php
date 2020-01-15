<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class AdminUserForCompanyAlreadyExistsException
 * @package App\Exceptions
 */
class AlreadyExistsException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json(['error' => 'Already exists.'], 406);
    }
}
