<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Rotators extends Model
{
	protected $table      = 'rotators';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
	protected $fillable   = [
		'rotator_group_id', 'rotator_name', 'rotator_link', 'rotator_mode', 'on_finish', 'cloak_rotator', 'cloak_page_title', 'cloak_page_description',
		'backup_url', 'mobile_url', 'popup_id', 'magickbar_id', 'cookie_duration', 'randomize', 'geo_targeting', 'notes',
		'timer_id', 'pixel_code', 'abuser', 'anon', 'bot', 'spider', 'server', 'user'
	];
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		Tenant::setDb(session()->get('sub_domain'));
	}
}
