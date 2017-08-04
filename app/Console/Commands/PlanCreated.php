<?php

namespace App\Console\Commands;

use App\Http\Repositories\MembersRepository;
use App\Http\Repositories\PlanRepository;
use App\Mail\clickperfectPlanUpgrade;
use App\Mail\notMappedToClickPerfect;
use App\Models\PaykickstartTransactionDetails;
use App\User;
use App\UserPlans;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PlanCreated extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Payment:CreateUser';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';
	
	protected $payKick;
	protected $membersRepo;
	protected $planRepo;
	
	public function __construct(PaykickstartTransactionDetails $paykickstartTransactionDetails, MembersRepository $membersRepository, PlanRepository $planRepository)
	{
		parent::__construct();
		
		$this->payKick = $paykickstartTransactionDetails;
		
		$this->membersRepo = $membersRepository;
		
		$this->planRepo = $planRepository;
	}
	
	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$pays = $this->payKick
			->where('transaction_time', '>=', '1499904000')
			->where('status', '=', 'Success')
			->where('event', '=', 'subscription-payment')
			->groupBy('buyer_email')
			->get();
		if ( count($pays) > '0' ) {
			foreach ( $pays as $pay ) {
				$pp = $this->payKick
					->whereRaw('buyer_email = ? AND transaction_time >= "1499904000" AND ( (event = "subscription-payment-failed") OR (event = "subscription-cancelled")) ', [ $pay->buyer_email ])
					->count();
				if ( $pp > 0 ) {
					$this->payKick
						->where('buyer_email', '=', $pay->buyer_email)
						->where('transaction_id', '=', $pay->transaction_id)
						->where('transaction_time', '>=', '1499904000')
						->update([ 'status' => 'Failed' ]);
					$mail_data = [
						'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
						'body_message' => 'Your ' . $pay->product_name . ' Billing Failed.'
					];
					Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
				} else {
					$plan_details = $this->planRepo->gettingPlanByProductId($pay->product_id);
					if ( count($plan_details) == 0 ) {
						$this->payKick
							->where('buyer_email', '=', $pay->buyer_email)
							->where('transaction_id', '=', $pay->transaction_id)
							->where('transaction_time', '>=', '1499904000')
							->update([ 'status' => 'Failed' ]);
						$mail_data = [
							'user_name'  => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
							'product_id' => $pay->product_id
						];
						Mail::to($pay->buyer_email)->send(new notMappedToClickPerfect($mail_data));
					} else {
						$checkUserDetail = User::where('email', '=', $pay->buyer_email)->first();
						if ( count($checkUserDetail) == 0 ) {
							$input_array = [
								'payment_method'    => 'paykickstart-ipn',
								'subscribe_id'      => '',
								'first_name'        => $pay->buyer_first_name,
								'last_name'         => $pay->buyer_last_name,
								'email'             => $pay->buyer_email,
								'group_code'        => '2',
								'verification_code' => $pay->verification_code,
								'invoice_id'        => $pay->invoice_id,
								'signup_ip'         => $pay->buyer_ip,
							];
							
							$this->membersRepo->addNewUserByIpn($input_array, $plan_details);
							
							$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
								->where('transaction_time', '>=', '1499904000')
								->update([ 'status' => 'Pending' ]);
						} else {
							$userPlanExpire = checkUserPlanExpire($checkUserDetail->current_plan);
							if ( $checkUserDetail->current_plan == '0' ) {
								$mail_data = [
									'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
									'body_message' => 'Your ' . $pay->product_name . ' User Successfully Upgraded!'
								];
								Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
								
								$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
									->where('transaction_time', '>=', '1499904000')
									->update([ 'status' => 'UserActive' ]);
								
								$pay = (array) $pay;
								$this->membersRepo->planUpgradePaykickstart($checkUserDetail->id, $pay, $plan_details);
							} else {
								if ( count($userPlanExpire) == 0 ) {
									$userPlanInvoice = checkUserPlanInvoice($pay->invoice_id);
									if ( count($userPlanInvoice) > 0 ) {
										if ( $userPlanInvoice->user_banned == 1 ) {
											$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
												->where('transaction_time', '>=', '1499904000')
												->update([ 'status' => 'Failed' ]);
											
											$mail_data = [
												'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
												'body_message' => 'Your ' . $pay->product_name . ' User Banned'
											];
											Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
										} else {
											$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
												->where('transaction_time', '>=', '1499904000')
												->update([ 'status' => 'UserActive' ]);
											$this->membersRepo->planRenewalPaykickstart($userPlanInvoice);
											$mail_data = [
												'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
												'body_message' => 'Your ' . $pay->product_name . ' User Successfully Upgraded!'
											];
											Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
										}
									} else {
										$mail_data = [
											'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
											'body_message' => 'Your ' . $pay->product_name . ' User Successfully Upgraded!'
										];
										Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
										
										$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
											->where('transaction_time', '>=', '1499904000')
											->update([ 'status' => 'UserActive' ]);
										
										$pay = (array) $pay;
										$this->membersRepo->planUpgradePaykickstart($checkUserDetail->id, $pay, $plan_details);
									}
								} else {
									if ( $userPlanExpire->user_banned == '1' ) {
										$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
											->where('transaction_time', '>=', '1499904000')
											->update([ 'status' => 'Banned' ]);
										
										$mail_data = [
											'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
											'body_message' => 'Your ' . $pay->product_name . ' Cancelled'
										];
										Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
									} else {
										$this->payKick->whereRaw('transaction_id = ? AND event = ? ', [ $pay->transaction_id, 'subscription-payment' ])
											->where('transaction_time', '>=', '1499904000')
											->update([ 'status' => 'UserActive' ]);
										$mail_data = [
											'user_name'    => ucfirst($pay->buyer_first_name . ' ' . $pay->buyer_last_name),
											'body_message' => 'Your ' . $pay->product_name . ' User Successfully Upgraded!'
										];
										Mail::to($pay->buyer_email)->send(new clickperfectPlanUpgrade($mail_data));
										
										$this->membersRepo->planRenewalPaykickstart($userPlanExpire);
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
