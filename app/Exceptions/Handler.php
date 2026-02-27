<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Validation Error (422)
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'Validation error',
                422,
                $e->errors()
            );
        }

        // Unauthenticated (401 - Sanctum)
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error(
                'Unauthorized',
                401
            );
        }

        // Forbidden (403 - Policy)
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return ApiResponse::error(
                'Forbidden',
                403
            );
        }

        // HTTP Exception (404, 405, etc)
        if ($e instanceof HttpExceptionInterface) {
            return ApiResponse::error(
                $e->getMessage() ?: 'HTTP Error',
                $e->getStatusCode()
            );
        }

        // Fallback 500
        return ApiResponse::error(
            app()->environment('production') 
                ? 'Internal Server Error' 
                : $e->getMessage(),
            500
        );
    }
}
