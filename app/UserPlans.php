<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/9/2017
 * Time: 2:57 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UserPlans extends Model
{
	protected $table      = 'user_plans';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $guarded    = [ 'id' ];
}