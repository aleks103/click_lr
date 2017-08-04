<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/13/2017
 * Time: 6:34 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminLoginAsUser extends Model
{
	protected $table = 'admin_login_as_users';
	public $timestamps = false;
	protected $primaryKey = 'id';
	protected $fillable = ['user_id', 'fp_id', 'admin_id', 'domain', 'user_key', 'created_at'];

	public function insertAutoLogin($user_id, $finger_print)
	{
		$admin_details = auth()->user();
		$this->where('admin_id', '=', auth()->id())->delete();
		$insert_data = [
			'user_id'    => $user_id,
			'fp_id'      => $finger_print,
			'admin_id'   => auth()->id(),
			'domain'     => $admin_details->domain,
			'user_key'   => md5(auth()->id() . $admin_details->domain),
			'created_at' => DB::raw('unix_timestamp(NOW())')
		];
		return $this->insertGetId($insert_data);
	}
}