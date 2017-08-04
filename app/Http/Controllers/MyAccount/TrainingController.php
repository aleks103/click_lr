<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/20/2017
 * Time: 7:00 PM
 */

namespace App\Http\Controllers\MyAccount;


use App\Http\Controllers\Controller;

class TrainingController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		return view('users.training');
	}
}