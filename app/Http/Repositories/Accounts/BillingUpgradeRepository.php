<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
//use App\Models\Popbars;
use App\Models\PaykickstartTransactionDetails;
use App\Models\PaymentTransactionDetail;
use App\Plan;
use App\UserPlans;

class BillingUpgradeRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		//return app(Popbars::class);
	}
	public function userplans_model(){
        return app(UserPlans::class);
    }

    public function plans_model(){
        return app(Plan::class);
    }

    public function payment_transaction_model(){
        return app(PaymentTransactionDetail::class);
    }

    public function paykickstart_transaction_model(){
        return app(PaykickstartTransactionDetails::class);
    }

    public function getUserPaymentDetails(){
        $logged_user_id = auth()->id();
        $user_payment_qry = $this->userplans_model()
            ->select('user_plans.id', 'user_plans.user_id', 'user_plans.plan_id', 'user_plans.auto_renewal', 'user_plans.purchased_date', 'user_plans.payment_status', 'user_plans.activated_on'
            , 'user_plans.expiry_on', 'user_plans.amount', 'user_plans.plan_id', 'user_plans.status as user_plan_status'
            , 'user_plans.email_status', 'user_plans.duration', 'user_plans.duration_schedule'
            , 'user_plans.email_limit', 'user_plans.free_pack'
            , 'plans.plan_name', 'plans.plan_code', 'plans.plan_type', 'plans.amount as plan_amount', 'plans.currency_code as plan_currency'
            , 'plans.duration as plan_duration', 'plans.duration_schedule as plan_duration_schedult'
            , 'plans.subscriber_limit as plan_subscriber_limit', 'plans.email_limit as plan_email_limit'
            , 'plans.email_unlimited', 'plans.addons_emails as plan_addons_email', 'plans.addons_amount as plan_addon_amount'
            , 'plans.description as plan_description', 'plans.status as plan_status', 'user_plans.date_added', 'user_plans.activated_on'
            , 'plans.trial', 'plans.next_plan', 'plans.new_flag', 'plans.plan_level')
            ->leftjoin('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
            ->whereRaw('user_plans.user_id = ? AND plans.status = ? AND (user_plans.status = ? OR user_plans.status = ?)', array($logged_user_id, 'Active', 'Active', 'Pending'))
            ->orderBy('id', 'ASC')
            ->get();

        $pricingQry = $this->plans_model()
            ->select("plans.plan_id", "plan_type", "plan_name", "plan_code", "amount", "currency_code", "duration", "duration_schedule", "subscriber_limit",
            "email_limit", "email_sending_rate_limit", "email_sending_rate_period", "status", "description", "free_plan",
            "trial", "next_plan", "plans.product_url", "plans.plan_level")
            ->whereRaw('plans.status = ? AND plans.trial = ? AND plans.plan_mode = ?', array(
                'Active', '0', '1'
            ))
            ->orderBy('plans.plan_id', 'DESC');
        if (sizeof($user_payment_qry) > 0) {
            if ($user_payment_qry[0]['free_pack']) {
                $pricingQry = $pricingQry->where('plans.new_flag', '=', 1);
            } else {
                $pricingQry = $pricingQry->where('plans.new_flag', '=', $user_payment_qry[0]['new_flag']);
            }
        } else {
            $old_payment_details = $this->userplans_model()
                ->select('user_plans.id', 'user_plans.user_id', 'user_plans.plan_id', 'plans.free_plan', 'plans.new_flag', 'plans.plan_level')
                ->leftjoin('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
                ->whereRaw('user_plans.user_id = ? AND plans.status = ?', array($logged_user_id, 'Active'))
                ->orderBy('id', 'DESC')
                ->take(1)
                ->first();
            if (!is_null($old_payment_details)) {
                if ($old_payment_details['free_plan']) {
                    $pricingQry = $pricingQry->where('plans.new_flag', '=', 1);
                } else {
                    $pricingQry = $pricingQry->where('plans.new_flag', '=', $old_payment_details['new_flag']);
                }
            }
        }
        $pricingQry = $pricingQry->get();

        /* START : BILLING DETAILS */
        $billing_details_Qry = $this->payment_transaction_model()
            ->select('payment_transaction_details.payment_id', 'payment_transaction_details.user_plan_id', 'payment_transaction_details.name'
            , 'payment_transaction_details.amount', 'payment_transaction_details.currency', 'payment_transaction_details.card_details'
            , 'payment_transaction_details.paid', 'payment_transaction_details.date_added', 'payment_transaction_details.description'
            , 'payment_transaction_details.used_for', 'plans.plan_name', 'payment_transaction_details.transaction_id'
            , 'payment_transaction_details.status', 'user_plans.payment_method')
            ->leftjoin('user_plans', 'payment_transaction_details.user_plan_id', '=', 'user_plans.id')
            ->leftjoin('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
            ->whereRaw('user_plans.user_id = ?', array($logged_user_id))
            ->orderBy('payment_id', 'DESC')
            ->get();

//        $billing_details_Paykickstart = $this->paykickstart_transaction_model()
//            ->select('paykickstart_transaction_details.*', 'user_plans.payment_method', 'plans.plan_name')
//            ->leftjoin('user_plans', 'paykickstart_transaction_details.invoice_id', '=', 'user_plans.subscribe_code')
//            ->leftjoin('plans', 'user_plans.plan_id', '=', 'plans.plan_id')
//            ->whereRaw('user_plans.user_id = ? AND event = ? AND user_plans.payment_method = ?', array($logged_user_id, 'subscription-payment', 'Paykickstart'))
//            ->orderBy('payment_id', 'DESC')
//            ->get();


//        if(sizeof($billing_details_Qry) > 0 && sizeof($billing_details_Paykickstart) > 0){
//	        $billing_details_Qry = $billing_details_Qry->toArray();
//	        $billing_details_Paykickstart = $billing_details_Paykickstart->toArray();
//            $billing_details = array_merge($billing_details_Qry, $billing_details_Paykickstart);
//        } else if(sizeof($billing_details_Qry) > 0){
//            $billing_details = $billing_details_Qry;
//        } else if(sizeof($billing_details_Paykickstart) > 0){
//            $billing_details = $billing_details_Paykickstart;
//        } else{
//            $billing_details = [];
//        }
	
	    if(sizeof($billing_details_Qry) > 0){
		    $billing_details = $billing_details_Qry->toArray();
	    } else{
		    $billing_details = [];
	    }


        /* END : BILLING DETAILS */

        return array('user_payment_qry'=>$user_payment_qry, 'pricingQry'=>$pricingQry, 'billing_details'=>$billing_details);
    }

    public function getCurrentPlan(){
        $logged_user_id = auth()->id();
        $current_plan = $this->userplans_model()->where('user_id', $logged_user_id)->first();
        return $current_plan;
    }

    public function getPlanById($plan_id){
        return $this->plans_model()->where('plan_id', $plan_id)->first();
    }

}