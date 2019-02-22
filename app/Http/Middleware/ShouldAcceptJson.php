<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShouldAcceptJson
{
    /**
     * Valid only with header accept: application/json
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->header('Accept') === 'application/json', 403);
        return $next($request);
    }
}
