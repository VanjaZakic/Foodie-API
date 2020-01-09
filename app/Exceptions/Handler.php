<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     *
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request    $request
     * @param \Exception $exception
     *
     * @return Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AdminUserForCompanyAlreadyExistsException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Admin user already exists.'], 406);
            }
        }

        if ($exception instanceof InvalidUserRoleForCompanyException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Invalid user role for that company.'], 406);
            }
        }

        return parent::render($request, $exception);
    }
}
