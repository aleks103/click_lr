<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Popups extends Model
{
	protected $table      = 'popup';
    public    $timestamps = false;
    protected $primaryKey = 'id';
    protected $connection = 'mysql_tenant';
    protected $fillable = [
            'popupname',
            'width',
            'height',
            'timing',
            'delay_timing',
            'exit_method',
            'closable',
            'cookie_duration',
            'url',
            'popup_contents'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        Tenant::setDb(session()->get('sub_domain'));
    }
}
