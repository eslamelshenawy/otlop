<?php

namespace App\Http\Middleware;

use Closure;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next = null, $guard = 'web')
    {
        if (\Auth::guard($guard)->check()) {
            return $next($request);
            //return redirect('admin');
        }
        else
        {
            return redirect('/');
        }


    }
}
