<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/15/2017
 * Time: 12:12 PM
 */

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	protected $table      = 'links';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
	protected $fillable   = [
		'primary_url', 'tracking_link', 'link_group_id', 'link_name', 'tracking_domain', 'cloak_link', 'cloak_page_title', 'cloak_page_description', 'popup_id',
		'magickbar_id', 'timer_id', 'max_clicks', 'smartswap_id', 'smartswap_type', 'traffic_cost', 'geo_targeting', 'geo_targeting_include_countries',
		'geo_targeting_exclude_countries', 'backup_url', 'mobile_url', 'repeat_url', 'tracking_link_visited', 'pixel_code', 'abuser', 'anon', 'bot', 'spider', 'server',
		'user', 'notes', 'detect_new_bots', 'link_type'
	];
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		Tenant::setDb(session()->get('sub_domain'));
	}
}