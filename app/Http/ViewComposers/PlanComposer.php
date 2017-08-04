<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 5:45 AM
 */

namespace App\Http\ViewComposers;

use App\Http\Repositories\PlanRepository;
use Illuminate\View\View;

class PlanComposer
{
	/**
	 * The plan repository implementation.
	 *
	 * @var PlanRepository
	 */
	protected $plans;
	
	/**
	 * PlanComposer constructor.
	 *
	 * @param PlanRepository $plans
	 */
	public function __construct(PlanRepository $plans)
	{
		$this->plans = $plans;
	}
	
	/**
	 * Bind data to the view
	 *
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$view->with('plans', $this->plans->getAll());
		$view->with('plans_columns', $this->plans->getValueByColumns([ 'plan_id', 'plan_name' ]));
	}
}