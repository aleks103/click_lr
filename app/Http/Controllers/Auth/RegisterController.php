<?php

namespace App\Http\Controllers\Auth;

use App\Facades\Tenant;
use App\Http\Controllers\Controller;
use App\Mail\welcomeToClickPerfectPurchase;
use App\User;
use App\Users\CustomValidator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/
	
	use RegistersUsers;
	
	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}
	
	public function showRegistrationForm(Request $request)
	{
		if ( $request->has('vc') ) {
			$user_detail = DB::table('paykickstart_transaction_details')->select('payment_id', 'buyer_first_name', 'buyer_last_name', 'buyer_email', 'invoice_id', 'product_id')
				->whereRaw('verification_code = ? AND event = ? AND status = ?', [ $request->input('vc'), 'subscription-payment', 'Pending' ])
				->first();
			
			if ( count($user_detail) == 0 ) {
				$request->session()->flash('error', 'Invalid account activation code!');
				
				return response()->redirectTo('login');
			}
			
			$checkUserDetail = User::where('email', '=', $user_detail->buyer_email)->first();
			
			if ( count($checkUserDetail) > 0 && $checkUserDetail->domain != '' ) {
				$request->session()->flash('error', 'Account already created with the activation code!');
				
				return response()->redirectTo('login');
			} else if ( count($checkUserDetail) == 0 ) {
				$request->session()->flash('error', 'Invalid account activation code!');
				
				return response()->redirectTo('login');
			}
			
			$vc = $request->input('vc');
			
			return response()->view('auth.register', compact('user_detail', 'vc'));
		} else {
			$request->session()->flash('error', 'Account activation code missing!');
			
			return response()->redirectTo('login');
		}
	}
	
	public function register(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required|string|max:20|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'last_name'  => 'required|string|max:20|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'domain'     => 'required|min:' . config('auth.domain_min') . '|max:' . config('auth.domain_max') . '|alpha_num|unique:users',
			'email'      => 'required|email',
			'password'   => 'required|string|min:6|max:20',
		], [
			'first_name.required'         => 'The first name field is required.',
			'first_name.like_restricted'  => 'The first name cannot be included ' . config('auth.restrict_keywords_like'),
			'first_name.match_restricted' => 'The first name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.like_restricted'   => 'The last name cannot be matched ' . config('auth.restrict_keywords_like'),
			'last_name.match_restricted'  => 'The last name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.required'          => 'The last name field is required.'
		]);
		
		$created = $this->create($request);
		
		if ( $created ) {
			$request->session()->flash('success', 'User registered successfully!');
			
			return redirect()->to('http://' . config('site.site_domain') . '/login')->send();
		} else {
			$request->session()->flash('error', 'User register failed!');
			
			return redirect()->to('http://' . config('site.site_domain') . '/register?vc=' . $request->input('vc'))->send();
		}
	}
	
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'first_name' => 'required|string|max:20|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'last_name'  => 'required|string|max:20|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			'domain'     => 'required|min:' . config('auth.domain_min') . '|max:' . config('auth.domain_max') . '|alpha_num|unique:users',
			'email'      => 'required|email',
			'password'   => 'required|string|min:6|max:20',
		], [
			'first_name.required'         => 'The first name field is required.',
			'first_name.like_restricted'  => 'The first name cannot be included ' . config('auth.restrict_keywords_like'),
			'first_name.match_restricted' => 'The first name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.like_restricted'   => 'The last name cannot be matched ' . config('auth.restrict_keywords_like'),
			'last_name.match_restricted'  => 'The last name cannot be matched ' . config('auth.restrict_keywords_exact'),
			'last_name.required'          => 'The last name field is required.'
		]);
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  $request
	 *
	 * @return bool
	 */
	protected function create($request)
	{
		$bba_token = str_random(8);
		
		User::where('email', '=', $request->input('email'))->update([
			'first_name' => $request->input('first_name'),
			'last_name'  => $request->input('last_name'),
			'bba_token'  => $bba_token,
			'password'   => md5($request->input('password') . $bba_token),
			'domain'     => $request->input('domain'),
			'activated'  => '1'
		]);
		
		DB::table('paykickstart_transaction_details')
			->whereRaw('verification_code = ? AND event = ? AND status = ?', [ $request->input('vc'), 'subscription-payment', 'Pending' ])
			->update([ 'status' => 'Success' ]);
		
		$data = [
			'api'     => 'd6025b63d451769',
			'hash'    => 'a768550c27b1fc18885f391799ae90fa',
			'email'   => $request->input('first_name') . ' ' . $request->input('last_name') . '<' . $request->input('email') . '>',
			'list_id' => '1',
		];
		
		$user_info = User::where('email', '=', $request->input('email'))->first();
		
		DB::table('user_plans')->where('user_id', '=', $user_info->id)->update([
			'payment_status' => 'success',
			'status'         => 'Active',
		]);
		
		Mail::to($user_info->email)->send(new welcomeToClickPerfectPurchase($user_info, $request->input('password')));
		
		$dbName = config('site.db_prefix') . strtolower($request->input('domain'));
		if ( !checkIsDBExist($dbName) ) {
			DB::statement(DB::raw("CREATE DATABASE {$dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;"));
			Tenant::setDb($request->input('domain'));
			config([ 'database.connections.mysql_tenant.database' => $dbName ]);
			Artisan::call('migrate', [
				'--database' => 'mysql_tenant', '--path' => 'database/migrations/tenant'
			]);
			DB::setDefaultConnection('mysql');
		}
		
		$url = "https://clickperfect.sendlane.com/api/v1/list-subscribers-add";
		
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_exec($ch);
			curl_close($ch);
		} catch ( \Exception $e ) {
		}
		return true;
	}
}
