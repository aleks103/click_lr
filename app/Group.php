<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 7:49 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $table      = 'groups';
	protected $primaryKey = 'id';
	public    $timestamps = true;
	protected $fillable   = [ 'id', 'name', 'group_code', 'permissions' ];
	
}