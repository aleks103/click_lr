<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class welcomeToClickPerfect extends Mailable
{
	use Queueable, SerializesModels;

	protected $user;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Welcome to Click Perfect (Your Login information inside)')
			->markdown('vendor.notifications.email')
			->with([
				'greeting'   => 'Welcome to Click Perfect (Your Login information inside)',
				'introLines' => ['Your new account is ready! Thank you for joining ' . config('app.name')],
				'actionText' => 'Welcome ' . config('app.name'),
				'level'      => 'success',
				'actionUrl'  => getSecureRedirect() . config('site.site_domain'),
				'outroLines' => ['For any help you can contact our customer support at ' . config('general.support_email')]
			]);
	}
}
