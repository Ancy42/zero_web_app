<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttachCurrencyDataAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(file_exists(base_path('storage/installed'))) {
            $currencyId = generaleSetting()->currency_id;
            $request->merge([
                'currencyData' => currencyData($currencyId),
            ]);
        }
        return $next($request);
    }
}
