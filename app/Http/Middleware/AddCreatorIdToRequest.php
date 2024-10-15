<?php

namespace MixCode\Http\Middleware;

use Closure;

class AddCreatorIdToRequest
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
      
        if ($request->isMethod('POST') && (! $request->has('creator_id') && ! $request->filled('creator_id'))) {
            
            $request->request->add(['creator_id' => auth()->id()]);
        }
        
        if (auth()->check() && ! auth()->user()->isAdmin() && ($request->has('creator_id') && $request->filled('creator_id'))) {
            $request->request->add(['creator_id' => auth()->id()]);
        }

        return $next($request);
    }
}
