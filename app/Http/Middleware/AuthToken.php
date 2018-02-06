<?php

namespace App\Http\Middleware;

use App\Exceptions\ControllableException;
use App\Models\User;
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
        $user = User::where('api_token', $token)->first();
        if ( !$user ){
            throw new ControllableException(
                R::AUTH_WRONG_TOKEN,
                null,
                401
            );
        }
        \Auth::login($user);
        return $next($request);
    }
}
