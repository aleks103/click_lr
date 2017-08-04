<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkRotatorsAlert extends Model
{
	protected $table      = 'link_rotator_alerts';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $guarded    = [ 'id' ];
}
