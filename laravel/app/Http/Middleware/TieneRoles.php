<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TieneRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $guard = null)
    {
        $roles = explode('.',$roles);

        foreach($roles as $rol){
            if ( $request->user()->rol == $rol)
                return $next($request);
        }
        abort(403, "No está autorizado para ingresar");
    }
}
