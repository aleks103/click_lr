<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 5:31 AM
 */

namespace App\Http\Repositories;

use App\Plan;

class PlanRepository extends Repository
{
	public function model()
	{
		return app(Plan::class);
	}
	
	public function getAll()
	{
		return $this->model()->where('status', 'Active')->get();
	}
	
	public function getData()
	{
		return $this->model()->select('plans.*', 'n_p.plan_name AS next_plan_name')
			->leftJoin('plans AS n_p', 'plans.next_plan', '=', 'n_p.plan_id')
			->where('plans.status', '=', 'Active')->orderBy('plans.plan_id', 'DESC')->paginate(getConfig('per_page_list'));
	}
	
	public function create($request)
	{
		$ins_data = [
			'plan_name'         => $request->plan_name,
			'plan_code'         => str_random(8),
			'product_url'       => is_null($request->product_url) ? '' : $request->product_url,
			'plan_type'         => 1,
			'amount'            => !isset($request->amount) || is_null($request->amount) ? 0 : ((!is_null($request->free_plan) && $request->free_plan == 'on') ? 0 : $request->amount),
			'free_plan'         => !isset($request->free_plan) || is_null($request->free_plan) ? 0 : ($request->free_plan == 'on' ? 1 : 0),
			'trial'             => is_null($request->trial) ? 0 : $request->trial,
			'next_plan'         => !isset($request->next_plan) || is_null($request->next_plan) ? 0 : $request->next_plan,
			'currency_code'     => 'USD',
			'duration'          => $request->duration,
			'duration_schedule' => $request->duration_schedule,
			'email_limit'       => !isset($request->email_limit) || is_null($request->email_limit) ? 0 : $request->email_limit,
			'status'            => 'Active',
			'plan_mode'         => is_null($request->plan_mode) ? 0 : $request->plan_mode,
			'description'       => $request->description,
			'new_flag'          => !isset($request->new_flag) ? 0 : ($request->new_flag == 'on' ? 1 : 0),
			'plan_level'        => !isset($request->plan_level) || is_null($request->plan_level) ? 0 : $request->plan_level
		];
		
		$this->model()->insert($ins_data);
	}
	
	public function update($request, $plan)
	{
		$plan->plan_name         = $request->plan_name;
		$plan->product_url       = is_null($request->product_url) ? '' : $request->product_url;
		$plan->amount            = !isset($request->amount) || is_null($request->amount) ? 0 : ((!is_null($request->free_plan) && $request->free_plan == 'on') ? 0 : $request->amount);
		$plan->free_plan         = !isset($request->free_plan) || is_null($request->free_plan) ? 0 : ($request->free_plan == 'on' ? 1 : 0);
		$plan->trial             = is_null($request->trial) ? 0 : $request->trial;
		$plan->next_plan         = !isset($request->next_plan) || is_null($request->next_plan) ? 0 : $request->next_plan;
		$plan->duration          = $request->duration;
		$plan->duration_schedule = $request->duration_schedule;
		$plan->email_limit       = !isset($request->email_limit) || is_null($request->email_limit) ? 0 : $request->email_limit;
		$plan->status            = 'Active';
		$plan->plan_mode         = is_null($request->plan_mode) ? 0 : $request->plan_mode;
		$plan->description       = $request->description;
		$plan->new_flag          = !isset($request->new_flag) ? 0 : ($request->new_flag == 'on' ? 1 : 0);
		$plan->plan_level        = !isset($request->plan_level) || is_null($request->plan_level) ? 0 : $request->plan_level;
		
		$plan->save();
	}
	
	public function gettingPlanByProductId($product_id, $usage = 'first')
	{
		$planQry = $this->model()
			->select('plans.*')
			->leftJoin('plan_products', 'plan_products.plan_id', '=', 'plans.plan_id')
			->whereRaw('plan_products.product_id = ? AND plans.status = ?', [ $product_id, 'Active' ]);
		if ( $usage == 'count' ) {
			$planQry = $planQry->count();
		}
		if ( $usage == 'first' ) {
			$planQry = $planQry->first();
		}
		
		return $planQry;
	}
}