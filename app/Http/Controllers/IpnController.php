<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 7/7/2017
 * Time: 4:59 PM
 */

namespace App\Http\Controllers;

use App\Http\Repositories\MembersRepository;
use App\Http\Repositories\PlanRepository;
use App\Mail\clickperfectPlanUpgrade;
use App\Mail\notMappedToClickPerfect;
use App\Models\PaykickstartTransactionDetails;
use App\User;
use Illuminate\Support\Facades\Mail;

class IpnController extends Controller
{
	protected $planRepo;
	protected $memRepo;
	
	public function __construct(PlanRepository $planRepository, MembersRepository $membersRepository)
	{
		$this->planRepo = $planRepository;
		$this->memRepo  = $membersRepository;
	}
	
	public function IpnListener()
	{
		if ( !isset($this->planRepo) ) {
			$this->planRepo = new PlanRepository();
			$this->memRepo  = new MembersRepository();
		}
		try {
			$raw_post_data  = file_get_contents('php://input');
			$raw_post_array = explode('&', $raw_post_data);
			$myPost         = [];
			foreach ( $raw_post_array as $key_val ) {
				$key_val = explode('=', $key_val);
				if ( count($key_val) == 2 ) {
					$myPost[$key_val[0]] = urldecode($key_val[1]);
				}
			}
			
			if ( $this->is_valid_ipn($myPost, 'X0vLPM51e2BJ') ) {
				if ( count($myPost) > 0 ) {
					$myPost['payment_status'] = 'Success';
					$myPost['status']         = 'Success';
					
					$insert_data = [
						'event'             => $myPost['event'],
						'mode'              => $myPost['mode'],
						'payment_processor' => $myPost['payment_processor'],
						'amount'            => $myPost['amount'],
						'buyer_ip'          => $myPost['buyer_ip'],
						'buyer_first_name'  => $myPost['buyer_first_name'],
						'buyer_last_name'   => $myPost['buyer_last_name'],
						'buyer_email'       => $myPost['buyer_email'],
						'vendor_first_name' => $myPost['vendor_first_name'],
						'vendor_last_name'  => $myPost['vendor_last_name'],
						'vendor_email'      => $myPost['vendor_email'],
						'transaction_id'    => $myPost['transaction_id'],
						'invoice_id'        => $myPost['invoice_id'],
						'tracking_id'       => $myPost['tracking_id'],
						'transaction_time'  => $myPost['transaction_time'],
						'product_id'        => $myPost['product_id'],
						'product_name'      => $myPost['product_name'],
						'campaign_id'       => $myPost['campaign_id'],
						'campaign_name'     => $myPost['campaign_name'],
						
						'affiliate_first_name'         => $myPost['affiliate_first_name'],
						'affiliate_last_name'          => $myPost['affiliate_last_name'],
						'affiliate_email'              => $myPost['affiliate_email'],
						'affiliate_commission_amount'  => $myPost['affiliate_commission_amount'],
						'affiliate_commission_percent' => $myPost['affiliate_commission_percent'],
						
						'licenses'          => $myPost['licenses'],
						'verification_code' => $myPost['verification_code'],
						'payment_status'    => $myPost['payment_status'],
						'status'            => $myPost['status']
					];
					
					PaykickstartTransactionDetails::insert($insert_data);
					
					if ( $myPost['event'] == 'subscription-payment' || $myPost['event'] == 'sales' ) {
						$plan_details = $this->planRepo->gettingPlanByProductId($myPost['product_id']);
						if ( count($plan_details) == 0 ) {
							PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
								->update([ 'status' => 'NoPlan' ]);
							
							$mail_data = [
								'user_name'  => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
								'product_id' => $myPost['product_id']
							];
							Mail::to($myPost['buyer_email'])->send(new notMappedToClickPerfect($mail_data));
							
							return;
						}
						
						$checkUserDetail = User::where('email', '=', $myPost['buyer_email'])->first();
						if ( count($checkUserDetail) == 0 ) {
							$input_array = [
								'payment_method'    => 'paykickstart-ipn',
								'subscribe_id'      => '',
								'first_name'        => $myPost['buyer_first_name'],
								'last_name'         => $myPost['buyer_last_name'],
								'email'             => $myPost['buyer_email'],
								'group_code'        => '2',
								'verification_code' => $myPost['verification_code'],
								'invoice_id'        => $myPost['invoice_id'],
								'transaction_id'    => $myPost['transaction_id'],
								'signup_ip'         => $myPost['buyer_ip'],
							];
							
							$this->memRepo->addNewUserByIpn($input_array, $plan_details);
							
							PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
								->update([ 'status' => 'Pending' ]);
						} else if ( $myPost['amount'] > 0 ) {
							$userPlanExpire = checkUserPlanExpire($checkUserDetail->current_plan);
							if ( $checkUserDetail->current_plan == '0' ) {
								$this->memRepo->planUpgradePaykickstart($checkUserDetail->id, $myPost, $plan_details);
								
								PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
									->update([ 'status' => 'UserActive' ]);
								
								$mail_data = [
									'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
									'body_message' => 'Your ' . $myPost['product_name'] . ' User Successfully Upgraded!'
								];
								Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
							} else {
								if ( count($userPlanExpire) == 0 ) {
									$userPlanInvoice = checkUserPlanInvoice($myPost['invoice_id']);
									if ( count($userPlanInvoice) > 0 ) {
										if ( $userPlanInvoice->user_banned == 1 ) {
											PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
												->update([ 'status' => 'Failed' ]);
											
											$mail_data = [
												'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
												'body_message' => 'Your ' . $myPost['product_name'] . ' User Banned'
											];
											Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
											
											return;
										} else {
											$mail_data = [
												'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
												'body_message' => 'Your ' . $myPost['product_name'] . ' User Successfully Upgraded!'
											];
											Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
											
											PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
												->update([ 'status' => 'UserActive' ]);
											
											$this->memRepo->planRenewalPaykickstart($userPlanInvoice);
										}
									} else {
										PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
											->update([ 'status' => 'UserActive' ]);
										
										$this->memRepo->planUpgradePaykickstart($checkUserDetail->id, $myPost, $plan_details);
										
										$mail_data = [
											'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
											'body_message' => 'Your ' . $myPost['product_name'] . ' User Successfully Upgraded!'
										];
										Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
									}
								} else {
									if ( $userPlanExpire->user_banned == '1' ) {
										PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
											->update([ 'status' => 'Failed' ]);
										
										$mail_data = [
											'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
											'body_message' => 'Your ' . $myPost['product_name'] . ' User Banned'
										];
										Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
										
										return;
									} else {
										$mail_data = [
											'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
											'body_message' => 'Your ' . $myPost['product_name'] . ' User Successfully Upgraded!'
										];
										Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
										
										PaykickstartTransactionDetails::whereRaw('transaction_id = ? AND event = ? ', [ $myPost['transaction_id'], 'subscription-payment' ])
											->update([ 'status' => 'UserActive' ]);
										
										$this->memRepo->planRenewalPaykickstart($userPlanExpire);
									}
								}
							}
						}
					} else if ( $myPost['event'] == 'subscription-cancelled' ) {
						PaykickstartTransactionDetails::whereRaw('invoice_id = ? AND event = ? AND status != ?', [ $myPost['invoice_id'], 'subscription-payment', 'Success' ])
							->update([ 'status' => 'Failed' ]);
						
						User::where('email', '=', $myPost['buyer_email'])->update([ 'current_plan' => '0' ]);
						
						$mail_data = [
							'user_name'    => ucfirst($myPost['buyer_first_name'] . ' ' . $myPost['buyer_last_name']),
							'body_message' => 'Your ' . $myPost['product_name'] . ' Cancelled!'
						];
						Mail::to($myPost['buyer_email'])->send(new clickperfectPlanUpgrade($mail_data));
						
						return;
					}
				}
			}
		} catch ( \Exception $exception ) {
		
		}
	}
	
	function is_valid_ipn($data, $secret_key)
	{
		
		$paramStrArr = [];
		$paramStr    = null;
		
		foreach ( $data as $key => $value ) {
			// Ignore if it is encrypted key
			if ( $key == "verification_code" ) continue;
			if ( !$key OR !$value ) continue;
			$paramStrArr[] = (string) $value;
		}
		
		ksort($paramStrArr, SORT_STRING);
		$paramStr = implode("|", $paramStrArr);
		$encKey   = hash_hmac('sha1', $paramStr, $secret_key);
		
		return $encKey == $data["verification_code"];
	}
}