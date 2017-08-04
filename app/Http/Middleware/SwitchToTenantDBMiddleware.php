<?php

namespace App\Http\Middleware;

use App\Facades\Tenant;
use App\User;
use Closure;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SwitchToTenantDBMiddleware
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
		$subDomain = $request->route()->parameter('sub_domain');
		
		if ( $subDomain == 'www' ) {
			return redirect()->to(getSecureRedirect() . config('site.site_domain'))->send();
		}
		
		if ( !isset($subDomain) || $subDomain == '' ) {
			return $next($request);
		}
		
		$tenant = User::where('domain', '=', $subDomain)->first();
		
		if ( !$tenant ) {
			throw new NotFoundHttpException(sprintf('Subdomain %s does not exist.', $subDomain));
		} else {
			$dbName = config('site.db_prefix') . strtolower($subDomain);
			
			$res = DB::select("show databases like '{$dbName}'");
			
			if ( count($res) == 0 ) {
				abort(401, 'Database does not exists for this domain!');
			} else {
				// Set the tenant DB name and fire it up as the new default DB connection
				Tenant::setDb($subDomain);
				
				$request->session()->put('sub_domain', $subDomain);
			}
		}
		
		return $next($request);
	}
}
