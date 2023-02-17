<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Exceptions\EhrException\EhrException;
use Tymon\JWTAuth\JWTAuth;
use App\Services\User\UserService;
use Symfony\Component\HttpFoundation\Session\Session;

class MobileApiToken
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

        $auth = App::make(JWTAuth::class);
        $token = $auth->getToken();

        if (!$token) {
            throw new EhrException(trans('auth.token_absent'), 401);
        }


        $service = new UserService;
        $service->authenticateToken($token->get());
        $_SESSION['token'] = $token->get();
        return $next($request);
    }
}
