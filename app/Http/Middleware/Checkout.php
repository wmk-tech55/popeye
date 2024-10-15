<?php

namespace MixCode\Http\Middleware;

use Closure;

class Checkout
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
        /** @var \MixCode\User $user */
        $user = auth()->user();
        
        if (auth()->check()) {
            
            return $next($request);
        }

        if (app()->environment('testing')) {
            return redirect()->route('login');
        }
        
        return redirect()->route('login', ['return_url' => url()->current()]);
    }
}
