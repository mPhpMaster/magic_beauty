<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class SetLocale
 *
 * @package mPhpMaster\Support\Middleware
 */
class SetLocale
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( request('change_language') ) {
            session()->put('language', request('change_language'));
            $language = request('change_language');
        } elseif ( $request->hasHeader('language') ) {
            $language = $request->header('language', config('app.locale', 'en'));
        } elseif ( session('language') ) {
            $language = session('language');
        } elseif ( config('app.locale') ) {
            $language = config('app.locale');
        }

        if ( isset($language) ) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
