<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Auth\AuthenticationException;

use Throwable;


class Handler extends ExceptionHandler
{
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
    }
    
    //   protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // Force JSON response for API routes
    //     if ($request->expectsJson() || $request->is('api/*')) {
    //         return response()->json([
    //             'status' => 0,
    //             'error' => 'Unauthenticated. Please log in again.',
    //         ], 401);
    //     }

    //     return redirect()->guest(route('login'));
    // }


    // public function render($request, Throwable $exception)
    // {
    //     // Handle specific API error formats
    //     if ($request->is('api/*')) {
    //         if ($exception instanceof UnauthorizedHttpException) {
    //             return response()->json([
    //                 'status' => 0,
    //                 'error' => 'Unauthorized. Invalid or expired token.',
    //             ], 401);
    //         }

    //         // Add more custom API-specific error handlers if needed
    //     }

    //     return parent::render($request, $exception);
    // }
}
