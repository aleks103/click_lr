<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\MembersRepository;
use App\Models\PaykickstartTransactionDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PaykickstartsController extends Controller
{
	protected $membersRepository;
	
	public function __construct(MembersRepository $membersRepository)
	{
		$this->membersRepository = $membersRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request                        $request
	 * @param PaykickstartTransactionDetails $paykickstartTransactionDetails
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(Request $request, PaykickstartTransactionDetails $paykickstartTransactionDetails)
	{
		$list = $paykickstartTransactionDetails->getDetails($request);
		
		$searchParams = [];
		if ( $request->has('invoice_id') ) {
			$searchParams['invoice_id'] = $request->invoice_id;
		}
		if ( $request->has('email') ) {
			$searchParams['email'] = $request->email;
		}
		if ( $request->has('status') ) {
			$searchParams['status'] = $request->status;
		}
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->page;
		}
		
		$status = [
			[ 'id' => '', 'name' => '' ],
			[ 'id' => 'Pending', 'name' => 'Pending' ],
			[ 'id' => 'UserActive', 'name' => 'User Active' ],
			[ 'id' => 'Banned', 'name' => 'Banned User' ],
			[ 'id' => 'NoPlan', 'name' => 'No Plan' ],
			[ 'id' => 'WithoutTrial', 'name' => 'Without Trial' ],
			[ 'id' => 'TrialPlan', 'name' => 'Trial Plan' ],
		];
		
		return view('admin.paykickstartPending', compact('list', 'status', 'searchParams'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param PaykickstartTransactionDetails $paykickstartTransactionDetails
	 * @param Request                        $request
	 *
	 * @return \Illuminate\Http\Response | String
	 */
	public function show(Request $request, PaykickstartTransactionDetails $paykickstartTransactionDetails)
	{
		$id = $request->route()->parameter('paykickstart');
		
		$data = $paykickstartTransactionDetails->getHistoryList($id);
		if ( sizeof($data) > 0 ) {
			$re_data = '';
			foreach ( $data as $row ) {
				$re_data .= $row->invoice_id . '_COL_' . $row->payment_status . '_COL_' . $row->product_name . '_COL_$' . $row->amount . '_COL_' . date('Y-m-d H:i:s', $row->transaction_time) . '_ROW_';
			}
			
			return $re_data;
		} else {
			return '';
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  PaykickstartTransactionDetails $paykickstartTransactionDetails
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(PaykickstartTransactionDetails $paykickstartTransactionDetails)
	{
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request                        $request
	 * @param  PaykickstartTransactionDetails $paykickstartTransactionDetails
	 *
	 * @return \Illuminate\Http\Response | String
	 */
	public function update(Request $request, PaykickstartTransactionDetails $paykickstartTransactionDetails)
	{
		$invoice_id = $request->route()->parameter('paykickstart');
		
		$PKSTransactionDetails = $paykickstartTransactionDetails->whereRaw('invoice_id = ? AND event = ?', [ $invoice_id, 'subscription-payment' ])
			->orderBy('payment_id', 'DESC')->first();
		
		if ( isset($PKSTransactionDetails) ) {
			$user_info = $this->membersRepository->getValueByColumns([ 'id', 'email', 'domain', 'current_plan' ], [ [ 'email', '=', $PKSTransactionDetails->buyer_email ] ]);
			
			if ( isset($user_info->current_plan) ) {
				$user_plan = DB::table('user_plans')->find($user_info->current_plan);
				
				if ( $user_plan ) {
					
					$expiry_date = "INTERVAL " . $user_plan->duration . " " . strtoupper($user_plan->duration_schedule);
					
					$user_plan->activated_on   = DB::raw('NOW()');
					$user_plan->purchased_date = DB::raw('NOW()');
					$user_plan->date_added     = DB::raw('NOW()');
					$user_plan->attempt_date   = DB::raw('NOW()');
					$user_plan->free_flag      = '0';
					$user_plan->expiry_on      = DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')');
					$user_plan->save();
					
					$request->session()->flash('success', 'Upgrades');
				} else {
					$request->session()->flash('error', 'Plan expired or cancelled');
					
					return 'error';
				}
			} else {
				$request->session()->flash('error', 'Plan expired or cancelled');
				
				return 'error';
			}
		} else {
			$request->session()->flash('error', 'Not Upgrades');
			
			return 'error';
		}
		
		return 'success';
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Request                        $request
	 * @param  PaykickstartTransactionDetails $paykickstartTransactionDetails
	 *
	 * @return \Illuminate\Http\Response | String
	 */
	public function destroy(Request $request, PaykickstartTransactionDetails $paykickstartTransactionDetails)
	{
		$invoice_id = $request->route()->parameter('paykickstart');
		
		if ( isset($invoice_id) && $invoice_id != '' ) {
			$url = "https://app.paykickstart.com/api/subscriptions/cancel";
			
			$data = [
				'auth_token' => 'fGhQPIV3LbUD',
				'invoice_id' => $invoice_id
			];
			
			try {
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$output = curl_exec($ch);
				curl_close($ch);
				
				$obj = json_decode($output);
				
				if ( isset($obj->success) ) {
					
					$paykickstartTransactionDetails->whereRaw('invoice_id = ? AND event = ? AND status != ?', [ $invoice_id, 'subscription-payment', 'Success' ])
						->update([ 'status' => 'Cancelled' ]);
					
					$request->session()->flash('success', $obj->message);
				}
			} catch ( Exception $e ) {
				
				$request->session()->flash('error', $e->getMessage());
				
				return 'error';
			}
			
			return 'success';
		}
	}
}
