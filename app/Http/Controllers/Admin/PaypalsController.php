<?php

namespace App\Http\Controllers\Admin;

use App\Models\SudopayPaypalUserLog;
use App\UserPlans;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\MembersRepository;
use Illuminate\Support\Facades\DB;
use App\Users\CustomValidator;

class PaypalsController extends Controller
{
	protected $membersRepository;
	
	/**
	 * PaypalsController constructor.
	 *
	 * @param MembersRepository $membersRepository
	 */
	public function __construct(MembersRepository $membersRepository)
	{
		$this->middleware('auth');
		$this->membersRepository = $membersRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param \Illuminate\Http\Request         $request
	 * @param \App\Models\SudopayPaypalUserLog $sudopayPaypalUserLogs
	 *
	 * @return \Illuminate\Http\Response | \Illuminate\View\View
	 */
	public function index(Request $request, SudopayPaypalUserLog $sudopayPaypalUserLogs)
	{
		$list = $sudopayPaypalUserLogs->getData($request);
		
		$searchParams = [];
		if ( $request->has('subscription_id') ) {
			$searchParams['subscription_id'] = $request->subscription_id;
		}
		if ( $request->has('email') ) {
			$searchParams['email'] = $request->email;
		}
		if ( $request->has('domain') ) {
			$searchParams['domain'] = $request->domain;
		}
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->page;
		}
		
		return view('admin.paypalPending', compact('list', 'searchParams'));
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
	 * @param  \Illuminate\Http\Request         $request
	 * @param  \App\Models\SudopayPaypalUserLog $sudopayPaypalUserLogs
	 *
	 * @return String
	 */
	public function show(Request $request, SudopayPaypalUserLog $sudopayPaypalUserLogs)
	{
		$id = $request->route()->parameter('paypal');
		
		$data = $sudopayPaypalUserLogs->getDetails($id);
		
		if ( sizeof($data) > 0 ) {
			
			$re_data = '';
			foreach ( $data as $row ) {
				$re_data .= $row->paykey . '_COL_' . $row->status . '_COL_' . $row->currency_code . '_COL_' . $row->amount . '_COL_' . $row->payment_date . '_ROW_';
			}
			
			return $re_data;
		} else {
			return '';
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Illuminate\Http\Request         $request
	 * @param  \App\Models\SudopayPaypalUserLog $sudopayPaypalUserLogs
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, SudopayPaypalUserLog $sudopayPaypalUserLogs)
	{
		$subscription_id = $request->route()->parameter('paypal');
		
		$members = $sudopayPaypalUserLogs->whereRaw('subscription_id = ? AND status != ?', [ $subscription_id, 'Deleted' ])->first();
		
		if ( $subscription_id == '' || $subscription_id <= 0 || !isset($members) ) {
			return redirect('/admin/paypals');
		}
		
		$reputation_array = [ 'Trusted' => 'Trusted', 'Untrusted' => 'Untrusted' ];
		
		return view('admin.createPayPalUser', compact('members', 'reputation_array'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request         $request
	 * @param  \App\Models\SudopayPaypalUserLog $sudopayPaypalUserLogs
	 * @param  \App\UserPlans                   $userPlans
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, SudopayPaypalUserLog $sudopayPaypalUserLogs, UserPlans $userPlans)
	{
		$this->validate($request, [
			'first_name'       => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'last_name'        => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'domain'           => 'required|min:' . config('auth.domain_min') . '|max:' . config('auth.domain_max') . '|alpha_num|unique:users',
			'email'            => 'required|email|unique:users',
			'group_id'         => 'required',
			'password'         => 'required|min:' . config('auth.password_min') . '|max:' . config('auth.password_max'),
			'confirm_password' => 'required|min:' . config('auth.password_min') . '|max:' . config('auth.password_max') . '|same:password',
		], [
			'first_name.required'         => 'The first name field is required.',
			'first_name.like_restricted'  => 'The first name cannot be included ' . config('auth.restrict_keywords_like'),
			'first_name.match_restricted' => 'The first name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.like_restricted'   => 'The last name cannot be matched ' . config('auth.restrict_keywords_like'),
			'last_name.match_restricted'  => 'The last name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.required'          => 'The last name field is required.',
			'group_id.required'           => 'The group name field is required.',
		]);
		
		$create = $this->membersRepository->create($request, $userPlans);
		
		$subscription_id = $request->route()->parameter('paypal');
		
		$sudopayPaypalUserLogs->whereRaw('subscription_id = ? ', [ $subscription_id ])->update([ 'status' => 'Deleted' ]);
		
		DB::table('sudopay_paypal_user_logs_second')->whereRaw('subscription_id = ?', [ $subscription_id ])->update([ 'status' => 'Deleted' ]);
		
		return $create ? redirect('/admin/paypals') : redirect()->back();
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\SudopayPaypalUserLog $sudopayPaypalUserLogs
	 * @param  \Illuminate\Http\Request         $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(SudopayPaypalUserLog $sudopayPaypalUserLogs, Request $request)
	{
		$idStr = $request->pay_check_ids;
		
		if ( isset($idStr) ) {
			
			$idAry = explode(',', $idStr);
			
			if ( count($idAry) > 0 ) {
				
				foreach ( $idAry as $id ) {
					
					$delRow = $sudopayPaypalUserLogs->find($id);
					
					$delRow->status  = 'Deleted';
					$subscription_id = $delRow->subscription_id;
					
					$delRow->save();
					
					DB::table('sudopay_paypal_user_logs_second')->where('subscription_id', '=', $subscription_id)
						->update([ 'status' => 'Deleted' ]);
				}
				
				$request->session()->flash('success', 'Deleted successfully');
			}
		}
		
		return redirect('/admin/paypals');
	}
}
