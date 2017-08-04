<?php
/**
 * Created by PhpStorm.
 * Time: 18:09
 */

namespace App\Users\Cache;


use App\Contracts\MyCache;
use Closure;

class NoCache implements MyCache
{
	public function setTag($tag)
	{
		// Do Nothing
	}

	public function setTime($time_in_minute)
	{
		// Do Nothing
	}

	public function remember($key, Closure $entity, $tag = null)
	{
		/**
		 * directly return
		 */
		return $entity();
	}

	public function forget($key, $tag = null)
	{
		// Do Nothing
	}


	public function clearCache($tag = null)
	{
		// Do Nothing
	}


	public function clearAllCache()
	{
		// Do Nothing
	}
}