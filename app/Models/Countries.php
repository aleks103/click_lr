<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/15/2017
 * Time: 12:12 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
	protected $table = 'countries';
	public $timestamps = false;
	protected $primaryKey = 'country_id';
	protected $fillable = ['code', 'name', 'alias_name1', 'full_name', 'iso3', 'number', 'continent_code', 'display_order'];
}