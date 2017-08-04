<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function index()
	{
		if ( auth()->check() ) {
			if ( isSupperAdmin() || isAdminUser() ) {
				return redirect('admin/members');
			} else {
				$domain = strtolower(auth()->user()->domain);
				if ( $domain == '' || is_null($domain) ) {
					return view('/errors/notfound');
				}
				
				return redirect()->to('http://' . $domain . '.' . config('site.site_domain'))->send();
			}
		} else {
			return view('index');
		}
	}
	
}
