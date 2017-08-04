<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 6:30 AM
 */

namespace App\Http\ViewComposers;

use App\Http\Repositories\CountriesRepository;
use App\Http\Repositories\GroupRepository;
use App\Http\Repositories\PlanRepository;
use Illuminate\View\View;

class MembersComposer
{
	/**
	 * The plan repository implementation.
	 *
	 * @var PlanRepository
	 */
	protected $plans;
	protected $groups;
	protected $countries;
	
	/**
	 * MembersComposer constructor.
	 *
	 * @param PlanRepository      $plans
	 * @param GroupRepository     $groups
	 * @param CountriesRepository $countries
	 */
	public function __construct(PlanRepository $plans, GroupRepository $groups, CountriesRepository $countries)
	{
		$this->plans     = $plans;
		$this->groups    = $groups;
		$this->countries = $countries;
	}
	
	/**
	 * Bind data to the view
	 *
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$view->with('plans_columns', $this->plans->getValueByColumns([ 'plan_id', 'plan_name' ], [ [ 'status', '=', 'Active' ] ]));
		$view->with('group_columns', $this->groups->getValueByColumns([ 'id', 'name', 'group_code' ]));
		$view->with('countries_columns', $this->countries->getValueByColumns([ 'country_id', 'code', 'name' ], [], 'name'));
	}
}