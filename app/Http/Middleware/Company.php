<?php

namespace MixCode\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Company
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
        $user = auth()->user();
        abort_unless(($user->isCompany()  ), Response::HTTP_FORBIDDEN);
        
        return $next($request);
    }
}
