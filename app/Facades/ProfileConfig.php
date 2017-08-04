<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/14/2017
 * Time: 8:33 PM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ProfileConfig extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'ProfileConfig';
	}
}