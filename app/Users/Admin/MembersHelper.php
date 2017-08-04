<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 4:20 PM
 */

namespace App\Users\Admin;

use App\Facades\MyConfig;

trait MembersHelper
{
	public function getConfig($key)
	{
		return MyConfig::getValue($key);
	}
}