<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPreviewPageType
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
        if(array_key_exists("HTTP_REFERER", $_SERVER) && strpos($_SERVER["HTTP_REFERER"], 'event/') !== false) {
            return $next($request);
        } else {
            return redirect()->route("frontend.index");
        }
    }
}
