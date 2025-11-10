<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('You can not perform this action in demo mode'),
                ], Response::HTTP_FORBIDDEN);

            }

            return back()->with('demoMode', __('You can not perform this action in demo mode'));
        }

        return $next($request);
    }
}
