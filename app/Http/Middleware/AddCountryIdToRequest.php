<?php

namespace MixCode\Http\Middleware;

use Closure;

class AddCountryIdToRequest
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

        $country_id = auth()->user()->country_id ?? null;

        if ($request->isMethod('POST') && (!$request->has('country_id') && !$request->filled('country_id'))) {
            $request->request->add(['country_id' => $country_id]);
        }

        if (auth()->check() &&  ($request->has('country_id') && $request->filled('country_id'))) {
            $request->request->add(['country_id' =>  $country_id]);
        }

        return $next($request);
    }
}
