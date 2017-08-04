<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/12/2017
 * Time: 9:07 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaykickstartTransactionDetails extends Model
{
	protected $table = 'paykickstart_transaction_details';
	protected $primaryKey = 'payment_id';
	public $timestamps = false;
	protected $fillable = [
		'event', 'mode', 'payment_processor', 'amount', 'buyer_ip', 'buyer_first_name', 'buyer_last_name', 'buyer_email', 'vendor_first_name',
		'vendor_last_name', 'vendor_email', 'transaction_id', 'invoice_id', 'tracking_id', 'transaction_time', 'product_id', 'product_name', 'campaign_id',
		'campaign_name', 'affiliate_first_name', 'affiliate_last_name', 'affiliate_email', 'affiliate_commission_amount', 'affiliate_commission_percent',
		'licenses', 'verification_code', 'payment_status', 'status'
	];

	/**
	 * Get PayKickStart list
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function getDetails($request)
	{
		$query = $this->select('payment_id', 'invoice_id', 'buyer_email', 'buyer_first_name', 'buyer_last_name', 'product_name', 'amount', 'status', 'transaction_time')
			->whereRaw('status != ? AND status != ? AND status != ? AND event = ?', ['Success', 'Cancelled', '', 'subscription-payment']);
		if (isset($request->invoice_id)) {
			$query = $query->where('invoice_id', '=', $request->invoice_id);
		}
		if (isset($request->email)) {
			$query = $query->where('buyer_email', '=', $request->email);
		}
		if (isset($request->status)) {
			$query = $query->where('status', '=', $request->status);
		}
		$query = $query->orderBy('transaction_time', 'DESC')->paginate(getConfig('per_page_news'));
		return $query;
	}

	public function getHistoryList($id)
	{
		$data = $this->where('invoice_id', '=', $id)->where('event', '=', 'subscription-payment')->orderBy('transaction_time', 'DESC')->get();
		if (sizeof($data) > 0) {
			return $data;
		} else {
			return [];
		}
	}
}