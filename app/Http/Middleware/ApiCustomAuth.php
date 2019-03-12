<?php

namespace App\Http\Middleware;

use App\Models\UserAuthToken;
use Closure;
use Illuminate\Http\Request;

class ApiCustomAuth
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
        if(!$baererToken = $request->bearerToken())
            abort(401);

        if(!$userToken = UserAuthToken::query()->whereToken($baererToken)->first())
            abort(401);

        \Illuminate\Support\Facades\Auth::login($userToken->user);

        return $next($request);
    }
}
