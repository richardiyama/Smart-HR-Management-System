<?php

namespace App\Http\Middleware;
use Symfony\Component\HttpFoundation\Response;

use Closure;

class HrOfficer
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
        if ($request->user()->role == 5 || $request->user()->role== 1 || $request->user()->role == 2 || $request->user()->role == 3)
        {
            return $next($request);
        }else {
            return new Response(view('unauthorized')->with('role', 'HR Officer or HR Manager'));
        }
        
    }
}
