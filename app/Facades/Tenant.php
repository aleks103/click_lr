<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/10/2017
 * Time: 7:59 AM
 */

namespace App\Facades;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

class Tenant extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'Tenant';
	}
	
	public static $_tenant_connection = '';
	
	public static function setDb($domain)
	{
		config([ 'database.connections.mysql_tenant.database' => config('site.db_prefix') . strtolower($domain) ]);
		
		session([ 'tenant_connect' => 'mysql_tenant' ]);
	}
	
	public static function getDb()
	{
		return config('database.connections.mysql_tenant.database');
	}
	
	public static function DB()
	{
		if ( !self::$_tenant_connection ) {
			self::$_tenant_connection = DB::connection(session('tenant_connect'));
		}
		
		return self::$_tenant_connection;
	}
	
	public static function to(Request $request, $domain, $path)
	{
		if ( $request->getHttpHost() ) {
			$host_parts = explode(':', $request->getHttpHost());
			
			$new_host = str_ireplace($host_parts[0], $domain . '.' . config('site.site_domain'), $request->root()) . '/' . $path;
		} else {
			$main_domain_url = MyConfig::getValue('main_domain_url');
			
			$new_host = 'http://' . $domain . '.' . $main_domain_url . '/' . $path;
		}
		
		return $new_host;
	}
	
	public static function toMain(Request $request, $path)
	{
		if ( $request->getHttpHost() ) {
			$host_parts = explode(':', $request->getHttpHost());
			
			$new_host = str_ireplace($host_parts[0], config('site.site_domain'), $request->root()) . '/' . $path;
		} else {
			$main_domain_url = MyConfig::getValue('main_domain_url');
			
			$new_host = 'http://' . $main_domain_url . '/' . $path;
		}
		
		return $new_host;
	}
}