<?php

namespace App\Http\Middleware;

use App\Exceptions\ControllableException;
use App\Utils\R;
use Closure;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws ControllableException
     */
    public function handle($request, Closure $next)
    {
        $token = $request->get('api_token');
        if ( !strlen($token) ){
            throw new ControllableException(
                R::AUTH_NO_TOKEN,
                null,
                401
            );
        }
        $authorized = \Auth::attempt(['api_token' => $token]);
        if ( !$authorized ){
            throw new ControllableException(
                R::AUTH_WRONG_TOKEN,
                null,
                401
            );
        }
        return $next($request);
    }
}
