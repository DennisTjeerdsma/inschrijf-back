<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class ClearanceMiddleware
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

        if ($request->is('api/eventlist/*'))
        {
            if (!Auth::user()->hasPermissionTo('view events'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('api/event/*'))
        {
            if (!Auth::user()->hasPermissionTo('edit events'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('api/setenroll/*'))
        {
            if (!Auth::user()->hasPermissionTo('enroll'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }
        
        if ($request->is('api/userlist'))
        {
            if (!Auth::user()->hasPermissionTo('view users'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }
        if ($request->is('api/makeevent'))
        {
            if (!Auth::user()->hasPermissionTo('create events'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('api/user/delete/*'))
        {
            if (!Auth::user()->hasPermissionTo('edit users'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('api/user/multidelete'))
        {
            if (!Auth::user()->hasPermissionTo('edit users'))
            {
                abort('401');
            }
            else {
                return $next($request);
            }
        }

        if ($request->is('api/user/create'))
        {
            if (!Auth::user()->hasPermissionTo('create users'))
            {
                abort('401');
            } else{
                return $next($request);
            }
        }
        return $next($request);
    }
}
