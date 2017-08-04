<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 5:34 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	protected $table      = 'plans';
	protected $primaryKey = 'plan_id';
	protected $guarded    = [ 'plan_id' ];
	public    $timestamps = true;
	const CREATED_AT = 'date_added';
	const UPDATED_AT = 'date_updated';
}