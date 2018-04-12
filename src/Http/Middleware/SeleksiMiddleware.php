<?php

namespace Bantenprov\Seleksi\Http\Middleware;

use Closure;

/**
 * The SeleksiMiddleware class.
 *
 * @package Bantenprov\Seleksi
 * @author  bantenprov <developer.bantenprov@gmail.com>
 */
class SeleksiMiddleware
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
        return $next($request);
    }
}
