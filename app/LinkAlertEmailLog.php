<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkAlertEmailLog extends Model
{
	protected $table      = 'link_alert_email_log';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $guarded    = [ 'id' ];
}
