<?php

namespace App\Models;

use App\Facades\Tenant;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table      = 'custom_domain';
    public    $timestamps = false;
    protected $primaryKey = 'id';
    protected $connection = 'mysql_tenant';
    protected $fillable = [
        'domain_name',
        'domain_for'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        Tenant::setDb(session()->get('sub_domain'));
    }
}
