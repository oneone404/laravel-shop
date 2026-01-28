<?php

namespace App\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
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
    
public function render($request, Throwable $exception)
{
    if ($exception instanceof ThrottleRequestsException) {
        return back()->with('error', 'VUI LÒNG THỬ LẠI SAU 1 PHÚT');
    }

    return parent::render($request, $exception);
}
}
