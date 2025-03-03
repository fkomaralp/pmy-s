<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCartToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if(!$request->hasCookie("token")){
            $token = md5(uniqid());

            $response->cookie("token", $token);
        }

        return $response;
    }
}
