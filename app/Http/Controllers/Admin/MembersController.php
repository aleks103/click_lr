<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\MembersRepository;
use App\Models\AdminLoginAsUser;
use App\User;
use App\UserPlans;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Http\Request;
use App\Users\CustomValidator;

class MembersController extends Controller
{
	protected $membersRepository;
	
	/**
	 * MembersController constructor.
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
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\UserPlans           $userPlans
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(UserPlans $userPlans, Request $request)
	{
		$members = $this->membersRepository->getMembers($request, $userPlans);
		
		$searchParams = [];
		
		if ( $request->has('name') ) {
			$searchParams['name'] = $request->input('name');
		}
		
		if ( $request->has('email') ) {
			$searchParams['email'] = $request->input('email');
		}
		
		if ( $request->has('domain') ) {
			$searchParams['domain'] = $request->input('domain');
		}
		
		if ( $request->has('plan') ) {
			$searchParams['plan'] = $request->input('plan');
		}
		
		if ( $request->has('activated') ) {
			$searchParams['activated'] = $request->input('activated');
		}
		
		if ( $request->has('group_name') ) {
			$searchParams['group_name'] = $request->input('group_name');
		}
		
		if ( $request->has('user_banned') ) {
			$searchParams['user_banned'] = $request->input('user_banned');
		}
		
		if ( $request->has('plan_status') ) {
			$searchParams['plan_status'] = $request->input('plan_status');
		}
		
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->input('page');
		}
		
		return view('admin.members', compact('members', 'searchParams'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$reputation_array = [ 'Trusted' => 'Trusted', 'Untrusted' => 'Untrusted' ];
		
		return view('admin.addmember', compact('reputation_array'));
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\UserPlans           $userPlans
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(UserPlans $userPlans, Request $request)
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
		
		if ( $create ) {
			return redirect('/admin/members');
		} else {
			return redirect()->back();
		}
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\User                    $user
	 * @param  Request                      $request
	 * @param  \App\Models\AdminLoginAsUser $adminLoginAsUser
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function show(User $user, Request $request, AdminLoginAsUser $adminLoginAsUser)
	{
		$id = $request->route()->parameter('member');
		
		$finger_print = '';
		if ( isset($_COOKIE['fp_id']) && $_COOKIE['fp_id'] != '' ) {
			$finger_print = $_COOKIE['fp_id'];
		}
		
		$http_val = getSecureRedirect();
		
		$user_details = $user->find($id);
		
		if ( $user_details ) {
			$admin = $adminLoginAsUser->whereRaw('admin_id = ? AND fp_id = ? AND user_id = ?', [ auth()->id(), $finger_print, $id ])->first();
			
			if ( isset($admin) ) {
				$old_user                  = $user->find($admin->user_id);
				$redirect_sub_domain_error = $http_val . $old_user->domain . '.' . config('site.site_domain');
				$adminLoginAsUser->where('admin_id', '=', auth()->id())->delete();
				
				if ( $id == $admin->user_id ) {
					return redirect($redirect_sub_domain_error);
				} else {
					$request->session()->flash('error', trans('admin/manageMember.log_out_current_user'));
					
					return redirect($redirect_sub_domain_error);
				}
			} else {
				$admin_login_user = $adminLoginAsUser->insertAutoLogin($id, $finger_print);
				
				$admin = $adminLoginAsUser->find($admin_login_user);
				if ( isset($admin) ) {
					$redirect_domain = $http_val . $user_details->domain . '.' . config('site.site_domain') . '/users/loginas/' . $admin['user_key'] . '/' . $admin['fp_id'];
					
					return redirect()->to($redirect_domain)->send();
				}
			}
		} else {
			$request->session()->flash('error', trans('auth.register.user_not_activated'));
			
			return redirect('/admin/members');
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\UserPlans $userPlans
	 * @param  Request        $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(UserPlans $userPlans, Request $request)
	{
		$members = $this->membersRepository->getMembers($request, $userPlans);
		
		$dbName = config('site.db_prefix') . strtolower($members->domain);
		if ( !checkIsDBExist($dbName) ) {
			$request->session()->flash('error', trans('auth.register.user_not_activated'));
		}
		
		$reputation_array = [ 'Trusted' => 'Trusted', 'Untrusted' => 'Untrusted' ];
		
		return view('admin.editmember', compact('members', 'reputation_array'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\User                $user
	 * @param  \App\UserPlans           $userPlans
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function update(User $user, Request $request, UserPlans $userPlans)
	{
		if ( $request->status == 'edit_member' ) {
			$this->validate($request, [
				'first_name' => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
				'last_name'  => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
				'email'      => 'required|email',
				'group_id'   => 'required',
				'plan_id'    => 'required',
			], [
				'first_name.required'         => 'The first name field is required.',
				'first_name.like_restricted'  => 'The first name cannot be included ' . config('auth.restrict_keywords_like'),
				'first_name.match_restricted' => 'The first name cannot be matched ' . config('auth.restrict_keywords_exact'),
				'last_name.like_restricted'   => 'The last name cannot be matched ' . config('auth.restrict_keywords_like'),
				'last_name.match_restricted'  => 'The last name cannot be matched ' . config('auth.restrict_keywords_exact'),
				'last_name.required'          => 'The last name field is required.',
				'group_id.required'           => 'The group name field is required.',
			]);
		}
		
		if ( $request->status == 'update_password' ) {
			$this->validate($request, [
				'new_password'     => 'required|min:6',
				'confirm_password' => 'required|min:6|same:new_password'
			]);
		}
		
		$id = $request->route()->parameter('member');
		
		if ( $user ) {
			$this->membersRepository->update($request, $userPlans);
		} else {
			$request->session()->flash('error', 'Invalid member data!');
		}
		
		if ( $request->status == 'edit_member' || $request->status == 'update_password' ) {
			return redirect('/admin/members/' . $id . '/edit');
		} else {
			return response('success');
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\User                $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user, Request $request)
	{
		$id = $request->route()->parameter('member');
		
		$user = $user->find($id);
		
		if ( $user ) {
			//Drop the database
			$db_name = config('site.db_prefix') . strtolower($user->domain);
			DB::statement(DB::raw("DROP DATABASE IF EXISTS {$db_name};"));
			
			//Delete User Image
			$img_ext = $user->img_ext;
			if ( $img_ext != "" ) {
				$f_name          = $id . '.' . $img_ext;
				$destinationPath = getConfig('users_img_path');
				$path            = public_path($destinationPath . $f_name);
				$thumb_path      = public_path($destinationPath . 'thumb/' . $f_name);
				if ( File::exists($path) )
					File::delete($path);
				if ( File::exists($thumb_path) )
					File::delete($thumb_path);
			}
			
			DB::table('users_groups')->where('user_id', '=', $id)->delete();
			
			DB::table('throttle')->where('user_id', '=', $id)->delete();
			
			$user->delete();
			
			$request->session()->flash('success', trans('admin/manageMember.user_deleted_successfully'));
		}
		
		return response('success');
	}
}
