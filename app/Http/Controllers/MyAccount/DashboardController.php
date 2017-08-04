<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/7/2017
 * Time: 3:51 PM
 */

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginAsUser;
use App\User;
use App\Http\Repositories\Accounts\DataService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param User             $user
	 * @param Request          $request
	 * @param AdminLoginAsUser $adminLoginAsUser
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function index(User $user, AdminLoginAsUser $adminLoginAsUser, Request $request)
	{
		$subDomain = $request->route()->parameter('sub_domain');
		
		if ( auth()->check() ) {
			if ( isSupperAdmin() || isAdminUser() ) {
				
				$finger_print = '';
				
				if ( !is_null($request->session()->get('fp_id')) && $request->session()->get('fp_id') != '' ) {
					$finger_print = $request->session()->get('fp_id');
				}
				
				$redirectTo = '';
				if ( $finger_print != '' ) {
					$user_info = $user->where('domain', '=', $subDomain)->first();
					
					if ( isset($user_info) ) {
						if ( $user_info->user_banned ) {
							$request->session()->flash('error', 'User is banned.');
						} else {
							$check_admin_login = $adminLoginAsUser->where('user_id', '=', $user_info->id)
								->where('admin_id', '=', auth()->id())->where('fp_id', '=', $finger_print)->first();
							
							if ( isset($check_admin_login) ) {
								$request->session()->put('login_as_user', 'admin');
								
								auth()->login($user_info, true);
								
								$redirectTo = 'users/dashboard';
							}
						}
					}
				}
				if ( $redirectTo == '' ) {
					$request->session()->flash('error', trans('admin/manageMember.log_out_current_user'));
					
					return redirect()->to(getSecureRedirect() . config('site.site_domain'))->send();
				} else {
					$counts = DataService::getServicesCounts();
					
					return view('users/dashboard', compact('counts'));
				}
			} else {
				if ($subDomain == strtolower(auth()->user()->domain)) {
					$counts = DataService::getServicesCounts();
					
					return view('users/dashboard', compact('counts'));
				} else {
					return redirect()->to(getSecureRedirect() . strtolower(auth()->user()->domain) . '.' . config('site.site_domain'))->send();
				}
			}
		} else {
			return view('index');
		}
	}
	
	/**
	 * User Login As Admin
	 *
	 * @param User $user
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function userLoginAsAdmin(User $user)
	{
		$user_info = $user->where('email', '=', getConfig('admin_email'))->first();
		
		auth()->login($user_info, true);
		
		return redirect(getSecureRedirect() . config('site.site_domain'));
	}
	
	/**
	 * @param User             $user
	 * @param Request          $request
	 * @param AdminLoginAsUser $adminLoginAsUser
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function adminLoginAsUser(User $user, Request $request, AdminLoginAsUser $adminLoginAsUser)
	{
		$user_key = $request->route()->parameter('user_key');
		$fp_id    = $request->route()->parameter('fp_id');
		
		$request->session()->flash('fp_id', $fp_id);
		
		$check_admin_login = $adminLoginAsUser->where('user_key', '=', $user_key)->first();
		
		$refer_path = '';
		
		if ( $request->server('HTTP_REFERER') ) {
			$refer_path = $request->server('HTTP_REFERER');
		}
		
		if ( isset($check_admin_login) && strpos($refer_path, 'admin/members') !== false ) {
			$user_details = $user->findOrFail($check_admin_login->user_id);
			if ( $user_details->user_banned ) {
				$request->session()->flash('error', 'User is banned.');
				
				return redirect('/admin/members');
			}
			
			return redirect('/');
		} else {
			$request->session()->flash('error', trans('admin/manageMember.log_out_current_user'));
			
			return redirect('/admin/members');
		}
	}
	
	/**
	 * Links Graph
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboardLinksGraph(Request $request)
	{
		$date_interval = $request->input('date_interval') * 1;
		
		$interval_unit = 'DAY';
		
		if ( strtolower(auth()->user()->domain) == 'vssmind' ) {
			if ( $date_interval == 30 ) {
				$date_interval = 25;
			}
		} else if ( strtolower(auth()->user()->domain) == 'ddlm' ) {
			if ( $date_interval == 30 ) {
				$date_interval = 10;
			}
		}
		
		$GraphInformation = [
			'no_report'       => 'No Report has been found',
			'performanceName' => 'Link Group',
			'chart_width'     => 950,
		];
		
		$d_arr = [ 'link_id' => 0 ];
		
		$axisData = DataService::getXYAxisArray('links', $date_interval, $interval_unit, $d_arr);
		
		$clicks = [
			'total_click'                 => $axisData[0],
			'total_unique'                => $axisData[1],
			'total_non_unique'            => $axisData[2],
			'percentage_unique_clicks'    => $axisData[3],
			'percentage_nonunique_clicks' => $axisData[4],
			'total_pclicks'               => $axisData[5],
			'rotators_log'                => [],
			'xaxis_step'                  => (($date_interval == 7 || $date_interval == 1) ? 1 : 4)
		];
		
		$link_logs = [];
		
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->input('page');
		} else {
			$searchParams['page'] = 1;
		}
		
		return response()->view('users.reportGraph', compact('clicks', 'GraphInformation', 'link_logs', 'searchParams'));
	}
	
	/**
	 * Rotators Graph
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboardRotatorsGraph(Request $request)
	{
		$date_interval = $request->input('date_interval') * 1;
		
		$interval_unit = 'DAY';
		
		$GraphInformation = [
			'no_report'       => 'No Report has been found',
			'performanceName' => 'Rotators Group',
			'chart_width'     => 950,
		];
		
		$d_arr = [ 'rotators_id' => 0, 'rotators_url_id' => 0 ];
		
		$axisData = DataService::getXYAxisArray('rotators', $date_interval, $interval_unit, $d_arr);
		
		$clicks = [
			'total_click'                 => $axisData[0],
			'total_unique'                => $axisData[1],
			'total_non_unique'            => $axisData[2],
			'percentage_unique_clicks'    => $axisData[3],
			'percentage_nonunique_clicks' => $axisData[4],
			'total_pclicks'               => $axisData[5],
			'rotators_log'                => [],
			'xaxis_step'                  => (($date_interval == 7 || $date_interval == 1) ? 1 : 4)
		];
		
		$link_logs = [];
		
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->input('page');
		} else {
			$searchParams['page'] = 1;
		}
		
		return response()->view('users.reportGraph', compact('clicks', 'GraphInformation', 'link_logs', 'searchParams'));
	}
}