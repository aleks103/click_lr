<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/14/2017
 * Time: 8:58 PM
 */

namespace App\Http\Repositories;

use App\LinkRotatorsAlert;
use Illuminate\Support\Facades\DB;

class LinkRotatorsAlertRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(LinkRotatorsAlert::class);
	}
	
	/**
	 * @param $user_id
	 * @param $ref_id
	 * @param $ref_type
	 * @param $alert_status
	 */
	public function create($user_id, $ref_id, $ref_type, $alert_status)
	{
		$data_arr = [
			'user_id'    => $user_id,
			'ref_id'     => $ref_id,
			'ref_type'   => $ref_type,
			'status'     => $alert_status,
			'created_at' => DB::raw("unix_timestamp(now())"),
			'updated_at' => DB::raw("unix_timestamp(now())"),
		];
		
		$this->model()->insert($data_arr);
	}
	
	/**
	 * @param $user_id
	 * @param $ref_id
	 */
	public function delete($user_id, $ref_id)
	{
		$this->model()->where('user_id', '=', $user_id)->where('ref_id', '=', $ref_id)->delete();
	}
	
	/**
	 * @param $user_id
	 * @param $ref_id
	 * @param $ref_type
	 * @param $alert_status
	 */
	public function update($user_id, $ref_id, $ref_type, $alert_status)
	{
		$up_data = [
			'status'     => $alert_status,
			'updated_at' => DB::raw("unix_timestamp(now())"),
		];
		if ($ref_id != '') {
			$this->model()->where('user_id', '=', $user_id)->where('ref_id', '=', $ref_id)->where('ref_type', '=', $ref_type)->update($up_data);
		} else {
			$this->model()->where('user_id', '=', $user_id)->where('ref_type', '=', $ref_type)->update($up_data);
		}
	}
}