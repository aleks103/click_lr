<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Popbars extends Model
{
	protected $table      = 'magick_bar';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
    protected $fillable = [
            'bar_name',
            'position',
            'height',
            'timing',
            'delay_timing',
            'shadow',
            'closable',
            'spacer',
            'button_color',
            'transparent_background',
            'url',
            'content'
    ];
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		Tenant::setDb(session()->get('sub_domain'));
	}
}
