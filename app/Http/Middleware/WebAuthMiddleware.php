<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use App\Http\Middleware\VerifyCsrfToken as BaseVerifier;;

use App\AuthGenerator;
class WebAuthMiddleware extends BaseVerifier
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
        $user = $request->session()->get('user');
        // dd($_COOKIE);

        if(!is_null($user)){
            auth()->login(User::query()->find($user->id));
        }else{
            return redirect('/');
        }

        return $next($request);
    }
}
