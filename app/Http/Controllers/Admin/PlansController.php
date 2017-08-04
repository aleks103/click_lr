<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlanRepository;
use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
	protected $planRepository;
	
	public function __construct(PlanRepository $planRepository)
	{
		$this->planRepository = $planRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$list = $this->planRepository->getData();
		
		$calendar_array = config('general.calendar_array');
		
		return view('admin.planList', compact('list', 'calendar_array'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$calendar_array = config('general.calendar_array');
		
		$next_plan_array = $this->planRepository->getValueByColumns([ 'plan_id', 'plan_name' ], [ [ 'status', '=', 'Active' ], [ 'free_plan', '=', '0' ], [ 'trial', '=', '0' ] ]);
		
		return view('admin.addPlan', compact('calendar_array', 'next_plan_array'));
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'plan_name'   => 'required|min:2',
			'amount'      => 'required_if:free_plan,null,',
			'duration'    => 'required',
			'email_limit' => 'required',
			'description' => 'required'
		], [
			'plan_name.required'   => 'Plan Name is required.',
			'amount.required_if'   => 'Amount is required.',
			'duration.required'    => 'Plan Duration is required.',
			'email_limit.required' => 'Email Limit is required. If unlimited clicks, enter 0',
			'description.required' => 'Description is required.',
		]);
		
		$this->planRepository->create($request);
		
		$request->session()->flash('success', 'Plan created successfully');
		
		return redirect('admin/plans');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Plan $plan
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Plan $plan)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Plan $plan
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Plan $plan)
	{
		$calendar_array = config('general.calendar_array');
		
		$next_plan_array = $this->planRepository->getValueByColumns([ 'plan_id', 'plan_name' ], [ [ 'status', '=', 'Active' ], [ 'free_plan', '=', '0' ], [ 'trial', '=', '0' ] ]);
		
		$product_array = DB::table('plan_products')->where('plan_id', '=', $plan->plan_id)->get();
		
		return view('admin.editPlan', compact('plan', 'calendar_array', 'next_plan_array', 'product_array'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Plan                $plan
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Plan $plan)
	{
		$this->validate($request, [
			'plan_name'   => 'required|min:2',
			'amount'      => 'required_if:free_plan,null,',
			'duration'    => 'required',
			'email_limit' => 'required',
			'description' => 'required'
		], [
			'plan_name.required'   => 'Plan Name is required.',
			'amount.required_if'   => 'Amount is required.',
			'duration.required'    => 'Plan Duration is required.',
			'email_limit.required' => 'Email Limit is required. If unlimited clicks, enter 0',
			'description.required' => 'Description is required.',
		]);
		
		$this->planRepository->update($request, $plan);
		
		$request->session()->flash('success', 'Plan updated successfully');
		
		return redirect('admin/plans');
	}
	
	public function postProductId(Request $request)
	{
		$this->validate($request, [
			'product_id' => 'required'
		], [
			'product_id.required' => 'Product ID is required.'
		]);
		
		DB::table('plan_products')->insert([ 'plan_id' => $request->plan_id, 'product_id' => $request->product_id ]);
		
		$request->session()->flash('success', 'Product ID added successfully');
		
		return redirect('admin/plans/' . $request->plan_id . '/edit');
	}
	
	public function destroyProductId(Request $request)
	{
		DB::table('plan_products')->where('id', '=', $request->product_key)->delete();
		
		$request->session()->flash('success', 'Product ID deleted successfully');
		
		return redirect('admin/plans/' . $request->plan_key . '/edit');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Plan $plan
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Plan $plan)
	{
		$plan->status = 'Deleted';
		$plan->save();
		
		session()->flash('success', 'Plan deleted successfully');
		
		return redirect('admin/plans');
	}
}
