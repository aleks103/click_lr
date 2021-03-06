<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ( !is_null($request->user()) ) {
			if ( !isSupperAdmin() && !isAdminUser() ) {
				abort(401);
			}
		} else {
			abort(401);
		}
		
		return $next($request);
	}
}
