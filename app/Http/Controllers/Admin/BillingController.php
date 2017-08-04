<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentTransactionDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingController extends Controller
{
	public function billingHistoryList(Request $request, PaymentTransactionDetail $paymentTransactionDetail)
	{
		$list = $paymentTransactionDetail->getData($request);
		
		$searchParams = [];
		if ( $request->has('plan_id') ) {
			$searchParams['plan_id'] = $request->plan_id;
		}
		if ( $request->has('payment_method') ) {
			$searchParams['payment_method'] = $request->payment_method;
		}
		if ( $request->has('name') ) {
			$searchParams['name'] = $request->name;
		}
		if ( $request->has('email') ) {
			$searchParams['email'] = $request->email;
		}
		if ( $request->has('transaction_id') ) {
			$searchParams['transaction_id'] = $request->transaction_id;
		}
		
		$payment_methoad_arr = [ 'Stripe' => 'Stripe', 'Paypal' => 'Paypal' ];
		
		return view('admin.billingHistory', compact('list', 'searchParams', 'payment_methoad_arr'));
	}
}
