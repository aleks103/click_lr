<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkNotificationLog extends Model
{
	protected $table      = 'link_notification_log';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $guarded    = [ 'id' ];
}
