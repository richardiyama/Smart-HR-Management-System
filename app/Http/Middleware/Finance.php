<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class Finance
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
        if ($request->user()->role == 6 || $request->user()->role == 1 || $request->role == 3)
        {
            return $next($request);
        }else{
            return new Response(view('unauthorized')->with('role', 'PAYROLL'));
        }
        
    }
}
