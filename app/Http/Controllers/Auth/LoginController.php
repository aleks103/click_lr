<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginAsUser;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/
	
	use AuthenticatesUsers;
	
	/**
	 * Where to redirect users after login.
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
		$this->middleware('guest')->except('logout');
	}
	
	public function login(Request $request)
	{
		$user = User::where('email', '=', $request['email'])->first();
		
		$password = md5($request['password'] . $user['bba_token']);
		
		if ( $password === $user['password'] ) {
			
			AdminLoginAsUser::where('admin_id', '=', $user->id)->orWhere('user_id', $user->id)->delete();
			
			Auth::login($user, $request->has('remember'));
			
			return redirect()->intended($this->redirectPath());
		} else {
			
			Auth::logout();
			
			return $this->sendFailedLoginResponse($request);
		}
	}
}
