<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/7/2017
 * Time: 4:29 AM
 */

namespace App\Contracts;

use Closure;

interface MyCache
{
	public function setTag($tag);

	public function setTime($time_in_minute);

	public function remember($key, Closure $entity, $tag = null);

	public function forget($key, $tag = null);

	public function clearCache($tag = null);

	public function clearAllCache();
}