<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:37 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\LinkRotatorsAlertRepository;
use Illuminate\Support\Facades\Facade;

class DataService extends Facade
{
	public static function getFacadeAccessor()
	{
		return 'DataService';
	}
	
	/**
	 * Dashboard services counts.
	 *
	 * @return array
	 */
	public static function getServicesCounts()
	{
		$links     = new LinksRepository();
		$links_all = $links->getAll();
		
		$rotators     = new RotatorsRepository();
		$rotators_all = $rotators->getAll();
		
		$popups     = new PopupRepository();
		$popups_all = $popups->getAll();
		
		$pop_bars     = new PopBarsRepository();
		$pop_bars_all = $pop_bars->getAll();
		
		$timers     = new TimersRepository();
		$timers_all = $timers->getAll();
		
		return [
			'links_count'    => count($links_all),
			'rotators_count' => count($rotators_all),
			'popup_count'    => count($popups_all),
			'pop_bar_count'  => count($pop_bars_all),
			'timer_count'    => count($timers_all)
		];
	}
	
	/**
	 * Dashboard Link Rotators Graph data
	 *
	 * @param $flag
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $d_arr
	 *
	 * @return array
	 */
	public static function getXYAxisArray($flag, $calendar_numeric, $date_interval, $d_arr)
	{
		if ( $flag == 'links' ) {
			$links = new LinksRepository();
			
			return $links->getLinkXYAxisArray($calendar_numeric, $date_interval, $d_arr);
		} else if ( $flag == 'rotators' ) {
			$rotators = new RotatorsRepository();
			
			return $rotators->getRotatorsXYAxisArray($calendar_numeric, $date_interval, $d_arr);
		}
	}
	
	/**
	 * @param $domain_id
	 * @param $domain_for
	 *
	 * @return mixed
	 */
	public static function getTrackingDomain($domain_id, $domain_for)
	{
		$customDomainRepo = new CustomDomainRepository();
		
		return $customDomainRepo->getTrackingDomain($domain_id, $domain_for);
	}
	
	/**
	 * Links edit - popups
	 *
	 * @return array
	 */
	public static function getPopUpsForSelect()
	{
		$popups = new PopupRepository();
		
		$popup_qry = $popups->getValueByColumns([ 'id', 'popupname' ], [ [ 'status', '=', 'Active' ] ], [ 'created_at', 'desc' ]);
		$popup_arr = [];
		if ( count($popup_qry) > 0 ) {
			foreach ( $popup_qry as $popup ) {
				$popup_arr[$popup['id']] = $popup['popupname'];
			}
		}
		
		return $popup_arr;
	}
	
	/**
	 * Links edit pop bars
	 *
	 * @return array
	 */
	public static function getPopBarsForSelect()
	{
		$pop_bars = new PopBarsRepository();
		
		$pop_bars_arr = $pop_bars->getValueByColumns([ 'id', 'bar_name' ], [ [ 'status', '=', '0' ] ], [ 'created_at', 'desc' ]);
		
		$pop_bar_ary = [];
		if ( count($pop_bars_arr) > 0 ) {
			foreach ( $pop_bars_arr as $row ) {
				$pop_bar_ary[$row['id']] = $row['bar_name'];
			}
		}
		
		return $pop_bar_ary;
	}
	
	/**
	 * Links edit custom domains
	 *
	 * @param $domain_for
	 *
	 * @return array
	 */
	public static function getCustomDomains($domain_for)
	{
		$customDomain = new CustomDomainRepository();
		
		return $customDomain->getValueByColumns([ 'id', 'domain_name', 'domain_for' ], [ [ 'status', '=', '0' ], [ 'domain_for', '=', $domain_for ] ]);
	}
	
	/**
	 * Links alert
	 *
	 * @param $user_id
	 * @param $link_id
	 * @param $ref_type
	 *
	 * @return array
	 */
	public static function getLinkAlertsByLinkID($user_id, $link_id, $ref_type = '0')
	{
		$linkAlertsRepo = new LinkRotatorsAlertRepository();
		
		return $linkAlertsRepo->getValueByColumns([ 'id', 'status' ], [ [ 'user_id', '=', $user_id ], [ 'ref_id', '=', $link_id ], [ 'ref_type', '=', $ref_type ] ]);
	}
	
	/**
	 * Link edit smart swap for select
	 *
	 * @param string $link_id
	 *
	 * @return array
	 */
	public static function getSmartSwapLinks($link_id = '0')
	{
		$link_arr = $rotators_arr = [];
		
		$linkRepo = new LinksRepository();
		
		$links = $linkRepo->getValueByColumns([ 'id', 'link_name', 'tracking_link' ], [ [ 'status', '=', 'Active' ], [ 'id', '<>', $link_id ] ]);
		if ( count($links) > 0 ) {
			foreach ( $links as $link ) {
				$value = $link['link_name'] != '' ? $link['link_name'] : $link['tracking_link'];
				
				$link_arr['link_' . $link['id']] = 'Link - ' . $value;
			}
		}
		
		$rotatorsRepo = new RotatorsRepository();
		
		$rotators = $rotatorsRepo->getValueByColumns([ 'id', 'rotator_name', 'rotator_link' ], [ [ 'status', '=', '0' ] ]);
		if ( count($rotators) > 0 ) {
			foreach ( $rotators as $row ) {
				$rotators_value = $row['rotator_name'] != '' ? $row['rotator_name'] : $row['rotator_link'];
				
				$rotators_arr['rotator_' . $row['id']] = 'Rotators - ' . $rotators_value;
			}
		}
		
		return $link_arr + $rotators_arr;
	}
	
	/**
	 * @return array
	 */
	public static function getTimersForLink()
	{
		$timersRepo = new TimersRepository();
		
		$timers = $timersRepo->getValueByColumns([ 'id', 'timer_name' ], [ [ 'status', '=', '0' ] ], [ 'created_at', 'desc' ]);
		
		$timer_arr = [];
		if ( count($timers) > 0 ) {
			foreach ( $timers as $timer ) {
				$timer_arr[$timer['id']] = $timer['timer_name'];
			}
		}
		
		return $timer_arr;
	}
}