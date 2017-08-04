<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/
	
	use ResetsPasswords;
	
	/**
	 * Where to redirect users after resetting their password.
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
	
	public function reset(Request $request)
	{
		$this->validate($request, $this->rules(), $this->validationErrorMessages());
		
		$response = $this->broker()->reset($this->credentials($request), function ($user, $password) {
			$this->resetPassword($user, $password);
		});
		
		// If the password was successfully reset, we will redirect the user back to
		// the application's home authenticated view. If there is an error we can
		// redirect them back to where they came from with their error message.
		return $response == Password::PASSWORD_RESET
			? $this->sendResetResponse($response)
			: $this->sendResetFailedResponse($request, $response);
	}
	
	protected function resetPassword($user, $password)
	{
		$user->forceFill([
			'password'       => md5($password . $user['bba_token']),
			'remember_token' => Str::random(60),
		])->save();
		
		$this->guard()->login($user);
	}
}
