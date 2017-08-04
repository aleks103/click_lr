<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class RotatorsGroup extends Model
{
    protected $table      = 'rotator_groups';
    public    $timestamps = false;
    protected $primaryKey = 'id';
    protected $connection = 'mysql_tenant';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        Tenant::setDb(session()->get('sub_domain'));
    }
}
