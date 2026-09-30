<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleCheck
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
        if(Auth::check() && in_array(Auth::user()->role, ['department user', 'individual']))
            return redirect('/report/par-individual'); 
        
        return $next($request);
    }
    
}
