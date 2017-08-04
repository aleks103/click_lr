<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/15/2017
 * Time: 12:12 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransactionDetail extends Model
{
	protected $table = 'payment_transaction_details';
	public $timestamps = false;
	protected $primaryKey = 'payment_id';
	protected $fillable = ['user_plan_id', 'name', 'amount'];

	public function getData($request)
	{
		$query = $this->select('payment_transaction_details.payment_id', 'payment_transaction_details.user_plan_id', 'payment_transaction_details.name',
			'payment_transaction_details.amount', 'payment_transaction_details.currency', 'payment_transaction_details.card_details',
			'payment_transaction_details.paid', 'payment_transaction_details.date_added', 'payment_transaction_details.description', 'payment_transaction_details.used_for',
			'plans.plan_name', 'payment_transaction_details.status', 'payment_transaction_details.transaction_id',
			'user_plans.user_id', 'user_plans.payment_method', 'user_plans.subscribe_code', 'users.first_name', 'users.last_name', 'users.email')
			->join('user_plans', 'payment_transaction_details.user_plan_id', '=', 'user_plans.id')
			->join('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
			->join('users', 'user_plans.user_id', '=', 'users.id')
			->whereRaw('payment_transaction_details.used_for != ? AND payment_transaction_details.used_for != ?', ['Addon_sify', 'import']);
		if (isset($request->plan_id)) {
			$query = $query->where('user_plans.plan_id', '=', $request->plan_id);
		}
		if (isset($request->payment_method)) {
			$query = $query->where('user_plans.payment_method', '=', $request->payment_method);
		}
		if (isset($request->name)) {
			$query = $query->whereRaw('(payment_transaction_details.name LIKE "%' . addslashes($request->name) . '%" OR users.first_name LIKE "%' . addslashes($request->name) . '%" OR users.last_name LIKE "%' . addslashes($request->name) . '%")');
		}
		if (isset($request->email)) {
			$query = $query->where('users.email', '=', $request->email);
		}
		if (isset($request->transaction_id)) {
			$query = $query->where('payment_transaction_details.transaction_id', '=', $request->transaction_id);
		}

		$query = $query->orderBy('payment_transaction_details.payment_id', 'DESC')->paginate(getConfig('billing_perpage'));
		return $query;
	}
}