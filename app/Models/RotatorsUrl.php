<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class RotatorsUrl extends Model
{
	protected $table      = 'rotator_urls';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
	protected $fillable   = [
		'name', 'url', 'position', 'max_clicks', 'max_daily_clicks', 'bonus', 'min_mobile', 'max_mobile', 'start_date', 'end_date', 'status',
		'geo_targeting', 'geo_targeting_include_countries', 'geo_targeting_exclude_countries', 'popup_id', 'timer_id', 'magickbar_id'
	];
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		Tenant::setDb(session()->get('sub_domain'));
	}
}
