<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 6:28 AM
 */

namespace App\Http\Repositories;

use App\Facades\Tenant;
use App\Mail\activateClickPerfectAccount;
use App\Mail\welcomeToClickPerfect;
use App\Mail\welcomeToClickPerfectPurchase;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MembersRepository extends Repository
{
	public function model()
	{
		return app(User::class);
	}
	
	/**
	 * Get Members
	 *
	 * @param $request
	 * @param $userPlans
	 *
	 * @return array
	 */
	public function getMembers($request, $userPlans)
	{
		$members = $this->model()
			->select('users.*', 'groups.name as group_name', 'groups.id as group_id',
				'user_plans.plan_id', 'user_plans.free_pack', 'user_plans.duration', 'user_plans.duration_schedule', 'user_plans.email_limit', 'user_plans.auto_renewal',
				'user_plans.expiry_on', 'user_plans.activated_on', 'user_plans.email_status', 'user_plans.payment_method', 'user_plans.status', 'user_plans.free_flag',
				DB::raw('IF(user_plans.expiry_on > NOW(), 0, 1) AS expired'))
			->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
			->leftjoin('groups', 'users_groups.group_id', '=', 'groups.id')
			->leftjoin('user_plans', 'users.current_plan', '=', 'user_plans.id')
			->where('users_groups.group_id', '!=', '1');
		if ( isset($request->name) ) {
			$name_arr = explode(" ", $request->name);
			if ( count($name_arr) > 0 ) {
				foreach ( $name_arr AS $names ) {
					$members = $members->whereRaw("(users.first_name LIKE '%{$names}%' OR users.last_name LIKE '%{$names}%')");
				}
			}
		}
		if ( isset($request->email) ) {
			$members = $members->where('users.email', '=', $request->email);
		}
		if ( isset($request->domain) ) {
			$members = $members->where('users.domain', 'like', '%' . $request->domain . '%');
		}
		if ( isset($request->plan) ) {
			$userPlans  = $userPlans->where('plan_id', '=', $request->plan)->get();
			$planQryAry = [ 1 ];
			if ( sizeof($userPlans) > 0 ) {
				$planQryAry = [];
				foreach ( $userPlans as $userP ) {
					$planQryAry[] = $userP->id;
				}
			}
			$members = $members->whereIn('users.current_plan', $planQryAry);
		}
		if ( isset($request->activated) ) {
			$members = $members->where('users.activated', '=', $request->activated);
		}
		if ( isset($request->group_name) ) {
			$members = $members->where('users_groups.group_id', '=', $request->group_name);
		}
		if ( isset($request->user_banned) ) {
			$members = $members->where('users.user_banned', '=', $request->user_banned);
		}
		if ( isset($request->plan_status) ) {
			$userPlans  = $userPlans->where('status', '=', $request->plan_status)->get();
			$planQryAry = [ 1 ];
			if ( sizeof($userPlans) > 0 ) {
				$planQryAry = [];
				foreach ( $userPlans as $userP ) {
					if ( $request->plan_status == 'Active' ) {
						if ( date('Y-m-d') > date('Y-m-d', strtotime($userP->expiry_on)) ) {
							continue;
						}
					}
					$pRr = $userPlans->where('user_id', '=', $userP->user_id)
						->where('id', '>', $userP->id)
						->where('status', '<>', $request->plan_status)
						->first();
					if ( count($pRr) > 0 ) {
						if ( $request->plan_status == 'Expired' ) {
							if ( date('Y-m-d') <= date('Y-m-d', strtotime($pRr['expiry_on'])) ) {
								continue;
							}
						} else {
							continue;
						}
					}
					$planQryAry[] = $userP->user_id;
				}
			}
			$members = $members->whereIn('users.id', $planQryAry);
		}
		$id = $request->route()->parameter('member');
		if ( isset($id) ) {
			$members = $members->where('users.id', '=', $id)->first();
		} else {
			$members = $members->orderBy('users.id', 'desc')->paginate(10);
		}
		
		return $members;
	}
	
	public function getPlan($flag = '')
	{
		if ( !isset($flag) || is_null($flag) || !$flag || $flag == '' ) {
			$plans = DB::table('plans')->where('free_plan', '=', '1')->where('status', '=', 'Active')->first();
		} else {
			$plans = DB::table('plans')->where('plan_code', '=', $flag)->first();
		}
		
		return $plans;
	}
	
	/**
	 * Create Member
	 *
	 * @param $request
	 * @param $userPlans
	 *
	 * @return bool
	 */
	public function create($request, $userPlans)
	{
		$emailCheck = $this->model()->whereRaw('email = ?', [ $request->email ])->count();
		if ( $emailCheck > 0 ) {
			$request->session()->flash('error', 'Email address already exist.');
			
			return false;
		}
		
		$domainCheck = $this->model()->whereRaw('domain = ?', [ $request->domain ])->count();
		if ( $domainCheck > 0 ) {
			$request->session()->flash('error', 'Domain name already exist.');
			
			return false;
		}
		
		$bba_token = str_random(8);
		$user_id   = $this->model()->insertGetId([
			'email'       => $request->email,
			'bba_token'   => $bba_token,
			'password'    => md5($request->password . $bba_token),
			'first_name'  => $request->first_name,
			'last_name'   => $request->last_name,
			'domain'      => strtolower($request->domain),
			'company'     => is_null($request->company) ? '' : $request->company,
			'address'     => is_null($request->address) ? '' : $request->address,
			'city'        => is_null($request->city) ? '' : $request->city,
			'postal_code' => is_null($request->postal_code) ? '' : $request->postal_code,
			'country'     => is_null($request->country) ? '' : $request->country,
			'state_code'  => is_null($request->state_code) ? '' : $request->state_code,
			'phone'       => is_null($request->phone) ? '' : $request->phone,
			'signup_ip'   => $request->ip(),
			'activated'   => '1',
			'reputation'  => is_null($request->reputation) ? '' : $request->reputation
		]);
		
		DB::table('users_groups')->insert([
			'user_id'  => $user_id,
			'group_id' => $request['group_id']
		]);
		
		DB::table('throttle')->insert([ 'user_id' => $user_id ]);
		
		$plans     = $this->getPlan($request->plan_flag);
		$user_info = $this->model()->find($user_id);
		if ( sizeof($plans) > 0 ) {
			$this->insertUserPlan($plans, $userPlans, $user_info);
		}
		
		$dbName = config('site.db_prefix') . strtolower($request->domain);
		if ( !checkIsDBExist($dbName) ) {
			DB::statement(DB::raw("CREATE DATABASE {$dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;"));
			Tenant::setDb($user_info['domain']);
			config([ 'database.connections.mysql_tenant.database' => $dbName ]);
			Artisan::call('migrate', [
				'--database' => 'mysql_tenant', '--path' => 'database/migrations/tenant'
			]);
			DB::setDefaultConnection('mysql');
		}
		
		$request->session()->flash('success', 'User account created successfully.');
		Mail::to($user_info->email)->send(new welcomeToClickPerfectPurchase($user_info, $request->password));
		
		return true;
	}
	
	/**
	 * Members Update.
	 *
	 * @param $request
	 * @param $userPlans
	 *
	 * @return bool
	 */
	public function update($request, $userPlans)
	{
		$id = $request->route()->parameter('member');
		
		$user_info = $this->model()->find($id);
		
		if ( $request->status == 'activate' ) {
			$user_info->activated = 1;
			$user_info->save();
			
			$dbName = config('site.db_prefix') . strtolower($user_info['domain']);
			if ( !checkIsDBExist($dbName) ) {
				
				DB::statement(DB::raw("CREATE DATABASE {$dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;"));
				Tenant::setDb($user_info['domain']);
				config([ 'database.connections.mysql_tenant.database' => $dbName ]);
				Artisan::call('migrate', [
					'--database' => 'mysql_tenant', '--path' => 'database/migrations/tenant'
				]);
				DB::setDefaultConnection('mysql');
			}
			
			$message = trans('admin/manageMember.status_updated_successfully');
			Mail::to($user_info->email)->send(new welcomeToClickPerfect($user_info));
		} elseif ( $request->status == 'ban' ) {
			
			$user_info->user_banned      = 1;
			$user_info->user_banned_date = DB::raw('NOW()');
			$user_info->save();
			
			$message = trans('admin/manageMember.ban_status_updated_successfully');
		} elseif ( $request->status == 'unban' ) {
			
			$user_info->user_banned = 0;
			$user_info->save();
			
			$message = trans('admin/manageMember.unban_status_updated_successfully');
		} elseif ( $request->status == 'addMonth' ) {
			if ( $user_info->current_plan ) {
				
				$user_plan = $userPlans->find($user_info->current_plan);
				
				$user_plan->purchased_date = date('Y-m-d H:i:s');
				$user_plan->activated_on   = date('Y-m-d H:i:s');
				$user_plan->date_added     = date('Y-m-d H:i:s');
				$user_plan->expiry_on      = date('Y-m-d H:i:s', strtotime("+1 months", strtotime($user_plan->expiry_on)));
				$user_plan->save();
				
				$message = 'One month plan added successfully';
			}
		} elseif ( $request->status == 'resend' ) {
			
			$PaykickstartTransactionDetails = \App\Models\PaykickstartTransactionDetails::whereRaw('buyer_email = ? AND event = ? AND status = ?', [
				$user_info->email, 'subscription-payment', 'Pending' ])->select('verification_code')->first();
			
			if ( isset($PaykickstartTransactionDetails) && count($PaykickstartTransactionDetails) > 0 ) {
				Mail::to($user_info->email)->send(new activateClickPerfectAccount($user_info, $PaykickstartTransactionDetails->verification_code));
			}
			
			$message = 'Resent activation code successfully';
		} elseif ( $request->status == 'edit_member' ) {
			$emailCheck = $this->model()->whereRaw('email = ? AND id != ?', [ $request->email, $id ])->count();
			if ( $emailCheck == 0 ) {
				
				$user_info->reputation      = $request->reputation;
				$user_info->email           = $request->email;
				$user_info->first_name      = $request->first_name;
				$user_info->last_name       = $request->last_name;
				$user_info->tracking_domain = $request->tracking_domain;
				$user_info->company         = $request->company;
				$user_info->address         = $request->address;
				$user_info->city            = $request->city;
				$user_info->postal_code     = $request->postal_code;
				$user_info->country         = $request->country;
				$user_info->state_code      = $request->state_code;
				$user_info->phone           = $request->phone;
				$user_info->save();
				
				$group_exists = DB::table('users_groups')->whereRaw('user_id = ?', [ $id ])->count();
				if ( $group_exists > 0 ) {
					DB::table('users_groups')->whereRaw('user_id = ?', [ $id ])->update([ 'group_id' => $request->group_id ]);
				} else {
					DB::table('users_groups')->insert([ 'user_id' => $id, 'group_id' => $request->group_id ]);
				}
				
				if ( $user_info->current_plan ) {
					$user_plan = $userPlans->find($user_info->current_plan);
					if ( sizeof($user_plan) ) {
						if ( $request->plan_id != $user_plan->plan_id ) {
							$plans = DB::table('plans')->where('plan_id', '=', $request->plan_id)->first();
							if ( sizeof($plans) > 0 ) {
								if ( $plans->plan_type != 2 && $plans->free_plan != 1 ) {
									if ( $user_info->vault_key == '' ) {
										$message = trans('auth.register.card_not_provided');
										$request->session()->flash('error', $message);
										
										return false;
									}
								}
								
								$expiry_date = "INTERVAL " . $plans->duration . " " . strtoupper($plans->duration_schedule);
								
								$user_plan->plan_id           = $request->plan_id;
								$user_plan->amount            = $plans->amount;
								$user_plan->duration          = $plans->duration;
								$user_plan->duration_schedule = $plans->duration_schedule;
								$user_plan->currency_code     = $plans->currency_code;
								$user_plan->auto_renewal      = 2;
								$user_plan->email_limit       = $plans->email_limit;
								if ( $plans->free_plan == 1 ) {
									$user_plan->free_pack = 1;
								} else {
									$user_plan->free_pack = 0;
								}
								$user_plan->payment_status = 'success';
								$user_plan->status         = 'Active';
								$user_plan->activated_on   = DB::raw('NOW()');
								$user_plan->expiry_on      = DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')');
								$user_plan->purchased_date = DB::raw('NOW()');
								$user_plan->attempt_count  = 1;
								$user_plan->attempt_date   = DB::raw('NOW()');
								$user_plan->save();
							} else {
								$message = trans('auth.register.invalid_pricing');
								$request->session()->flash('error', $message);
								
								return false;
							}
						}
					} else {
						$plans = DB::table('plans')->where('plan_id', '=', $request->plan_id)->first();
						if ( sizeof($plans) > 0 ) {
							$this->insertUserPlan($plans, $userPlans, $user_info);
						} else {
							$message = trans('auth.register.invalid_pricing');
							$request->session()->flash('error', $message);
							
							return false;
						}
					}
				} else {
					$plans = DB::table('plans')->where('plan_id', '=', $request->plan_id)->first();
					if ( sizeof($plans) > 0 ) {
						$this->insertUserPlan($plans, $userPlans, $user_info);
					} else {
						$message = trans('auth.register.invalid_pricing');
						$request->session()->flash('error', $message);
						
						return false;
					}
				}
			} else {
				$message = trans('auth.register.email_exist');
				$request->session()->flash('error', $message);
				
				return false;
			}
			$message = trans('admin/manageMember.user_updated_successfully');
		} elseif ( $request->status == 'update_password' ) {
			
			$user_info->password = md5($request->new_password . $user_info->bba_token);
			$user_info->save();
			
			$message = 'Password updated successfully';
		} elseif ( $request->status == 'cancel_subscription' ) {
			if ( $user_info->current_plan ) {
				
				$user_plan         = $userPlans->find($user_info->current_plan);
				$user_plan->status = 'Cancelled';
				$user_plan->save();
				
				$user_info->current_plan = 0;
				$user_info->save();
			}
			$message = trans('Subscription cancelled successfully.');
		} else {
			$message = trans('admin/manageMember.status_updated_successfully');
		}
		
		$request->session()->flash('success', $message);
		
		return true;
	}
	
	/**
	 * @param $plans
	 * @param $userPlans
	 * @param $user_info
	 *
	 * @return $new_plan_id;
	 */
	public function insertUserPlan($plans, $userPlans, $user_info)
	{
		$expiry_date = "INTERVAL " . $plans->duration . " " . strtoupper($plans->duration_schedule);
		$ins_data    = [
			'user_id'           => $user_info->id,
			'plan_id'           => $plans->plan_id,
			'amount'            => $plans->amount,
			'duration'          => $plans->duration,
			'duration_schedule' => $plans->duration_schedule,
			'currency_code'     => $plans->currency_code,
			'auto_renewal'      => 1,
			'email_limit'       => $plans->email_limit,
			'payment_status'    => 'success',
			'status'            => 'Active',
			'activated_on'      => DB::raw('NOW()'),
			'expiry_on'         => DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')'),
			'purchased_date'    => DB::raw('NOW()'),
			'attempt_count'     => 1,
			'attempt_date'      => DB::raw('NOW()'),
		];
		
		if ( $plans->free_plan == 1 ) {
			$ins_data['free_pack'] = 1;
		} else {
			$ins_data['free_pack'] = 0;
		}
		
		$new_plan_id = $userPlans->insertGetId($ins_data);
		
		$user_info->current_plan = $new_plan_id;
		$user_info->save();
		
		return $new_plan_id;
	}
	
	public function addNewUserByIpn($input, $plan_details)
	{
		$user_id = $this->model()->insertGetId([
			'email'       => $input['email'],
			'first_name'  => $input['first_name'],
			'last_name'   => $input['last_name'],
			'domain'      => '',
			'activated'   => '0',
			'signup_ip'   => isset($input['buyer_ip']) ? $input['buyer_ip'] : '',
			'company'     => '',
			'address'     => '',
			'city'        => '',
			'postal_code' => '',
			'country'     => '',
			'state_code'  => '',
			'phone'       => '',
			'reputation'  => ''
		]);
		
		DB::table('users_groups')->insert([
			'user_id'  => $user_id,
			'group_id' => $input['group_code']
		]);
		
		DB::table('throttle')->insert([ 'user_id' => $user_id ]);
		
		$expiry_date = "INTERVAL " . $plan_details->duration . " " . strtoupper($plan_details->duration_schedule);
		$ins_data    = [
			'user_id'           => $user_id,
			'plan_id'           => $plan_details->plan_id,
			'amount'            => $plan_details->amount,
			'duration'          => $plan_details->duration,
			'duration_schedule' => $plan_details->duration_schedule,
			'currency_code'     => $plan_details->currency_code,
			'auto_renewal'      => 2,
			'email_limit'       => $plan_details->email_limit,
			'payment_status'    => 'pending',
			'status'            => 'Pending',
			'activated_on'      => DB::raw('NOW()'),
			'expiry_on'         => DB::raw('DATE_ADD(DATE_ADD(NOW(), INTERVAL 14 DAY), ' . $expiry_date . ')'),
			'purchased_date'    => DB::raw('NOW()'),
			'date_added'        => DB::raw('NOW()'),
			'attempt_count'     => 1,
			'attempt_date'      => DB::raw('NOW()'),
			'free_pack'         => 0,
			'free_flag'         => 1,
			'payment_method'    => 'Paykickstart',
			'subscribe_code'    => isset($input['invoice_id']) ? $input['invoice_id'] : 'Paykickstart',
		];
		
		$user_plan_id = DB::table('user_plans')->insertGetId($ins_data);
		
		$transaction_details = [
			'user_plan_id'   => $user_plan_id,
			'name'           => $input['first_name'] . ' ' . $input['last_name'],
			'amount'         => $plan_details->amount,
			'currency'       => 'USD',
			'paid'           => 'paid',
			'transaction_id' => isset($input['transaction_id']) ? $input['transaction_id'] : 'Paykickstart',
			'invoice'        => isset($input['invoice_id']) ? $input['invoice_id'] : 'Paykickstart',
			'date_added'     => DB::raw('NOW()'),
			'status'         => 'Pending',
			'used_for'       => 'Plan',
		];
		
		DB::table('payment_transaction_details')->insert($transaction_details);
		
		$this->model()->whereRaw('id = ?', [ $user_id ])->update([ 'current_plan' => $user_plan_id ]);
		
		$user_info = $this->model()->find($user_id);
		
		Mail::to($user_info->email)->send(new activateClickPerfectAccount($user_info, $input['verification_code']));
	}
	
	public function planUpgradePaykickstart($user_id, $paykickstart, $plan_details)
	{
		$expiry_date = "INTERVAL " . $plan_details->duration . " " . strtoupper($plan_details->duration_schedule);
		
		$insert_user_plan = [];
		
		$insert_user_plan['user_id']           = $user_id;
		$insert_user_plan['plan_id']           = $plan_details->plan_id;
		$insert_user_plan['duration']          = $plan_details->duration;
		$insert_user_plan['duration_schedule'] = $plan_details->duration_schedule;
		$insert_user_plan['amount']            = $plan_details->amount;
		$insert_user_plan['currency_code']     = $plan_details->currency_code;
		$insert_user_plan['email_limit']       = $plan_details->email_limit;
		$insert_user_plan['free_pack']         = '0';
		$insert_user_plan['payment_status']    = 'success';
		$insert_user_plan['status']            = 'Active';
		$insert_user_plan['subscribe_code']    = isset($paykickstart['invoice_id']) ? $paykickstart['invoice_id'] : 'Paykickstart';
		$insert_user_plan['payment_method']    = 'Paykickstart';
		$insert_user_plan['activated_on']      = DB::raw('NOW()');
		$insert_user_plan['expiry_on']         = DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')');
		$insert_user_plan['purchased_date']    = DB::raw('NOW()');
		$insert_user_plan['date_added']        = DB::raw('NOW()');
		$insert_user_plan['attempt_count']     = '1';
		$insert_user_plan['attempt_date']      = DB::raw('NOW()');
		$insert_user_plan['free_flag']         = '0';
		
		$user_plan_id = DB::table('user_plans')->insertGetId($insert_user_plan);
		
		$user_updates = [];
		
		$user_updates['current_plan'] = $user_plan_id;
		
		$this->model()->whereRaw('id = ?', [ $user_id ])->update($user_updates);
		
		$transaction_details = [
			'user_plan_id'   => $user_plan_id,
			'name'           => $paykickstart['buyer_first_name'] . ' ' . $paykickstart['buyer_last_name'],
			'amount'         => $plan_details->amount,
			'currency'       => 'USD',
			'paid'           => 'paid',
			'transaction_id' => isset($paykickstart['transaction_id']) ? $paykickstart['transaction_id'] : 'Paykickstart',
			'invoice'        => isset($paykickstart['invoice_id']) ? $paykickstart['invoice_id'] : 'Paykickstart',
			'date_added'     => DB::raw('NOW()'),
			'status'         => 'Pending',
			'used_for'       => 'Plan',
		];
		
		DB::table('payment_transaction_details')->insert($transaction_details);
	}
	
	public function planRenewalPaykickstart($user_plans)
	{
		if ( sizeof($user_plans) > 0 ) {
			if ( $user_plans->user_plan_status == 'Active' ) {
				$expiry_date = "INTERVAL " . $user_plans->duration . " " . strtoupper($user_plans->duration_schedule);
				
				$update_user_plan_table['duration']          = $user_plans->duration;
				$update_user_plan_table['duration_schedule'] = $user_plans->duration_schedule;
				$update_user_plan_table['email_limit']       = $user_plans->email_limit;
				$update_user_plan_table['amount']            = $user_plans->amount;
				$update_user_plan_table['currency_code']     = $user_plans->currency_code;
				$update_user_plan_table['payment_status']    = 'success';
				$update_user_plan_table['status']            = 'Active';
				$update_user_plan_table['activated_on']      = DB::raw('NOW()');
				$update_user_plan_table['expiry_on']         = DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')');
				$update_user_plan_table['purchased_date']    = DB::raw('NOW()');
				$update_user_plan_table['date_added']        = DB::raw('NOW()');
				$update_user_plan_table['attempt_count']     = '1';
				$update_user_plan_table['free_flag']         = '0';
				$update_user_plan_table['attempt_date']      = DB::raw('NOW()');
				
				DB::table('user_plans')->whereRaw('id = ?', [ $user_plans->user_plan_id ])->update($update_user_plan_table);
			} else if ( $user_plans->user_plan_status == 'Pending' ) {
				$expiry_date                        = "INTERVAL " . $user_plans->duration . " " . strtoupper($user_plans->duration_schedule);
				$user_plan_update['activated_on']   = DB::raw('Now()');
				$user_plan_update['expiry_on']      = DB::raw('DATE_ADD(NOW(),' . $expiry_date . ')');
				$user_plan_update['payment_status'] = 'success';
				$user_plan_update['status']         = 'Active';
				$user_plan_update['free_flag']      = '0';
				
				DB::table('user_plans')->whereRaw('id = ?', [ $user_plans->user_plan_id ])->update($user_plan_update);
				
				$user_updates['current_plan'] = $user_plans->user_plan_id;
				$this->model()->whereRaw('id = ?', [ $user_plans->user_id ])->update($user_updates);
			}
		}
	}
}