<?php

namespace Sowork\YAuth\Http\Middleware;

use Closure;
use Sowork\YAuth\Facades\YAuth;
use Sowork\YAuth\Http\Exceptions\YAuthNotPermissionException;

class YAuthAuthorize
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

        if(YAuth::can($request->route()->getName())){
            return $next($request);
        }

        throw new YAuthNotPermissionException('permission deny...');
    }
}
