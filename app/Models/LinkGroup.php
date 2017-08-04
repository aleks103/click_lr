<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class LinkGroup extends Model
{
    protected $table      = 'link_groups';
    public    $timestamps = false;
    protected $primaryKey = 'id';
    protected $connection = 'mysql_tenant';
    protected $fillable = [
        'link_group'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        Tenant::setDb(session()->get('sub_domain'));
    }
}
