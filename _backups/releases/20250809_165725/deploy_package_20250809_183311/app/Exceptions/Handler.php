<?php

namespace App\Exceptions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Traits\ApiResponser;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error(['message' => 'Invalid token.'], 'fail', 401, 9);
            }
        });

        // Custom error page rendering
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return null; // Let Laravel handle API errors normally
            }

            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
            
            // Use custom error pages for web requests
            if (in_array($statusCode, [404, 403, 500])) {
                return response()->view("errors.{$statusCode}", ['exception' => $e], $statusCode);
            }
            
            // Use generic error page for other errors
            return response()->view('errors.generic', ['exception' => $e], $statusCode);
        });
    }
}
