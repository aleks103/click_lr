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

class CustomDomain extends Model
{
	protected $table      = 'custom_domain';
	public    $timestamps = false;
	protected $primaryKey = 'id';
	protected $connection = 'mysql_tenant';
	
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		Tenant::setDb(session()->get('sub_domain'));
	}
}