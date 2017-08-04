<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Blockipaddress extends Model
{
    protected $table      = 'block_ip_address';
    public    $timestamps = false;
    protected $primaryKey = 'id';
    protected $connection = 'mysql_tenant';
    protected $fillable = [
        'from_ip_address',
        'to_ip_address',
        'note'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        Tenant::setDb(session()->get('sub_domain'));
    }
}
