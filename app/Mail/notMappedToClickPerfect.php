<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class notMappedToClickPerfect extends Mailable
{
	use Queueable, SerializesModels;

	protected $user;

	/**
	 * Create a new message instance.
	 *
	 * @param $user
	 */
	public function __construct($user)
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
		return $this->markdown('vendor.notifications.email')
			->with([
				'greeting'   => 'Hey ' . $this->user['user_name'],
				'introLines' => ['Your purchase has been cancelled. ' . config('app.name') . '(' . $this->user['product_id'] . ') Not Mapped to ' . config('app.name')],
				'actionText' => 'Your Plan Not Mapped to ' . config('app.name'),
				'level'      => 'error',
				'actionUrl'  => getSecureRedirect() . config('site.site_domain'),
				'outroLines' => ['For any help you can contact our customer support at ' . config('general.support_email')]
			]);
	}
}
