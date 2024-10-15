<?php

namespace MixCode\Http\Middleware;

use Closure;

class Active
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
        if (auth()->check()) {
            
            if (auth()->user()->isActive()) {

                return $next($request);
            } else {

                return redirect()->route('account_status');
            }    
        }

        return $next($request);
    }
}
