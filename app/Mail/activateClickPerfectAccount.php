<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class activateClickPerfectAccount extends Mailable
{
	use Queueable, SerializesModels;

	protected $user;
	protected $verification_code;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 * @param string $verification_code
	 */
	public function __construct(User $user, $verification_code)
	{
		$this->user = $user;
		$this->verification_code = $verification_code;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Welcome to Click Perfect (Log In Information Inside)')
			->markdown('vendor.notifications.email')
			->with([
				'greeting'   => 'Welcome to Click Perfect (Log In Information Inside)',
				'introLines' => [trans('newsletter.your_plan_welcome_thank') . '<br>', trans('newsletter.your_plan_welcome_need')],
				'actionText' => 'Activate ' . config('app.name'),
				'level'      => 'success',
				'actionUrl'  => getSecureRedirect() . config('site.site_domain') . '/register?vc=' . $this->verification_code,
				'outroLines' => [
					'Inside you\'ll be asked to confirm your name,email, and pick your sub-domain for tracking.',
					'That sub-domain is just your unique identifier: We recommend your name, a business name, or product name.',
					'If you have questions while using ClickPerfect, please visit our http://support.clickperfect.com - If you don\'t find the answer you are seeking, our Support Team is available 9am-5pm PST, and would be happy to assist!',
					'Thanks again for joining! We look forward to having you as a member for many years ahead.'
				]
			]);
	}
}
