<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/22/2017
 * Time: 7:01 PM
 */

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class welcomeToClickPerfectPurchase extends Mailable
{
	use Queueable, SerializesModels;

	protected $user;
	protected $password;

	/**
	 * Create a new message instance.
	 *
	 * @param User $user
	 * @param $password
	 */
	public function __construct(User $user, $password)
	{
		$this->user = $user;
		$this->password = $password;
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
				'greeting'   => 'Hi ' . $this->user->first_name,
				'introLines' => [
					'Your new account is ready! Thank you for joining ' . config('app.name') . '.<br> Your Login Email is " ' . htmlspecialchars($this->user->email) . ' " and Password is " ' . htmlspecialchars($this->password) . ' "'
				],
				'actionText' => 'Welcome ' . config('app.name'),
				'level'      => 'success',
				'actionUrl'  => getSecureRedirect() . $this->user->domain . '.' . config('site.site_domain'),
				'outroLines' => ['For any help you can contact our customer support at ' . config('general.support_email')]
			]);
	}
}