<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsEnableMiddleware
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

        return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, PUT, GET, POST, DELETE, OPTIONS')
        ->header('Access-Control-Request-Headers', 'Content-Type,x-requested-with, authorization, Accept, Origin, Referer, User-Agent, text/plain, application/json, Access-Control-Request-Headers, Access-Control-Request-Method, Access-Control-Allow-Origin')
        ;
    }
}
