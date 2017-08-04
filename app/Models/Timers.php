<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Timers extends Model
{
	protected $table      = 'timer';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
    protected $fillable = [
            'timer_name',
            'timer_type',
            'position',
            'transparent',
            'timer_type_days',
            'timer_type_hours',
            'timer_type_minutes',
            'timer_type_expires_at',
            'timer_style',
            'color',
            'timer_width',
            'background_color',
            'show_day',
            'show_hour',
            'show_minute',
            'show_seconds',
            'day_width',
            'on_expires',
            'redirect_url'
    ];
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		Tenant::setDb(session()->get('sub_domain'));
	}
}
