<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/23/2017
 * Time: 4:51 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SudopayPaypalUserLog extends Model
{
	protected $table = 'sudopay_paypal_user_logs';
	protected $primaryKey = 'id';
	public $timestamps = false;

	/**
	 * Get Data
	 * @param $request
	 * @return mixed
	 */
	public function getData($request)
	{
		$query = $this->select('sudopay_paypal_user_logs.id', 'sudopay_paypal_user_logs.subscription_id', 'sudopay_paypal_user_logs.email', 'sudopay_paypal_user_logs.domain',
			'sudopay_paypal_user_logs.first_name', 'sudopay_paypal_user_logs.last_name', 'sudopay_paypal_user_logs.address', 'sudopay_paypal_user_logs.city',
			'sudopay_paypal_user_logs.zip_code', 'sudopay_paypal_user_logs.country_code', 'sudopay_paypal_user_logs.state', 'sudopay_paypal_user_logs.phone',
			'sudopay_paypal_user_logs.item_name', 'sudopay_paypal_user_logs.item_description', 'sudopay_paypal_user_logs.payment_status', 'sudopay_paypal_user_logs.amount',
			'sudopay_paypal_user_logs.date_added')
			->join('sudopay_paypal_user_logs_second', 'sudopay_paypal_user_logs.subscription_id', '=', 'sudopay_paypal_user_logs_second.subscription_id')
			->whereRaw('(sudopay_paypal_user_logs_second.status = ? OR sudopay_paypal_user_logs.payment_status = ?) AND sudopay_paypal_user_logs.status != ?', [
				'Pending', 'Pending', 'Deleted'
			]);
		if (isset($request->subscription_id)) {
			$query = $query->where('sudopay_paypal_user_logs.subscription_id', '=', $request->subscription_id);
		}
		if (isset($request->email)) {
			$query = $query->where('sudopay_paypal_user_logs.email', '=', $request->email);
		}
		if (isset($request->domain)) {
			$query = $query->where('sudopay_paypal_user_logs.domain', '=', $request->domain);
		}
		$query = $query->orderBy('sudopay_paypal_user_logs.date_added', 'DESC')->paginate(getConfig('per_page_news'));
		return $query;
	}

	/**
	 * get details.
	 * @param $id
	 * @return array
	 */
	public function getDetails($id)
	{
		$data = $this->find($id);
		if (sizeof($data) > 0) {
			$email = $data->email;
			$query = DB::table('sudopay_transaction_logs')->where('buyer_email', '=', $email)->get();
			return $query;
		} else {
			return [];
		}
	}
}