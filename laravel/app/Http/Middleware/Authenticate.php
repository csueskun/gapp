<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $configController = app('App\Http\Controllers\ConfigController');
        $v='ven';$c='ce';$l="{$c}ncia";
        try {
            $token = $configController->readLicenceToken();
            if($token["li{$l}_{$v}{$c}"]&&!(date('Y-m-d H:i:s')<$token["li{$l}_hasta"])){
                throw new Exception('');
            }
        } catch (\Throwable $th) {
            try {
                $configController->downloadToken();
                $token = $configController->readLicenceToken();
                if($token["li{$l}_{$v}{$c}"]&&!(date('Y-m-d H:i:s')<$token["li{$l}_hasta"])){
                    return response("Li{$l} {$v}cida.", 403);
                }
            } catch (\Throwable $th) {
                return response(
                    "Li{$l}"." no e"."nc"."ont"."rad"."a o"." co"."rr"."upta", 403);
            }
        }

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login')->with('status', ["danger"=>"Inicie Sessión."]);
            }
        }

        return $next($request);
    }
}
