<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Vite;

class AddContentSecurityPolicyHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($quest)

            Vite::useCspNonce();

        Log::alert(Vite::cspNonce());
        // return $next($request);

        return $next($request)->withHeaders([
            'Content-Security-Policy' => "script-src 'nonce-" . Vite::cspNonce() . "'",
        ]);
    }
}
