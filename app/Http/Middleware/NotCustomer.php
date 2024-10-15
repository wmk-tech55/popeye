<?php

namespace MixCode\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class NotCustomer
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
        abort_if(auth()->user()->isCustomer(), Response::HTTP_FORBIDDEN);
        
        return $next($request);
    }
}
