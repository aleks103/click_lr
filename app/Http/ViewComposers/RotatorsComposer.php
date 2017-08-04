<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/26/2017
 * Time: 7:18 AM
 */

namespace App\Http\ViewComposers;

use App\Http\Repositories\Accounts\DataService;
use App\Http\Repositories\Accounts\RotatorsRepository;
use App\Http\Repositories\CountriesRepository;
use Illuminate\View\View;

class RotatorsComposer
{
	protected $countries;
	protected $rotators;
	
	public function __construct(CountriesRepository $countries, RotatorsRepository $rotatorsRepository)
	{
		$this->countries = $countries;
		
		$this->rotators = $rotatorsRepository;
	}
	
	/**
	 * Bind data to the view
	 *
	 * @param View $view
	 */
	public function compose(View $view)
	{
		$tracking_domain = [];
		if ( config('site.site_domain_name') == 'clickperfect' ) {
			foreach ( config('site.custom_domains') as $key => $val ) {
				$tracking_url = 'http://';
				
				if ( strtolower(auth()->user()->domain) != '' ) {
					$tracking_url .= strtolower(auth()->user()->domain) . '.';
				}
				
				$tracking_domain[$key] = $tracking_url . $val . '/tr';
			}
		} else {
			$tracking_domain[0] = request()->root() . '/tr';
		}
		
		$custom_domains = DataService::getCustomDomains('1');
		if ( count($custom_domains) > 0 ) {
			foreach ( $custom_domains as $custom_domain ) {
				$tracking_domain[$custom_domain['id']] = $custom_domain['domain_name'];
			}
		}
		
		$countries_result = [];
		
		$countries_ary = $this->countries->getValueByColumns([ 'code', 'name' ], [], 'name');
		if ( sizeof($countries_ary) > 0 ) {
			foreach ( $countries_ary as $key => $row ) {
				$countries_result[$row['code']] = $row['name'];
			}
		}
		
		$view->with('rotators_group', $this->rotators->getRotatorsGroup());
		$view->with('tracking_domain', $tracking_domain);
		$view->with('popup_arr', [ '0' => 'None' ] + DataService::getPopUpsForSelect());
		$view->with('pop_bar_arr', [ '0' => 'None' ] + DataService::getPopBarsForSelect());
		$view->with('timer_arr', [ '0' => 'None' ] + DataService::getTimersForLink());
		$view->with('countries_arr', $countries_result);
		$view->with('rotator_bad_clicks_arr', config('general.rotator_bad_clicks_arr'));
	}
}