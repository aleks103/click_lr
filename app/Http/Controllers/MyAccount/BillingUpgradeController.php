<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Repositories\Accounts\BillingUpgradeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingUpgradeController extends Controller
{
    protected $repo;
    public function __construct(BillingUpgradeRepository $billingUpgradeRepository)
    {
        $this->middleware('auth');

        $this->repo = $billingUpgradeRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($sub_domain, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($request->has('flag')) {
            if ($request->input('flag') == 'showDetail') {
                $results = $this->repo->getPlanById($request->input('plan_id'));
                return response()->view('users.billingupgradeDetail', compact('results'));
            } else if ($request->input('flag') == 'cancelSubscription') {

                $user_plan_id = $request->input('plan_id');
                $payment_method = $this->repo->userplans_model()->whereRaw('id = ?', array($user_plan_id))->first();
                if (isset($payment_method) && $payment_method->payment_method == 'Paykickstart' && $payment_method->subscribe_code != '') {
                    // Cancel subscriptions in paykickstart
                    $id_new = 0;
                    $data = array();
                    $url = "https://app.paykickstart.com/api/subscriptions/cancel";
                    $data['auth_token'] = 'fGhQPIV3LbUD';
                    $data['invoice_id'] = $payment_method->subscribe_code;
                    try {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                        $output = curl_exec($ch);
                        curl_close($ch);
                    } catch (Exception $e) {
                        //do nothing
                        //Session::flash('error', $e->getMessage());
                        return response()->redirectTo('billingupgrade');
                    }
                    $id_new = 1;
                    // Cancel subscriptions in paykickstart
                } else {
                    $id_new = 1;
                    /*$this->SudoPayment = new SudoPayment(array(
                        'api_key'     => Config::get('site.sudo_pay_api_key'),
                        'merchant_id' => Config::get('site.merchant_id'),
                        'website_id'  => Config::get('site.website_id'),
                        'secret'      => Config::get('site.sudo_pay_secret_key')
                    ));
                    $payemt_cancel = UserPlans::whereRaw('id = ?  AND payment_method = ?', array($user_plan_id, 'Paypal'))->first();
                    $id_new = 0;
                    if (isset($payemt_cancel) && $payemt_cancel->subscribe_code != '' && $payemt_cancel->payment_method == 'Paypal') {
                        $payment_cancel_response = $this->SudoPayment->callCancelPayPalSubscription($payemt_cancel->subscribe_code);
                        if (isset($payment_cancel_response['error']['message']) && $payment_cancel_response['error']['message'] != '') {
                            $error_cancel = str_replace('Gateway Error:', '', $payment_cancel_response['error']['message']);
                            Session::flash('error', $error_cancel);
                            return Redirect::to('account/billing-upgrade');
                        } else {
                            $id_new = 1;
                        }
                    } else {
                        $id_new = 1;
                    }*/
                }

                if ($id_new == 1) {
                    $updateRow = $this->repo->userplans_model()->find($user_plan_id);
                    $updateRow->status = 'Cancelled';
                    $updateRow->date_added = date('Y-m-d H:i:s');
                    $updateRow->save();
//            User::whereRaw('user_id = ?', array($this->logged_user_id))->update(array('current_plan' => 0));
                }
                return response()->redirectTo('billingupgrade');

            }
        } else {
            $data_array = $this->repo->getUserPaymentDetails();
            $user_payment_array = $data_array['user_payment_qry'];
            $pricingArray  = $data_array['pricingQry'];
            $billing_details  = $data_array['billing_details'];

            $current_plan = $this->repo->getCurrentPlan();

            $userInfo = auth()->user();

            return response()->view('users.billingupgrade', compact('user_payment_array', 'current_plan', 'pricingArray', 'billing_details', 'userInfo'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sub_domain, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
