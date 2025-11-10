<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // 413: Payload Too Large
        if ($exception instanceof PostTooLargeException) {
            return back()->withErrors(['error' => 'File size is too large.'], 413);
        }

        // 403: Access Denied handler
        // if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {

        //     $user = $request->user();

        //     if ($user->hasRole('shop') && !$user->hasRole('root')) {
        //         return to_route('shop.dashboard.index');
        //     }

        //     return to_route('admin.dashboard.index');
        // }



        if ($exception instanceof ThrottleRequestsException) {
            $retryAfter = (int) ($exception->getHeaders()['Retry-After'] ?? 60);
            $maxAttempts = (int) ($exception->getHeaders()['X-RateLimit-Limit'] ?? 5);
            $remaining   = (int) ($exception->getHeaders()['X-RateLimit-Remaining'] ?? 0);

            if ($retryAfter >= 60) {
                $minutes = floor($retryAfter / 60);
                $seconds = $retryAfter % 60;

                if ($seconds > 0) {
                    $timeText = "{$minutes} min {$seconds} sec";
                } else {
                    $timeText = "{$minutes} minute";
                }
            } else {
                $timeText = "{$retryAfter} second";
            }

            // JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Too many attempts. Please try again after {$timeText}.",
                    'retry_after' => $retryAfter,
                    'max_attempts' => $maxAttempts,
                    'remaining_attempts' => $remaining,
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            // Web response
            return back()->withErrors([
                'error' => "Too many attempts. Please try again after {$timeText}."
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }


        return parent::render($request, $exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
