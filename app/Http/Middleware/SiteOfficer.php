<?php

namespace App\Http\Middleware;
use Symfony\Component\HttpFoundation\Response;

use Closure;

class SiteOfficer
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
        if ($request->user()->role == 4 || $request->user()->role == 1 || $request->user()->role == 2)
        {
            return $next($request);
        }else {
            return new Response(view('unauthorized')->with('role', 'HR Site Officer'));
        }
        
    }
}
