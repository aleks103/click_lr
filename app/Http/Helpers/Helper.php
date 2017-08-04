<?php
use App\Facades\MyConfig;
use App\Facades\ProfileConfig;
use App\Http\Repositories\Accounts\LinksRepository;
use Illuminate\Support\Facades\DB;

if ( !function_exists('isActiveRoute') ) {
	function isActiveRoute($route, $output = 'active')
	{
		if ( is_array($route) ) {
			$check = 0;
			for ( $i = 0; $i < count($route); $i++ ) {
				if ( Route::currentRouteName() == $route[$i] ) {
					$check = 1;
					break;
				}
			}
			if ( $check ) {
				return $output;
			} else {
				return '';
			}
		} else {
			if ( Route::currentRouteName() == $route ) {
				return $output;
			}
		}
	}
}

if ( !function_exists('isActiveResource') ) {
	function isActiveResource($route, $except = true, $output = 'active')
	{
		$check = 0;
		
		if ( is_array($route) ) {
			for ( $i = 0; $i < count($route); $i++ ) {
				if ( $except )
					$resource = [ $route[$i], $route[$i] . '.index', $route[$i] . '.show', $route[$i] . '.edit' ];
				else
					$resource = [ $route[$i], $route[$i] . '.index', $route[$i] . '.create', $route[$i] . '.show', $route[$i] . '.edit' ];
				
				for ( $j = 0; $j < count($resource); $j++ ) {
					if ( Route::currentRouteName() == $resource[$j] ) {
						$check = 1;
						break;
					}
				}
				
				if ( $check == 1 )
					break;
			}
			
			if ( $check ) {
				return $output;
			} else {
				return '';
			}
		} else {
			if ( $except )
				$resource = [ $route, $route . '.index', $route . '.show', $route . '.edit' ];
			else
				$resource = [ $route, $route . '.index', $route . '.create', $route . '.show', $route . '.edit' ];
			
			for ( $j = 0; $j < count($resource); $j++ ) {
				if ( Route::currentRouteName() == $resource[$j] ) {
					$check = 1;
					break;
				}
			}
			
			if ( $check ) {
				return $output;
			} else {
				return '';
			}
		}
	}
}

if ( !function_exists('isSupperAdmin') ) {
	function isSupperAdmin()
	{
		$currentUser = auth()->user();
		
		$admin = MyConfig::getValue('admin_email');
		if ( $currentUser->email == $admin ) {
			return true;
		}
		
		return false;
	}
}

if ( !function_exists('isAdminUserByEmail') ) {
	function isAdminUserByEmail($userEmail)
	{
		$currentUser = DB::table('users')->where('email', '=', $userEmail)->first();
		
		$group = DB::table('users_groups')->where('user_id', '=', $currentUser->id)->first();
		
		if ( sizeof($group) > 0 && $group->group_id == '1' ) {
			return true;
		}
		
		return false;
	}
}

if ( !function_exists('isAdminUser') ) {
	function isAdminUser()
	{
		$currentUser = auth()->user();
		
		$group = DB::table('users_groups')->where('user_id', '=', $currentUser->id)->first();
		
		if ( sizeof($group) > 0 && $group->group_id == '1' ) {
			return true;
		}
		
		return false;
	}
}

if ( !function_exists('getConfig') ) {
	function getConfig($config_key)
	{
		return MyConfig::getValue($config_key);
	}
}

if ( !function_exists('userNameDisplay') ) {
	function userNameDisplay($first_name, $last_name, $flag = 'full')
	{
		return $flag == 'full' ? ($first_name . ' ' . $last_name) : $first_name;
	}
}

if ( !function_exists('checkIsDBExist') ) {
	function checkIsDBExist($db_name)
	{
		// Check that the db exists, abort if not
		$res = DB::select("show databases like '{$db_name}'");
		if ( count($res) == 0 ) {
			return false;
		}
		
		return true;
	}
}

if ( !function_exists('getSecureRedirect') ) {
	function getSecureRedirect()
	{
		$server_protocol = 'http://';
		if ( Request::secure() ) {
			$server_protocol = 'https://';
		}
		
		return $server_protocol;
	}
}

if ( !function_exists('getUserPlan') ) {
	function getUserPlan()
	{
		$currentUser = auth()->user();
		
		$userPlans = DB::table('user_plans')->find($currentUser->current_plan);
		if ( $userPlans ) {
			$plan = DB::table('plans')->where('plan_id', '=', $userPlans->plan_id)->first();
			
			return $plan->plan_name;
		} else {
			return 'No Plan';
		}
	}
}

if ( !function_exists('getLRStatusByUser') ) {
	function getLRStatusByUser()
	{
		$currentUser = auth()->user();
		
		return ProfileConfig::getLRStatusByUserId($currentUser->id);
	}
}

if ( !function_exists('DeviceId') ) {
	function DeviceId()
	{
		return DB::table('tracker_devices')->whereRaw('is_mobile = ?', [ '1' ])->pluck('id')->toArray();
	}
}

if ( !function_exists('getRefererName') ) {
	function getRefererName($referer_id)
	{
		$referer_name     = "-";
		$tracker_referers = DB::table('tracker_referers')->where('id', '=', $referer_id)->first();
		
		if ( count($tracker_referers) > 0 )
			$referer_name = $tracker_referers->url;
		
		return $referer_name;
	}
}

if ( !function_exists('getCurrentBrowser') ) {
	function getCurrentBrowser($agent_id)
	{
		$browser = "-";
		
		$browser_type = DB::table('tracker_agents')->select('name', 'browser')->where('id', '=', $agent_id)->first();
		if ( count($browser_type) > 0 )
			$browser = $browser_type->browser;
		
		return $browser;
	}
}

if ( !function_exists('getCurrentPlatform') ) {
	function getCurrentPlatform($device_id)
	{
		$platform = "-";
		
		$platform_type = DB::table('tracker_devices')->select('platform', 'kind')->where('id', '=', $device_id)->first();
		if ( count($platform_type) > 0 )
			$platform = $platform_type->platform;
		
		return $platform;
	}
}

if ( !function_exists('getCurrentCountry') ) {
	function getCurrentCountry($geoip_id)
	{
		$country_code    = "";
		$country_details = DB::table('tracker_geoip')->select('country_code', 'country_code3', 'country_name')->where('id', '=', $geoip_id)->first();
		if ( count($country_details) > 0 )
			$country_code = $country_details->country_code;
		
		return $country_code;
	}
}

if ( !function_exists('remoteFileExists') ) {
	function remoteFileExists($domain)
	{
		$skipDomain = config('general.skip_domains');
		
		$parse = parse_url($domain);
		
		$skipUrl = $parse['scheme'] . '://' . $parse['host'];
		if ( in_array($skipUrl, $skipDomain) ) {
			return true;
		}
		
		$curlInit = curl_init($domain);
		curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curlInit, CURLOPT_HEADER, true);
		curl_setopt($curlInit, CURLOPT_NOBODY, true);
		curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($curlInit, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curlInit, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlInit, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");
		
		$response = curl_exec($curlInit);
		
		curl_close($curlInit);
		
		if ( $response ) return true;
		
		return false;
	}
}

if ( !function_exists('xFrameOption') ) {
	function xFrameOption($url)
	{
		$skipDomain = config('general.skip_domains');
		$parse      = parse_url($url);
		$skipUrl    = $parse['scheme'] . '://' . $parse['host'];
		if ( in_array($skipUrl, $skipDomain) ) {
			return true;
		}
		
		try {
			$header = get_headers($url, 1);
		} catch ( Exception $exception ) {
			$cloak = true;
		}
		
		$cloak = true;
		if ( isset($header["X-Frame-Options"]) ) {
			if ( is_array($header["X-Frame-Options"]) ) {
				$iFrameOption = "notFound";
				
				foreach ( $header["X-Frame-Options"] as $xFrame ) {
					if ( strtoupper($xFrame) == "DENY" || strtoupper($xFrame) == 'SAMEORIGIN' || strtoupper($xFrame) == 'ALLOW-FROM' )
						$iFrameOption = "Found";
				}
				
				if ( $iFrameOption != "notFound" ) {
					$cloak = false;
				}
			} else if ( strtoupper($header["X-Frame-Options"]) != 'DENY' && strtoupper($header["X-Frame-Options"]) != 'SAMEORIGIN' && strtoupper($header["X-Frame-Options"]) != 'ALLOW-FROM' ) {
				$cloak = true;
			} else {
				$cloak = false;
			}
		}
		
		return $cloak;
	}
}

if ( !function_exists('checkUserClickLimit') ) {
	function checkUserClickLimit($user_id)
	{
		$plan_details = DB::table('user_plans')->whereRaw('user_id = ? AND status = ? AND activated_on <= NOW() AND expiry_on >= NOW()', [ $user_id, 'Active' ])->first();
		
		if ( sizeof($plan_details) > 0 ) {
			$plan_details = (array) $plan_details;
			
			if ( $plan_details['email_status'] == 'Yes' ) {
				return false;
			}
			
			$totalC = getTotallyClicks($plan_details['activated_on']);
			
			if ( $plan_details['email_limit'] > 0 && $totalC >= $plan_details['email_limit'] ) {
				$update_arr = [ 'status' => 'Expired', 'date_updated' => DB::raw('NOW()'), 'comments' => 'Changed Plan as Expired' ];
				
				DB::table('user_plans')->whereRaw('user_id = ?', [ $user_id ])->update($update_arr);
				
				DB::table('users')->whereRaw('id = ?', [ $user_id ])->update([ 'current_plan' => 0 ]);
				
				return false;
			} else {
				return 'success' . '~~~' . $plan_details['id'] . '~~~' . $user_id . '~~~' . $plan_details['plan_id'] . '~~~0';
			}
		} else {
			return 'No_plan';
		}
	}
}

if ( !function_exists('getProgressEmailLimit') ) {
	function getProgressEmailLimit()
	{
		$click_limit    = '';
		$free_pack      = 0;
		$click_percent  = 0;
		$total_c        = 0;
		$email_limit    = 0;
		$progress_class = 'progress-bar-primary';
		if ( auth()->check() ) {
			$curr_plan    = auth()->user()->current_plan;
			$plan_details = DB::table('user_plans')->where('id', '=', $curr_plan)->first();
			if ( sizeof($plan_details) > 0 ) {
				$plan_details = (array) $plan_details;
				
				if ( $plan_details['email_status'] == 'Yes' ) {
					return [ $click_limit, $free_pack, $click_percent, $total_c, $email_limit, $progress_class ];
				}
				
				$total_c = getTotallyClicks($plan_details['activated_on']);
				
				$free_pack   = $plan_details['free_pack'];
				$email_limit = $plan_details['email_limit'] * 1;
				if ( $email_limit > 0 ) {
					$click_limit = number_format($plan_details['email_limit']);
				}
				if ( $total_c > 0 && $email_limit > 0 ) {
					$click_percent = ($total_c / $email_limit) * 100;
				}
				if ( $click_percent < 25 ) {
					$progress_class = 'progress-bar-primary';
				} else if ( $click_percent >= 25 && $click_percent < 60 ) {
					$progress_class = 'progress-bar-success';
				} else if ( $click_percent >= 60 && $click_percent < 90 ) {
					$progress_class = 'progress-bar-warning';
				} else {
					$progress_class = 'progress-bar-danger';
				}
			}
		}
		
		return [ $click_limit, $free_pack, $click_percent, $total_c, $email_limit, $progress_class ];
	}
}

if ( !function_exists('getTotallyClicks') ) {
	function getTotallyClicks($activated_on)
	{
		if ( auth()->check() ) {
			$link_service = new LinksRepository();
			
			$totalC = $link_service->getTotalClickCount($activated_on);
			
			return $totalC;
		} else {
			return 0;
		}
	}
}

if ( !function_exists('checkUserPlanExpire') ) {
	function checkUserPlanExpire($user_plan_id)
	{
		$user_plans = DB::table('users')
			->select('users.*', 'plans.*', 'user_plans.id as user_plan_id'
				, 'user_plans.status as user_plan_status', 'user_plans.plan_id', 'user_plans.attempt_count', 'user_plans.expiry_on')
			->join('user_plans', 'users.id', '=', 'user_plans.user_id')
			->join('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
			->whereRaw('user_plans.id = ? AND ((user_plans.status = "Active") OR (user_plans.status = "Pending") OR (user_plans.status = "Expired")) AND DATE(`user_plans`.`expiry_on`) <= DATE(NOW()) ', [ $user_plan_id ])
			->first();
		
		return $user_plans;
	}
}

if ( !function_exists('checkUserPlanInvoice') ) {
	function checkUserPlanInvoice($invoice_id)
	{
		$user_plans = DB::table('users')
			->select('users.*', 'plans.*', 'user_plans.id as user_plan_id', 'user_plans.status as user_plan_status', 'user_plans.plan_id', 'user_plans.attempt_count', 'user_plans.expiry_on')
			->join('user_plans', 'users.id', '=', 'user_plans.user_id')
			->join('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
			->whereRaw('user_plans.subscribe_code = ? AND user_plans.payment_method = "Paykickstart" AND ( (user_plans.status = "Active") OR  (user_plans.status = "Pending") OR (user_plans.status = "Expired") ) ', [ $invoice_id ])
			->first();
		
		return $user_plans;
	}
}