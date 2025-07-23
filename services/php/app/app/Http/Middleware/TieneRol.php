<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TieneRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $rol, $guard = null)
    {
        if ( $request->user()->rol == $rol)
            return $next($request);
        else
            abort(403, "No está autorizado para ingresar");

    }
}
