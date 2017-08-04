<?php

namespace App\Console\Commands;

use App\Mail\clickperfectPlanExpired;
use App\User;
use App\UserPlans;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PlanExpired extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Payment:PlanExpired';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expired Users Plan Notification';
	
	protected $user;
	protected $userPlan;
	
	/**
	 * PlanExpired constructor.
	 *
	 * @param User      $user
	 * @param UserPlans $userPlans
	 */
	public function __construct(User $user, UserPlans $userPlans)
	{
		parent::__construct();
		
		$this->user     = $user;
		$this->userPlan = $userPlans;
	}
	
	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$user_plans = $this->userPlan
			->select('user_plans.id', 'user_plans.user_id', 'plans.plan_name', 'plans.free_plan', 'users.first_name', 'users.email')
			->join('users', 'user_plans.user_id', '=', 'users.id')
			->join('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
			->whereRaw('user_plans.status = ? AND plans.status = ? AND plans.plan_id != ? AND (user_plans.expiry_on <= DATE_SUB(NOW(), INTERVAL 7 DAY))',
				[ 'Active', 'Active', '1' ])
			->where('plans.free_plan', '=', '0')
			->take(10)
			->get();
		if ( count($user_plans) > 0 ) {
			foreach ( $user_plans as $user_plan ) {
				if ( $user_plan->free_plan == '1' ) {
					continue;
				}
				$update_data = [ 'status' => 'Expired', 'attempt_date' => DB::raw('NOW()') ];
				$this->userPlan->where('id', '=', $user_plan->id)->update($update_data);
				
				$this->user->where('id', '=', $user_plan->user_id)->update([ 'current_plan' => '0' ]);
				
				$mail_data = [];
				
				$mail_data['subject'] = 'Hi ' . $user_plan->first_name;
				
				$mail_data['body_message'] = 'Your plan ' . $user_plan->plan_name . ' has been expired.';
				
				Mail::to($user_plan->email)->send(new clickperfectPlanExpired($mail_data));
			}
		}
	}
}
