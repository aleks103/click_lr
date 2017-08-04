<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Facades\Tenant;
use App\Http\Repositories\Repository;
use App\Models\Link;
use Illuminate\Support\Facades\DB;

class LinksRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(Link::class);
	}
	
	/**
	 * Get Links all data.
	 *
	 * @return mixed
	 */
	public function getAll()
	{
		$links = $this->model()->where('status', '<>', 'Deleted')->where('link_type', '=', 'all-links')->get();
		
		return $links;
	}
	
	/**
	 * Create Link
	 *
	 * @param        $user_id
	 * @param        $input_arr
	 * @param string $from
	 *
	 * @return mixed
	 */
	public function create($user_id, $input_arr, $from = 'add')
	{
		$added_date = date('Y-m-d H:i:s');
		
		$unix_added_date = DB::raw("unix_timestamp(NOW())");
		
		$fill_able = [
			'primary_url', 'tracking_link', 'link_group_id', 'link_name', 'tracking_domain', 'cloak_link', 'cloak_page_title', 'cloak_page_description', 'popup_id',
			'magickbar_id', 'timer_id', 'max_clicks', 'smartswap_id', 'smartswap_type', 'traffic_cost', 'geo_targeting', 'geo_targeting_include_countries',
			'geo_targeting_exclude_countries', 'backup_url', 'mobile_url', 'repeat_url', 'tracking_link_visited', 'pixel_code', 'abuser', 'anon', 'bot', 'spider', 'server',
			'user', 'notes', 'detect_new_bots', 'link_type'
		];
		
		$data_arr = $input_arr;
		
		foreach ( $fill_able as $key ) {
			if ( !isset($data_arr[$key]) ) {
				$data_arr[$key] = '';
				if ( $key == 'tracking_domain' || $key == 'smartswap_id' )
					$data_arr[$key] = '0';
				
				if ( $key == 'tracking_link_visited' || $key == 'detect_new_bots' )
					$data_arr[$key] = 'No';
				
				if ( $key == 'link_type' )
					$data_arr[$key] = 'all-links';
				
				if ( $key == 'geo_targeting' )
					$data_arr[$key] = 'All';
			}
		}
		
		if ( $from == 'add' ) {
			$geo_targeting_include_countries = ($data_arr['geo_targeting'] == 'Specified' && $data_arr['geo_targeting_include_countries'] != '') ? implode(",", $data_arr['geo_targeting_include_countries']) : '';
			$geo_targeting_exclude_countries = ($data_arr['geo_targeting'] == 'Specified' && $data_arr['geo_targeting_exclude_countries'] != '') ? implode(",", $data_arr['geo_targeting_exclude_countries']) : '';
		} else {
			$geo_targeting_include_countries = $data_arr['geo_targeting_include_countries'];
			$geo_targeting_exclude_countries = $data_arr['geo_targeting_exclude_countries'];
		}
		
		$smartswap_id   = '0';
		$smartswap_type = '0';
		
		if ( $data_arr['smartswap_id'] != '0' ) {
			$smart_swap_arr = explode('_', $data_arr['smartswap_id']);
			
			$smartswap_id = $smart_swap_arr[1];
			
			$smartswap_type = $smart_swap_arr[0] == 'link' ? '0' : '1';
		}
		
		$data_arr['smartswap_id']   = $smartswap_id;
		$data_arr['smartswap_type'] = $smartswap_type;
		
		$data_arr['geo_targeting_include_countries'] = $geo_targeting_include_countries;
		$data_arr['geo_targeting_exclude_countries'] = $geo_targeting_exclude_countries;
		
		$link = new Link();
		
		$link->user_id = $user_id;
		$link->status  = 'Active';
		
		$link->traffic_cost_for = 'CPC';
		$link->date_added       = $added_date;
		$link->unix_date_added  = $unix_added_date;
		
		$link->fill($data_arr);
		$link->save();
		
		return $link->id;
	}
	
	public function delete($link)
	{
		Tenant::DB()->table('links_log')->where('link_id', '=', $link)->delete();
		Tenant::DB()->table('links_notification')->where('link_id', '=', $link)->delete();
		Tenant::DB()->table('links_split_url')->where('link_id', '=', $link)->delete();
		Tenant::DB()->table('links_date_wise_clicks_log')->where('link_id', '=', $link)->delete();
	}
	
	/**
	 * Get Link report data for graph
	 *
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $d_arr
	 *
	 * @return array
	 */
	public function getLinkXYAxisArray($calendar_numeric, $date_interval, $d_arr)
	{
		$QueryInterval = 'interval ' . $calendar_numeric . ' ' . $date_interval;
		
		$this->model()->where('status', '=', 'Deleted')->delete();
		
		$current_date = date('d-m-Y');
		$table_name   = 'links_log';
		$link_id      = $d_arr['link_id'];
		
		$total_clicks = $total_unique_clicks = $total_nonunique_clicks = [];
		
		$calc_data = [
			'yAxisTotalNonUniqueDate' => [],
			'yAxisTotalUniqueDate'    => [],
			'yAxisTotalDate'          => [],
			'totalClicks'             => [],
			'totalUniqueClicks'       => [],
			'totalNonUniqueClicks'    => []
		];
		
		$totalPClicks = $totalPUniqueClicks = $totalPNonUniqueClicks = 0;
		
		if ( $calendar_numeric > 1 ) {
			$date_wise_clicks_logs = Tenant::DB()->table($table_name)
				->select('id', 'unique_click_per_day', DB::raw('date(from_unixtime(updated_at)) as `date`'))
				->where('link_reset', '=', '0')
				->whereRaw('date(from_unixtime(updated_at)) >= date_sub(now(), ' . $QueryInterval . ')');
			
			if ( !$link_id ) {
				$date_wise_clicks_logs = $date_wise_clicks_logs->get();
			} else {
				$date_wise_clicks_logs = $date_wise_clicks_logs->where('link_id', '=', $link_id)->get();
			}
			
			if ( isset($date_wise_clicks_logs) && count($date_wise_clicks_logs) > 0 ) {
				for ( $i = 0; $i <= $calendar_numeric; $i++ ) {
					$date_value = date('d/m', strtotime($current_date . ' - ' . ($calendar_numeric * 1 - $i) . ' days'));
					
					$calc_data['totalUniqueClicks'][$date_value] = 0;
					
					$calc_data['totalNonUniqueClicks'][$date_value] = 0;
				}
				
				foreach ( $date_wise_clicks_logs as $date_wise_clicks_log ) {
					$dStr = date('d/m', strtotime($date_wise_clicks_log->date));
					
					if ( !isset($calc_data['totalUniqueClicks'][$dStr]) ) {
						continue;
					}
					if ( $date_wise_clicks_log->unique_click_per_day == '1' ) {
						$calc_data['totalUniqueClicks'][$dStr] += 1;
						
						$totalPUniqueClicks += 1;
					} else {
						$calc_data['totalNonUniqueClicks'][$dStr] += 1;
						
						$totalPNonUniqueClicks += 1;
					}
					
					$calc_data['totalClicks'][$dStr] = $calc_data['totalUniqueClicks'][$dStr] + $calc_data['totalNonUniqueClicks'][$dStr];
					
					$totalPClicks = $totalPUniqueClicks + $totalPNonUniqueClicks;
				}
				
				$calc_data['yAxisTotalDate']          = [ $current_date ];
				$calc_data['yAxisTotalUniqueDate']    = [ $current_date ];
				$calc_data['yAxisTotalNonUniqueDate'] = [ $current_date ];
				
				for ( $i = 1; $i < $calendar_numeric; $i++ ) {
					$dStr = date('d-m-Y', strtotime("-" . $i . " day", strtotime($current_date)));
					
					$calc_data['yAxisTotalDate'][] = $calc_data['yAxisTotalUniqueDate'][] = $calc_data['yAxisTotalNonUniqueDate'][] = $dStr;
				}
				
				$sortTime = [];
				foreach ( $calc_data['yAxisTotalDate'] as $key => $values ) {
					$sortTime[] = strtotime($values);
				}
				asort($sortTime);
				
				$calc_data['yAxisTotalDate'] = $calc_data['yAxisTotalUniqueDate'] = $calc_data['yAxisTotalNonUniqueDate'] = [];
				foreach ( $sortTime as $value ) {
					$calc_data['yAxisTotalDate'][] = $calc_data['yAxisTotalUniqueDate'][] = $calc_data['yAxisTotalNonUniqueDate'][] = date('d/m', $value);
				}
				
				$calc_data['yAxisTotalDate'] = array_unique($calc_data['yAxisTotalDate']);
				
				$calc_data['yAxisTotalUniqueDate'] = array_unique($calc_data['yAxisTotalUniqueDate']);
				
				$calc_data['yAxisTotalNonUniqueDate'] = array_unique($calc_data['yAxisTotalNonUniqueDate']);
				
				$clicksData = $this->getClicksData($calc_data['yAxisTotalDate'], $calc_data['totalClicks'], $calc_data['totalUniqueClicks'], $calc_data['totalNonUniqueClicks']);
				
				$total_clicks = $clicksData[0];
				
				$total_unique_clicks = $clicksData[1];
				
				$total_nonunique_clicks = $clicksData[2];
			}
		} else {
			$hour_clicks = $hour_unique_clicks = $hour_nonunique_clicks = [];
			
			$calc_data['totalClicks'] = Tenant::DB()->table($table_name)
				->select('link_id', DB::raw('count(unique_click_per_day) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('type', '=', '0')
				->where('link_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $link_id > 0 ) {
				$calc_data['totalClicks'] = $calc_data['totalClicks']->where('link_id', '=', $link_id);
			}
			$calc_data['totalClicks'] = $calc_data['totalClicks']->get();
			
			$calc_data['totalUniqueClicks'] = Tenant::DB()->table($table_name)
				->select('link_id', DB::raw('count(unique_click_per_day) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('links_log.unique_click_per_day', '=', '1')
				->where('type', '=', '0')
				->where('link_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $link_id > 0 ) {
				$calc_data['totalUniqueClicks'] = $calc_data['totalUniqueClicks']->where('link_id', '=', $link_id);
			}
			$calc_data['totalUniqueClicks'] = $calc_data['totalUniqueClicks']->get();
			
			$calc_data['totalNonUniqueClicks'] = Tenant::DB()->table($table_name)
				->select('link_id', DB::raw('count(unique_click_per_day) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('links_log.unique_click_per_day', '=', '0')
				->where('type', '=', '0')
				->where('link_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $link_id > 0 ) {
				$calc_data['totalNonUniqueClicks'] = $calc_data['totalNonUniqueClicks']->where('link_id', '=', $link_id);
			}
			$calc_data['totalNonUniqueClicks'] = $calc_data['totalNonUniqueClicks']->get();
			
			$j = $k = $l = 0;
			
			if ( sizeof($calc_data['totalClicks']) > 0 ) {
				foreach ( $calc_data['totalClicks'] as $key => $values ) {
					$hour_clicks[$values->created_at] = $values->total_clicks;
					
					$totalPClicks += $values->total_clicks;
					$j++;
				}
			}
			
			if ( sizeof($calc_data['totalUniqueClicks']) > 0 ) {
				foreach ( $calc_data['totalUniqueClicks'] as $key => $values ) {
					$hour_unique_clicks[$values->created_at] = $values->total_clicks;
					
					$totalPUniqueClicks += $values->total_clicks;
					$k++;
				}
			}
			
			if ( sizeof($calc_data['totalNonUniqueClicks']) > 0 ) {
				foreach ( $calc_data['totalNonUniqueClicks'] as $key => $values ) {
					$hour_nonunique_clicks[$values->created_at] = $values->total_clicks;
					
					$totalPNonUniqueClicks += $values->total_clicks;
					$l++;
				}
			}
			
			$calc_data['yAxisTotalDate'] = [ '12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM' ];
			
			$clicksData = $this->getClicksData($calc_data['yAxisTotalDate'], $hour_clicks, $hour_unique_clicks, $hour_nonunique_clicks);
			
			$total_clicks = $clicksData[0];
			
			$total_unique_clicks = $clicksData[1];
			
			$total_nonunique_clicks = $clicksData[2];
		}
		
		$percentage_unique_clicks = $percentage_nonunique_clicks = 0;
		if ( $totalPClicks > 0 ) {
			$percentage_unique_clicks    = ($totalPUniqueClicks / $totalPClicks) * 100;
			$percentage_nonunique_clicks = ($totalPNonUniqueClicks / $totalPClicks) * 100;
		}
		
		return [
			'0' => $total_clicks,
			'1' => $total_unique_clicks,
			'2' => $total_nonunique_clicks,
			'3' => $percentage_unique_clicks,
			'4' => $percentage_nonunique_clicks,
			'5' => $totalPClicks,
		];
	}
	
	/**
	 * Get clicks data per date interval.
	 *
	 * @param $yAxisTotalDate
	 * @param $tClicks
	 * @param $uClicks
	 * @param $nClicks
	 *
	 * @return array
	 */
	public function getClicksData($yAxisTotalDate, $tClicks, $uClicks, $nClicks)
	{
		$total_clicks = $total_unique_clicks = $total_nonunique_clicks = [];
		foreach ( $yAxisTotalDate as $key => $dateInterval ) {
			$total_clicks[$key] = [
				'date'         => $dateInterval,
				'total_clicks' => 0
			];
			
			$total_unique_clicks[$key] = [
				'date'                => $dateInterval,
				'total_unique_clicks' => 0
			];
			
			$total_nonunique_clicks[$key] = [
				'date'                   => $dateInterval,
				'total_nonunique_clicks' => 0
			];
			
			if ( array_key_exists($dateInterval, $tClicks) ) {
				$total_clicks[$key]['date']         = $dateInterval;
				$total_clicks[$key]['total_clicks'] = $tClicks[$dateInterval];
			}
			
			if ( array_key_exists($dateInterval, $uClicks) ) {
				$total_unique_clicks[$key]['date']                = $dateInterval;
				$total_unique_clicks[$key]['total_unique_clicks'] = $uClicks[$dateInterval];
			}
			
			if ( array_key_exists($dateInterval, $nClicks) ) {
				$total_nonunique_clicks[$key]['date']                   = $dateInterval;
				$total_nonunique_clicks[$key]['total_nonunique_clicks'] = $nClicks[$dateInterval];
			}
		}
		
		return [ $total_clicks, $total_unique_clicks, $total_nonunique_clicks ];
	}
	
	/**
	 * get link groups
	 *
	 * @param null $parent_id
	 *
	 * @return array
	 */
	public function getLinkGroups($parent_id = null)
	{
		$link_group_arr = [];
		
		$link_group = Tenant::DB()->table('link_groups')->select('id', 'link_group', 'parent_id')
			->where('status', '<>', '2')
			->orderBy('parent_id', 'asc')
			->orderBy('created_at', 'desc');
		
		if ( isset($parent_id) ) {
			$link_group = $link_group->where('parent_id', '=', $parent_id);
		}
		
		$link_group = $link_group->get();
		
		if ( sizeof($link_group) > 0 ) {
			foreach ( $link_group as $link ) {
				$link_group_arr[$link->id] = (($link->parent_id != 0) ? $this->getGroupValueById($link->parent_id)->link_group . "-" : "") . $link->link_group;
			}
		}
		
		return $link_group_arr;
	}
	
	public function getPluckLinkGroup($link_id)
	{
		return Tenant::DB()->table('link_groups')->where('id', $link_id)->pluck('link_group');
	}
	
	/**
	 * get link group value by id.
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getGroupValueById($id)
	{
		$link_groups = Tenant::DB()->table('link_groups')->find($id);
		
		return $link_groups;
	}
	
	/**
	 * @param string $start_date
	 * @param string $end_date
	 * @param string $link_type
	 * @param string $search_name
	 *
	 * @return mixed
	 */
	public function buildLinksQuery($start_date = '', $end_date = '', $search_name = '', $link_type = 'all-links')
	{
		$qry = $this->model()->where('status', '=', 'Active');
		
		if ( $start_date != '' && $end_date != '' ) {
			$qry = $qry->whereRaw("(date(date_added) >= ? AND date(date_added) <= ?)", [ $start_date, $end_date ]);
		}
		
		if ( $search_name != '' ) {
			$qry = $qry->where('link_name', 'like', '%' . $search_name . '%');
		}
		
		if ( $link_type == 'all-links' || $link_type == 'archived-link' )
			$qry = $qry->where('links.link_type', '=', $link_type);
		else {
			$select_parent_id = Tenant::DB()->table('link_groups')->select('id')->where('parent_id', $link_type)->get();
			
			$link_ids = [ $link_type ];
			
			if ( count($select_parent_id) > 0 ) {
				foreach ( $select_parent_id as $rr ) {
					array_push($link_ids, $rr->id);
				}
			}
			
			if ( count($link_ids) > 0 )
				$qry = $qry->whereIn('link_group_id', $link_ids);
			else
				$qry = $qry->where('link_group_id', '=', $link_type);
		}
		$qry->orderBy('date_added', 'desc');
		
		return $qry;
	}
	
	/**
	 * @param      $id
	 * @param      $start_date
	 * @param      $end_date
	 * @param bool $unique
	 *
	 * @return mixed
	 */
	public function linkClickCountById($id, $start_date, $end_date, $unique = false)
	{
		$qry = Tenant::DB()->table('links_log')
			->select('id')
			->where('link_reset', '=', '0')
			->where('link_id', '=', $id)
			->whereRaw('date(from_unixtime(`updated_at`)) >= ? AND date(from_unixtime(`updated_at`)) <= ?', [ $start_date, $end_date ]);
		
		if ( $unique ) {
			$qry = $qry->where('unique_click_per_day', '=', '1');
		}
		
		return $qry->count();
	}
	
	/**
	 * @param $link_id
	 * @param $split_url_id
	 *
	 * @return mixed
	 */
	public function getSplitUrlTotalCount($link_id, $split_url_id)
	{
		if ( $split_url_id == 0 ) {
			return Tenant::DB()->table('links_log')->where('link_id', $link_id)->where('filtered_click', '=', '0')->count();
		} else {
			return Tenant::DB()->table('links_log')->where('link_id', $link_id)->where('split_url_id', $split_url_id)->count();
		}
	}
	
	/**
	 * @param $link_id
	 * @param $split_url_id
	 *
	 * @return mixed
	 */
	public function getSplitUrlUniqueCount($link_id, $split_url_id)
	{
		if ( $split_url_id == 0 ) {
			return Tenant::DB()->table('links_log')->where('link_id', $link_id)->where('filtered_click', '=', '0')->where('unique_click', '=', '1')->count();
		} else {
			return Tenant::DB()->table('links_log')->where('link_id', $link_id)->where('split_url_id', $split_url_id)->where('unique_click', '=', '1')->count();
		}
	}
	
	/**
	 * @param $link_id
	 *
	 * @return mixed
	 */
	public function getSplitUniqueActionCount($link_id)
	{
		return Tenant::DB()->table('links_log')->whereRaw('split_url_id = ? AND unique_click_per_day = ? AND type = ? ', [ $link_id, '1', '1' ])->count();
	}
	
	/**
	 * @param $link_id
	 *
	 * @return mixed
	 */
	public function getSplitUniqueEngagementCount($link_id)
	{
		return Tenant::DB()->table('links_log')->whereRaw('split_url_id = ? AND unique_click_per_day = ? AND type = ? ', [ $link_id, '1', '3' ])->count();
	}
	
	/**
	 * @param $link
	 *
	 * @return mixed
	 */
	public function getLinksSplitUrl($link)
	{
		$qry = Tenant::DB()->table('links_split_url')
			->whereRaw('link_id = ? AND is_deleted = ?', [ $link->id, '0' ])
			->orderBy('id', 'desc')->get();
		
		$r = [];
		if ( sizeof($qry) > 0 ) {
			foreach ( $qry as $key => $row ) {
				$r[$key] = (array) $row;
				
				$r[$key]['total_clicks']  = $this->getSplitUrlTotalCount($link->id, $row->id);
				$r[$key]['unique_clicks'] = $this->getSplitUrlUniqueCount($link->id, $row->id);
				if ( $row->primary_url == '1' ) {
					$r[$key]['edit'] = false;
				} else {
					$r[$key]['edit'] = true;
				}
				
				$action_conversion_rate = $engagement_conversion_rate = "-";
				$unique_action_count    = $this->getSplitUniqueActionCount($link->id);
				
				if ( $unique_action_count > 0 ) {
					$action_conversion_rate = round(($unique_action_count / $r[$key]['unique_clicks']) * 100) . '%';
				}
				
				$unique_engage_count = $this->getSplitUniqueEngagementCount($link->id);
				if ( $unique_engage_count > 0 ) {
					$engagement_conversion_rate = round(($unique_engage_count / $r[$key]['unique_clicks']) * 100) . '%';
				}
				
				$r[$key]['ac']  = $unique_action_count;
				$r[$key]['acr'] = $action_conversion_rate;
				$r[$key]['ec']  = $unique_engage_count;
				$r[$key]['ecr'] = $engagement_conversion_rate;
			}
		} else {
			$r[0] = [
				'id'            => $link->id,
				'url_name'      => 'Primary URL',
				'split_url'     => $link->primary_url,
				'weight'        => 100,
				'edit'          => false,
				'total_clicks'  => $this->getSplitUrlTotalCount($link->id, 0),
				'unique_clicks' => $this->getSplitUrlUniqueCount($link->id, 0),
			];
			
			$action_conversion_rate = $engagement_conversion_rate = "-";
			$unique_action_count    = $this->getSplitUniqueActionCount($link->id);
			
			if ( $unique_action_count > 0 ) {
				$action_conversion_rate = round(($unique_action_count / $r[0]['unique_clicks']) * 100) . '%';
			}
			
			$unique_engage_count = $this->getSplitUniqueEngagementCount($link->id);
			if ( $unique_engage_count > 0 ) {
				$engagement_conversion_rate = round(($unique_engage_count / $r[0]['unique_clicks']) * 100) . '%';
			}
			
			$r[0]['ac']  = $unique_action_count;
			$r[0]['acr'] = $action_conversion_rate;
			$r[0]['ec']  = $unique_engage_count;
			$r[0]['ecr'] = $engagement_conversion_rate;
		}
		
		return $r;
	}
	
	/**
	 * @param $link_id
	 * @param $primary_url_ratio
	 */
	public function updatePrimaryUrlRatio($link_id, $primary_url_ratio)
	{
		$data_arr = [ 'weight' => $primary_url_ratio ];
		Tenant::DB()->table('links_split_url')->whereRaw('link_id = ? AND primary_url = ? AND is_deleted = ?', [ $link_id, '1', '0' ])->update($data_arr);
	}
	
	/**
	 * @param $link
	 * @param $request
	 */
	public function createSplitUrl($link, $request)
	{
		$url_count = Tenant::DB()->table('links_split_url')->whereRaw('link_id = ?', [ $link->id ])->count();
		
		if ( $url_count > 0 ) {
			$primary_url_ratio = $request->ratio * 1 - $request->weight * 1;
			
			$this->updatePrimaryUrlRatio($link->id, $primary_url_ratio);
		} else {
			$ins_data = [
				'link_id'     => $link->id,
				'url_name'    => 'Primary URL',
				'split_url'   => $link->primary_url,
				'weight'      => (100 - $request->weight),
				'is_deleted'  => '0',
				'primary_url' => '1',
			];
			
			Tenant::DB()->table('links_split_url')->insert($ins_data);
		}
		
		$ins_data = [
			'link_id'   => $link->id,
			'url_name'  => $request->url_name,
			'split_url' => $request->split_url,
			'weight'    => $request->weight,
		];
		
		Tenant::DB()->table('links_split_url')->insert($ins_data);
	}
	
	/**
	 * @param $link
	 * @param $request
	 */
	public function updateSplitUrl($link, $request)
	{
		$upd_data = [
			'url_name'  => $request->url_name,
			'split_url' => $request->split_url,
			'weight'    => $request->weight,
		];
		
		Tenant::DB()->table('links_split_url')->where('id', '=', $request->split_id)->update($upd_data);
		
		$primary_url_ratio = $request->ratio * 1 - $request->weight * 1;
		
		$this->updatePrimaryUrlRatio($link->id, $primary_url_ratio);
	}
	
	/**
	 * @param $link
	 * @param $split_url_id
	 */
	public function deleteSplitUrl($link, $split_url_id)
	{
		$del = Tenant::DB()->table('links_split_url')->find($split_url_id);
		
		$weight = $del->weight;
		
		$primary = Tenant::DB()->table('links_split_url')->whereRaw('link_id = ? AND primary_url = ? AND is_deleted = ?', [ $link->id, '1', '0' ])->first();
		
		$new_weight = $weight * 1 + $primary->weight * 1;
		
		$this->updatePrimaryUrlRatio($link->id, $new_weight);
		
		Tenant::DB()->table('links_split_url')->where('id', $split_url_id)->delete();
	}
	
	/**
	 * Split url make equal weight
	 *
	 * @param $split_url_id
	 * @param $weight
	 */
	public function equalWeight($split_url_id, $weight)
	{
		$upd_data = [
			'weight' => $weight,
		];
		Tenant::DB()->table('links_split_url')->where('id', '=', $split_url_id)->update($upd_data);
	}
	
	/**
	 * @param $link
	 *
	 * @return mixed
	 */
	public function getLinkNotifications($link)
	{
		$link_notifications = Tenant::DB()->table('links_notification')
			->where('link_id', '=', $link->id)
			->where('is_deleted', '=', '0')
			->get();
		
		return $link_notifications;
	}
	
	/**
	 * @param $link
	 * @param $request
	 */
	public function createLinkNotifications($link, $request)
	{
		$ins_data = [
			'link_id'           => $link->id,
			'notification_type' => $request->notification_type,
			'relational'        => $request->relational,
			'value'             => $request->value,
			'clicks'            => $request->clicks,
		];
		
		Tenant::DB()->table('links_notification')->insert($ins_data);
	}
	
	/**
	 * @param $request
	 */
	public function deleteLinkNotifications($request)
	{
		Tenant::DB()->table('links_notification')->where('id', $request->notification_id)->delete();
	}
	
	/**
	 * @param $link
	 */
	public function resetLinkStat($link)
	{
		//		Tenant::DB()->table('links_log')->where('link_id', '=', $link->id)->update([ 'link_reset' => '1' ]);
		Tenant::DB()->table('links_log')->where('link_id', '=', $link->id)->delete();
	}
	
	/**
	 * @param $link_id
	 *
	 * @return array
	 */
	public function linksTrafficQuality($link_id)
	{
		$links_log_unique_ip = Tenant::DB()->table('links_log')
			->whereRaw('link_id = ? AND type = ? AND link_reset = ? AND filtered_click = ?', [ $link_id, '0', '0', '0' ])
			->select('client_ip', DB::raw('count(*) as total'))
			->groupBy('client_ip')->get();
		
		$links_log_details = Tenant::DB()->table('links_log')
			->whereRaw('link_id = ? AND type = ? AND link_reset= ? AND filtered_click = ?', [ $link_id, '0', '0', '0' ])
			->get();
		
		$return_array = [ 'unique_ip' => $links_log_unique_ip, 'link_details' => $links_log_details ];
		
		return $return_array;
	}
	
	/**
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $d_arr
	 *
	 * @return array
	 */
	public function getFilteredLinkClickGraph($calendar_numeric, $date_interval, $d_arr)
	{
		$clicks  = $total_clicks = [];
		$link_id = $d_arr['link_id'];
		
		$QueryInterval  = 'INTERVAL ' . $calendar_numeric . ' ' . $date_interval;
		$yAxisTotalDate = $totalClicks = $total_clicks = [];
		$current_date   = date('d-m-Y');
		
		if ( $calendar_numeric > 1 ) {
			$link_log = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select('links_log.id', 'links_log.link_id', 'links_log.referer_id', 'links_log.client_ip', 'links_log.unique_click_per_day', 'links_log.unique_click', DB::raw('from_unixtime(links_log.created_at) as created_at'), 'links_log.url', 'links_log.geoip_id', 'links_log.agent_id', 'links_log.device_id')
				->whereRaw('date(from_unixtime(links_log.created_at)) >= date_sub(now(), ' . $QueryInterval . ')')
				->whereRaw('links_log.type =? AND links_log.link_id =?', [ '0', $link_id ])
				->where('links.status', '<>', '2')
				->where('links_log.link_reset', '=', '0')
				->orderBy('links_log.updated_at', 'desc')
				->get();
			
			$filtered_links_clicks = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select(DB::raw('count(links_log.unique_click) as total_clicks'), 'links_log.link_id', DB::raw('from_unixtime(links_log.updated_at) as created_at'))
				->whereRaw('date(from_unixtime(links_log.created_at)) >= date_sub(now(), ' . $QueryInterval . ')')
				->whereRaw('links_log.type =? AND links_log.link_id =?', [ '0', $link_id ])
				->where('links.status', '<>', 'Deleted')
				->where('links_log.link_reset', '=', '0')
				->groupBy(DB::raw('DATE(from_unixtime(links_log.updated_at))'))
				->get();
			
			$j = 0;
			
			$total_pclicks = 0;
			if ( sizeof($filtered_links_clicks) > 0 ) {
				foreach ( $filtered_links_clicks as $key => $values ) {
					$yAxisTotalDate[$j] = date('d-m-Y', strtotime($values->created_at));
					
					$totalClicks[date('d/m', strtotime($values->created_at))] = $values->total_clicks;
					
					$total_pclicks += $values->total_clicks * 1;
					
					$clicks[$values->created_at] = $values->total_clicks * 1;
					
					$j++;
				}
			}
			
			$yAxisTotalDate = [ $current_date ];
			
			for ( $i = 1; $i < $calendar_numeric; $i++ ) {
				$yAxisTotalDate[] = date("d-m-Y", strtotime("-" . $i . " day", strtotime($yAxisTotalDate[0])));
			}
			
			if ( sizeof($yAxisTotalDate) > 0 ) {
				foreach ( $yAxisTotalDate as $key => $values ) {
					$yAxisTotalDateStrtoTime[] = strtotime(date('d-m-Y', strtotime($values)));
				}
				asort($yAxisTotalDateStrtoTime);
				
				$yAxisTotalDate = [];
				foreach ( $yAxisTotalDateStrtoTime as $value ) {
					$yAxisTotalDate[] = date('d/m', $value);
				}
				$yAxisTotalDate = array_unique($yAxisTotalDate);
			}
			
			foreach ( $yAxisTotalDate as $key => $dateInterval ) {
				$total_clicks[$key]['date'] = $dateInterval;
				
				if ( array_key_exists($dateInterval, $totalClicks) ) {
					$total_clicks[$key]['total_clicks'] = $totalClicks[$dateInterval];
				} else {
					$total_clicks[$key]['total_clicks'] = 0;
				}
			}
			
			return [ 0 => $total_clicks, 1 => $link_log ];
		} else {
			$link_log = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select('links_log.id', 'links_log.link_id', 'links_log.referer_id', 'links_log.client_ip', 'links_log.unique_click_per_day', 'links_log.unique_click', DB::raw('from_unixtime(links_log.updated_at) as created_at'), 'links_log.url', 'links_log.geoip_id', 'links_log.agent_id', 'links_log.device_id')
				->whereRaw('date(from_unixtime(links_log.created_at)) = CURDATE()')
				->where('links.status', '<>', '2')
				->where('links_log.link_reset', '=', '0')
				->whereRaw('links_log.type = ?  AND links_log.link_id = ?', [ '0', $link_id ])
				->orderBy('links_log.updated_at', 'desc')
				->get();
			
			
			$filtered_links_clicks = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select(DB::raw('Count(links_log.unique_click) as total_clicks'), 'links_log.link_id', DB::raw('DATE_FORMAT(from_unixtime(links_log.updated_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(links_log.created_at)) = CURDATE()')
				->whereRaw('links_log.type = ?  AND links_log.link_id = ?', [ '0', $link_id ])
				->where('links.status', '<>', 'Deleted')
				->where('links_log.link_reset', '=', '0')
				->groupBy(DB::raw('hour((from_unixtime(links_log.updated_at)))'))
				->get();
			
			
			$j = 0;
			
			$total_pclicks = 0;
			$hour_clicks   = [];
			
			if ( sizeof($filtered_links_clicks) > 0 ) {
				foreach ( $filtered_links_clicks as $key => $values ) {
					$hour_clicks[$values->created_at] = $values->total_clicks;
					
					$total_pclicks += $values->total_clicks * 1;
					$j++;
				}
			}
			
			$yAxisTotalDate = [ '12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM' ];
			foreach ( $yAxisTotalDate as $key => $dateInterval ) {
				$total_clicks[$key]['date'] = $dateInterval;
				
				if ( array_key_exists($dateInterval, $hour_clicks) ) {
					$total_clicks[$key]['total_clicks'] = $hour_clicks[$dateInterval];
				} else {
					$total_clicks[$key]['total_clicks'] = 0;
				}
			}
			
			return [ 0 => $total_clicks, 1 => $link_log ];
		}
	}
	
	/**
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getClickedList($calendar_numeric, $date_interval, $id)
	{
		$QueryInterval = 'INTERVAL ' . $calendar_numeric . ' ' . $date_interval;
		
		if ( $calendar_numeric > 1 ) {
			$link_log = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select('links_log.id', 'links_log.link_id', 'links_log.referer_id', 'links_log.client_ip', 'links_log.unique_click', 'links_log.unique_click_per_day', DB::raw('from_unixtime(links_log.created_at) as created_at'), 'links_log.url', 'links_log.geoip_id', 'links_log.agent_id', 'links_log.device_id')
				->whereRaw('date(from_unixtime(links_log.created_at)) >= date_sub(now(), ' . $QueryInterval . ')')
				->where('links.status', '<>', '2')
				->where('links_log.link_reset', '=', '0')
				->whereRaw('links_log.type =? AND links_log.link_id =?', [ '0', $id ])
				->orderBy('links_log.created_at', 'desc')
				->paginate(10);
		} else {
			$link_log = Tenant::DB()->table('links_log')
				->join('links', 'links.id', '=', 'links_log.link_id')
				->select('links_log.id', 'links_log.link_id', 'links_log.referer_id', 'links_log.client_ip', 'links_log.unique_click', 'links_log.unique_click_per_day', DB::raw('from_unixtime(links_log.created_at) as created_at'), 'links_log.url', 'links_log.geoip_id', 'links_log.agent_id', 'links_log.device_id')
				->whereRaw('date(from_unixtime(links_log.created_at)) = CURDATE()')
				->where('links.status', '<>', '2')
				->where('links_log.link_reset', '=', '0')
				->whereRaw('links_log.type =? AND links_log.link_id =?', [ '0', $id ])
				->orderBy('links_log.created_at', 'desc')
				->paginate(10);
		}
		
		return $link_log;
	}
	
	/**
	 * @param $link_id
	 *
	 * @return mixed
	 */
	public function getUniqueActionCount($link_id)
	{
		$action_count = Tenant::DB()->table('links_log')->whereRaw('link_id = ? AND unique_click_per_day = ? AND type = ? AND filtered_click = ? AND link_reset = ?', [ $link_id, '1', '1', '0', '0' ])->count();
		
		return $action_count;
	}
	
	/**
	 * @param $link_id
	 *
	 * @return mixed
	 */
	public function getUniqueEngagementCount($link_id)
	{
		$engagement_count = Tenant::DB()->table('links_log')->whereRaw('link_id = ? AND unique_click_per_day = ? AND type = ? AND filtered_click = ? AND link_reset = ?', [ $link_id, '1', '3', '0', '0' ])->count();
		
		return $engagement_count;
	}
	
	/**
	 * @param $link_id
	 *
	 * @return mixed
	 */
	public function getUniqueSalesCount($link_id)
	{
		$sales_count = Tenant::DB()->table('links_log')->whereRaw('link_id = ? AND unique_click_per_day = ? AND type = ? AND filtered_click = ? AND link_reset = ?', [ $link_id, '1', '2', '0', '0' ])->count();
		
		return $sales_count;
	}
	
	/**
	 * @param $start_date
	 * @param $end_date
	 * @param $link_type
	 * @param $action
	 * @param $duration
	 *
	 * @return array
	 */
	public function getLinkLogs($start_date, $end_date, $link_type, $action, $duration)
	{
		$clicks = $total_clicks = [];
		
		$linkQuery = Tenant::DB()->table('links_log')
			->join('links', 'links.id', '=', 'links_log.link_id')
			->whereRaw('date(from_unixtime(links_log.created_at)) >= "' . $start_date . '"')
			->whereRaw('date(from_unixtime(links_log.created_at)) <= "' . $end_date . '"')
			->where('links_log.link_reset', '=', '0')
			->where('links.status', '<>', 'Deleted');
		
		switch ( $link_type ) {
			case 'all-links':
				$linkQuery = $linkQuery->where('links.link_type', '=', 'all-links');
				break;
			case 'archived-link':
				$linkQuery = $linkQuery->where('links.link_type', '=', 'archived-link');
				break;
		}
		
		switch ( $action ) {
			case 'action':
				$linkQuery = $linkQuery->where('links_log.type', '=', '1');
				break;
			case 'sales':
				$linkQuery = $linkQuery->where('links_log.type', '=', '2');
				break;
			case 'event':
				$linkQuery = $linkQuery->where('links_log.type', '=', '3');
				break;
		}
		
		$yAxisTotalDate = [];
		
		switch ( $duration ) {
			case 'hour':
				$linkQuery = $linkQuery
					->select(DB::raw('COUNT(links_log.unique_click) AS total_clicks'), 'links_log.link_id', DB::raw('DATE_FORMAT(FROM_UNIXTIME(links_log.created_at), "%l%p") AS created_at'))
					->groupBy(DB::raw('HOUR((FROM_UNIXTIME(links_log.created_at)))'));
				
				$yAxisTotalDate = [ '12AM', '1AM', '2AM', '3AM', '4AM', '5AM', '6AM', '7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM', '9PM', '10PM', '11PM' ];
				break;
			case 'week':
				$linkQuery = $linkQuery->select(DB::raw('COUNT(links_log.unique_click) AS total_clicks'), 'links_log.link_id', DB::raw('DAYNAME(FROM_UNIXTIME(links_log.created_at)) AS created_at'))
					->groupBy(DB::raw('DAYNAME((FROM_UNIXTIME(links_log.created_at)))'));
				
				$yAxisTotalDate = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
				break;
			case 'month':
				$linkQuery = $linkQuery->select(DB::raw('COUNT(links_log.unique_click) AS total_clicks'), 'links_log.link_id', DB::raw('MONTHNAME(FROM_UNIXTIME(links_log.created_at)) AS created_at'))
					->groupBy(DB::raw('MONTHNAME((FROM_UNIXTIME(links_log.created_at)))'));
				
				$yAxisTotalDate = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
				break;
		}
		
		$linkQuery = $linkQuery->get();
		
		if ( count($linkQuery) > 0 ) {
			foreach ( $linkQuery as $key => $values ) {
				$clicks[$values->created_at] = $values->total_clicks * 1;
			}
		}
		
		if ( sizeof($yAxisTotalDate) > 0 ) {
			foreach ( $yAxisTotalDate as $key => $dateInterval ) {
				if ( array_key_exists($dateInterval, $clicks) ) {
					$total_clicks[$key] = $clicks[$dateInterval] * 1;
				} else {
					$total_clicks[$key] = 0;
				}
			}
		}
		
		return $total_clicks;
	}
	
	/*** Tracker ***/
	
	/**
	 * @param $activated_on
	 *
	 * @return mixed
	 */
	public function getTotalClickCount($activated_on)
	{
		$a_date = date("Y-m-d", strtotime($activated_on));
		$c_date = date("Y-m-d");
		
		$a_date1 = date_create($a_date);
		$c_date1 = date_create($c_date);
		
		$diff   = date_diff($a_date1, $c_date1);
		$b_days = $diff->format('%R%a') * 1;
		
		if ( $b_days < 30 ) {
			$f_date = $a_date;
		} else {
			$click_days = (($b_days % 30) * (-1)) . ' days';
			$f_date     = date("Y-m-d", strtotime($click_days, strtotime($c_date)));
		}
		
		$link_logs = Tenant::DB()->table('links_log')
			->where('links_log.link_reset', '=', '0')
			->whereRaw('DATE(from_unixtime(links_log.updated_at)) BETWEEN "' . $f_date . '" AND "' . $c_date . '"')
			->where('links_log.unique_click_per_day', '=', '1')->count();
		
		$rotator_logs = Tenant::DB()->table('rotators_log')
			->where('rotators_log.rotator_reset', '=', '0')
			->whereRaw('DATE(from_unixtime(rotators_log.updated_at)) BETWEEN "' . $f_date . '" AND "' . $c_date . '"')
			->where('rotators_log.unique_click_per_day', '=', '1')->count();
		
		return (($link_logs * 1) + ($rotator_logs * 1));
	}
	
	/**
	 * @param $link_id
	 *
	 * @return int
	 */
	public function getUniqueClicksPerday($link_id)
	{
		$unique_clicks_perday = 0;
		$link_details         = $this->model()->select('unique_click_per_day')->where('id', '=', $link_id)->first();
		if ( count($link_details) > 0 ) {
			$unique_clicks_perday = $link_details->unique_click_per_day;
		}
		
		return $unique_clicks_perday;
	}
	
	public function chkUniqueClick($cookie_id, $client_ip, $link_id)
	{
		$exists = Tenant::DB()->table('links_log')->whereRaw('cookie_id = ? AND link_id = ? AND client_ip = ? AND type = ?', [ $cookie_id, $link_id, $client_ip, '0' ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkUniqueClickPerDay($cookie_id, $client_ip, $link_id)
	{
		$exists = Tenant::DB()->table('links_log')
			->whereRaw('cookie_id = ? AND link_id = ? AND client_ip = ? AND type = ?', [ $cookie_id, $link_id, $client_ip, '0' ])
			->whereRaw('from_unixtime(links_log.created_at) >= DATE_ADD(CURDATE(), INTERVAL 0 DAY)')
			->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkSmartSwapLinkAlreadyVisited($cookie_id, $link_id)
	{
		$exists = Tenant::DB()->table('links_log')->whereRaw('link_id = ? AND cookie_id = ? AND type = ?', [ $link_id, $cookie_id, '0' ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkSmartSwapRotatorAlreadyVisited($cookie_id, $rotator_id)
	{
		$exists = Tenant::DB()->table('rotators_log')->whereRaw('rotator_id = ? AND cookie_id = ?', [ $rotator_id, $cookie_id ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkSplitUrlExists($link_id)
	{
		return Tenant::DB()->table('links_split_url')->whereRaw('link_id = ? AND is_deleted = ? AND primary_url = ?', [ $link_id, '0', '0' ])->count();
	}
	
	public function getLastCookieDetail($finger_print, $link_id)
	{
		return Tenant::DB()->table('links_log')->where('cookie_id', '=', $finger_print)->where('link_id', '=', $link_id)->first();
	}
	
	public function checkCookieIdExists($finger_print, $link_id)
	{
		return Tenant::DB()->table('links_log')->where('cookie_id', '=', $finger_print)->where('link_id', '=', $link_id)->count();
	}
	
	public function chkSplitUrlIdExists($split_url_id)
	{
		return Tenant::DB()->table('links_split_url')->select('split_url')->whereRaw('id = ? AND is_deleted = ? ', [ $split_url_id, '0' ])->first();
	}
	
	public function chkPrimaryUrlExists($primary_url, $link_id)
	{
		return $this->model()->select('id')->whereRaw('primary_url = ? AND id != ? AND status != ?', [ $primary_url, $link_id, 'Deleted' ])->get();
	}
	
	public function chkTrackingLinkVisited($cookie_id, $client_ip, $link_id)
	{
		$exists = Tenant::DB()->table('links_log')->whereIn('link_id', $link_id)->whereRaw('cookie_id = ? AND type = ?', [ $cookie_id, '0' ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function updateTrack($id, $update_arr)
	{
		$this->model()->where('id', '=', $id)->update($update_arr);
	}
	
	public function insertLog($data_arr)
	{
		Tenant::DB()->table('links_log')->insert($data_arr);
	}
	
	public function chkValidLinkName($link_name)
	{
		$exists = $this->model()->where('tracking_link', '=', $link_name)->whereRaw('status = ?', [ 'Active' ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function getLinkDetailsByName($link_name)
	{
		$link_details = $this->model()->select('id', 'user_id', 'primary_url', 'cloak_link', 'cloak_page_title', 'cloak_page_description', 'cloak_page_image_url', 'popup_id',
			'password', 'max_clicks', 'backup_url', 'total_clicks', 'unique_clicks', 'unique_click_per_day', 'geo_targeting', 'geo_targeting_include_countries',
			'geo_targeting_exclude_countries', 'magickbar_id', 'timer_id', 'mobile_url', 'repeat_url', 'tracking_link_visited', 'smartswap_id', 'smartswap_type', 'pixel_code')
			->where('tracking_link', '=', $link_name)->whereRaw('status != ?', [ 'Deleted' ])->first();
		
		return $link_details;
	}
	
	public function chkUniqueTracking($client_ip, $link_id, $type)
	{
		$exists = Tenant::DB()->table('links_log')->whereRaw('client_ip = ? AND link_id = ? AND type = ?', [ $client_ip, $link_id, $type ])
			->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkUniqueTrackingPerDay($client_ip, $link_id, $type)
	{
		$QueryInterval = 'INTERVAL 0 DAY';
		
		$exists = Tenant::DB()->table('links_log')->whereRaw('client_ip = ? AND link_id = ? AND type = ?', [ $client_ip, $link_id, $type ])
			->whereRaw('from_unixtime(links_log.created_at) >= DATE_ADD(CURDATE(), ' . $QueryInterval . ')')
			->count();
		
		return ($exists > 0) ? true : false;
	}
}