<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AlwaysAcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json', true);
        $response = $next($request);
        return $response;
    }
}
