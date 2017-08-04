<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/22/2017
 * Time: 7:01 PM
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class clickperfectPlanGrace extends Mailable
{
	use Queueable, SerializesModels;

	protected $mail_data;
	/**
	 * Create a new message instance.
	 *
	 * @param $mail_data
	 */
	public function __construct($mail_data)
	{
		$this->mail_data = $mail_data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->markdown('vendor.notifications.grace')
			->with([
				'greeting'   => $this->mail_data['subject'],
				'introLines' => $this->mail_data['body_message'],
				'actionText' => config('app.name'),
				'actionUrl'  => getSecureRedirect() . config('site.site_domain') . '/login',
				'outroLines' => ['For any help you can contact our customer support at ' . config('general.support_email')]
			]);
	}
}