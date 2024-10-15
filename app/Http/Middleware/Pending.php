<?php

namespace MixCode\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Pending
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
        if (auth()->check() && auth()->user()->isActive()) {
            return back();
        }

        return $next($request);
    }
}
