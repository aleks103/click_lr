<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/7/2017
 * Time: 4:23 AM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class MyConfig extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'MyConfig';
	}
}