<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $table      = 'user_profile';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $guarded    = [ 'id' ];
}
