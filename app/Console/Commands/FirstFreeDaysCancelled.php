<?php

namespace App\Console\Commands;

use App\Mail\clickperfectPlanExpired;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FirstFreeDaysCancelled extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Payment:FirstFreeDaysCancelled';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'First Free Days Cancel';
	
	protected $user;
	
	/**
	 * FirstFreeDaysCancelled constructor.
	 *
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		parent::__construct();
		
		$this->user = $user;
	}
	
	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$user_plans = DB::table('user_plans')
			->select('user_plans.id', 'user_plans.user_id', 'user_plans.plan_id', 'user_plans.amount', 'plans.free_plan', 'plans.plan_name', 'users.email', 'users.first_name')
			->join('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
			->join('users', 'user_plans.user_id', '=', 'users.id')
			->whereRaw('user_plans.status = ? AND plans.status = ? AND user_plans.free_flag = ? AND user_plans.purchased_date <= DATE_SUB(NOW(), INTERVAL 17 DAY)', [ 'Active', 'Active', '1' ])
			->get();
		
		if ( count($user_plans) > 0 ) {
			foreach ( $user_plans as $user_plan ) {
				if ( $user_plan->free_plan != '1' && ($user_plan->amount == '0' || !$user_plan->amount) ) {
					$update_arr = [ 'status' => 'Expired', 'date_updated' => DB::raw('NOW()'), 'comments' => 'Changed Plan as Expired', 'free_flag' => '0' ];
					DB::table('user_plans')->whereRaw('id = ?', [ $user_plan->id ])->update($update_arr);
					
					$this->user->whereRaw('id = ?', [ $user_plan->user_id ])->update([ 'current_plan' => '0' ]);
					
					$mail_data = [];
					
					$mail_data['subject'] = 'Hi ' . $user_plan->first_name;
					
					$mail_data['body_message'] = 'Your plan ' . $user_plan->plan_name . ' has been expired.';
					
					Mail::to($user_plan->email)->send(new clickperfectPlanExpired($mail_data));
				}
			}
		}
	}
}