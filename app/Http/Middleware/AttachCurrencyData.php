<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttachCurrencyData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currencyId = $request->currency_id ?? generaleSetting()->currency_id;

        $request->merge([
            'currencyData' => currencyData($currencyId),
        ]);

        return $next($request);
    }
}
