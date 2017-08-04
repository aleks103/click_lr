<?php

namespace App\Console\Commands;

use App\Mail\clickperfectPlanGrace;
use App\UserPlans;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GraceEmails extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Payment:GraceEmails';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sending Grace Emails to Expire Users.';
	
	protected $userPlans;
	
	/**
	 * Create a new command instance.
	 *
	 * @param \App\UserPlans $userPlans
	 */
	public function __construct(UserPlans $userPlans)
	{
		parent::__construct();
		
		$this->userPlans = $userPlans;
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$grace_email_users = $this->userPlans
			->select('user_plans.expiry_on', 'users.first_name', 'users.email', 'user_plans.user_id')
			->join('users', 'user_plans.user_id', '=', 'users.id')
			->whereRaw('(user_plans.expiry_on <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)) AND (user_plans.status = ? OR user_plans.status = ?)', [ 'Active', 'Cancelled' ])
			->get();
		$subjects = [
			'',
			'Your billing has failed – please fix to avoid service interruptions!',
			'You’re account will be deactivated...',
			'Yikes, your account is about to be disabled!',
			'',
			'Your final warning - your ClickPerfect account is closing...'
		];
		
		$messages = [
			'',
			[
				'<p>Hey - it’s Jimmy over at Click Perfect and I wanted to send you a quick email.</p><p>It appears your billing for this month’s access to Click Perfect has failed.</p><p>I’m sure this is just an over-sight and so we made sure it didn’t affect your links or access.</p><p>But - it’s very important that you head to your account, click on the “billing tab” on the top right, and update your account right away.</p><p>You can log into your account here: <a href="http://clickperfect.com/login">http://clickperfect.com/login</a></p><p>Happy Click Tracking!</p>'
			],
			[
				'<p>Hey - Just another heads up...</p><p>It appears your billing FAILED for your Click Perfect account.</p><p>And I know how IMPORTANT it is to make sure your links are active and live with your online business.</p><p>We assumed it was an over-sight and so we made sure your links and account are still active even though your billing has expired.</p><p>Here’s what you need to do:</p><p><ol><li>Go ahead and log into ClickPerfect: http://clickperfect.com/login</li><li>Click on your face and name on the top right (once logged in)</li><li>Click on billing & upgrade</li><li>Click “upgrade plan” and renew your plan right away</li></ol></p><p>This will ensure that you do not lose any of the important information stored in your account nor will your online LINKS go “down.”</p><p>Thank you for your immediate attention.</p>'
			],
			[
				'<p>Hey, this is very important.</p><p>Your ClickPerfect account is officially EXPIRED.</p><p>That means all your links and access will not be available soon.</p><p>Good news is, we’ve got all your information saved and we left your links LIVE as we assumed it was simply an oversight on your part.</p><p>Here’s what you need to do:</p><p><ol><li>Go ahead and log into ClickPerfect: http://clickperfect.com/login</li><li>Click on your face and name on the top right (once logged in)</li><li>Click on billing & upgrade</li><li>Click “upgrade plan” and renew your plan right away</li><li>You’re all set and ready to get back to work!</li></ol><p>Thank you for your immediate attention.</p>'
			],
			'',
			[
				'<p>It appears that we’re going to have to CLOSE your account due to failed billing.</p><p>We’ve kept your links and access alive as long as possible.</p><p>This is your official 24 hour warning - please update your billing to avoid service interruptions.</p><p>Remember, with Click Perfect, you are tracking all your links for your online business.</p><p>Here’s what you need to do:</p><ol><li>Go ahead and log into ClickPerfect: http://clickperfect.com/login</li><li>Click on your face and name on the top right (once logged in)</li><li>Click on billing & upgrade</li><li>Click “upgrade plan” and renew your plan right away</li><li>You’re all set and ready to get back to work!</li></ol><p>Thank you for your immediate attention.</p>'
			],
		];
		
		if ( sizeof($grace_email_users) > 0 ) {
			foreach ( $grace_email_users as $grace_email_user ) {
				if ( $grace_email_user->first_name == '' || $grace_email_user->email == '' ) {
					continue;
				}
				$check_users = $this->userPlans->where('user_id', '=', $grace_email_user->user_id)
					->whereRaw('(expiry_on > DATE_ADD(CURDATE(), INTERVAL 7 DAY)) AND (status = ?)', [ 'Active' ])->count();
				if ($check_users > 0) {
					continue;
				}
				for ( $i = 1; $i <= 5; $i++ ) {
					if ( $i == 4 ) continue;
					$mail_data = [];
					if ( $this->checkDate($i, $grace_email_user->expiry_on) ) {
						$mail_data['subject']      = $subjects[$i];
						$mail_data['body_message'] = $messages[$i];
						Mail::to($grace_email_user->email)->subject($subjects[$i])->send(new clickperfectPlanGrace($mail_data));
						break;
					}
				}
			}
		}
	}
	
	private function checkDate($day, $from)
	{
		$date = strtotime($from . ' + ' . $day . ' days');
		
		$expire = date('Y-m-d', $date);
		
		$now = date('Y-m-d');
		
		return $now == $expire;
	}
}
