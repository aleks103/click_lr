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
use App\Models\Rotators;
use Illuminate\Support\Facades\DB;

class RotatorsRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(Rotators::class);
	}
	
	/**
	 * Get rotators all data
	 *
	 * @return mixed
	 */
	public function getAll()
	{
		$rotators = $this->model()->where('status', '<>', '2')->get();
		
		return $rotators;
	}
	
	/**
	 * Get rotators graph data
	 *
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $d_arr
	 *
	 * @return array
	 */
	public function getRotatorsXYAxisArray($calendar_numeric, $date_interval, $d_arr)
	{
		$QueryInterval = 'interval ' . $calendar_numeric . ' ' . $date_interval;
		
		$this->model()->where('status', '=', '2')->delete();
		
		$current_date    = date('d-m-Y');
		$table_name      = 'rotators_log';
		$rotators_id     = $d_arr['rotators_id'];
		$rotators_url_id = $d_arr['rotators_url_id'];
		
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
				->select('id', 'unique_click', DB::raw('date(from_unixtime(updated_at)) as `date`'))
				->where('rotator_reset', '=', '0')
				->whereRaw('date(from_unixtime(updated_at)) >= date_sub(now(), ' . $QueryInterval . ')');
			
			if ( $rotators_id == 0 && $rotators_url_id == 0 ) {
				$date_wise_clicks_logs = $date_wise_clicks_logs->get();
			} else {
				if ( $rotators_id > 0 ) {
					$date_wise_clicks_logs = $date_wise_clicks_logs
						->where('rotator_id', '=', $rotators_id)
						->get();
				}
				
				if ( $rotators_url_id > 0 ) {
					$date_wise_clicks_logs = Tenant::DB()->table($table_name)
						->select('id', 'unique_click', DB::raw('date(from_unixtime(updated_at)) as `date`'))
						->where('rotator_reset', '=', '0')
						->where('rotator_url_id', '=', $rotators_url_id)
						->whereRaw('date(from_unixtime(updated_at)) >= date_sub(now(), ' . $QueryInterval . ')')
						->get();
				}
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
				->select('rotator_id', DB::raw('count(unique_click) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('rotator_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $rotators_id > 0 ) {
				$calc_data['totalClicks'] = $calc_data['totalClicks']->where('rotator_id', '=', $rotators_id);
			}
			if ( $rotators_url_id > 0 ) {
				$calc_data['totalClicks'] = $calc_data['totalClicks']->where('rotator_url_id', '=', $rotators_url_id);
			}
			$calc_data['totalClicks'] = $calc_data['totalClicks']->get();
			
			$calc_data['totalUniqueClicks'] = Tenant::DB()->table($table_name)
				->select('rotator_id', DB::raw('count(unique_click) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('unique_click', '=', '1')
				->where('rotator_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $rotators_id > 0 ) {
				$calc_data['totalUniqueClicks'] = $calc_data['totalUniqueClicks']->where('rotator_id', '=', $rotators_id);
			}
			if ( $rotators_url_id > 0 ) {
				$calc_data['totalUniqueClicks'] = $calc_data['totalUniqueClicks']->where('rotator_url_id', '=', $rotators_url_id);
			}
			$calc_data['totalUniqueClicks'] = $calc_data['totalUniqueClicks']->get();
			
			$calc_data['totalNonUniqueClicks'] = Tenant::DB()->table($table_name)
				->select('rotator_id', DB::raw('count(unique_click) as total_clicks'), DB::raw('DATE_FORMAT(from_unixtime(created_at), "%l%p") as created_at'))
				->whereRaw('date(from_unixtime(created_at)) = curdate()')
				->where('unique_click', '=', '0')
				->where('rotator_reset', '=', '0')
				->groupBy(DB::raw('hour(from_unixtime(created_at))'))
				->orderBy(DB::raw('hour(from_unixtime(created_at))'));
			if ( $rotators_id > 0 ) {
				$calc_data['totalNonUniqueClicks'] = $calc_data['totalNonUniqueClicks']->where('rotator_id', '=', $rotators_id);
			}
			if ( $rotators_url_id > 0 ) {
				$calc_data['totalNonUniqueClicks'] = $calc_data['totalNonUniqueClicks']->where('rotator_url_id', '=', $rotators_url_id);
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
	 * @param $calendar_numeric
	 * @param $date_interval
	 * @param $id
	 *
	 * @return mixed
	 */
	public function getClickedList($calendar_numeric, $date_interval, $id)
	{
		$rotators_id = $id['rotators_id'];
		
		$rotators_url_id = $id['rotators_url_id'];
		
		$QueryInterval = 'INTERVAL ' . $calendar_numeric . ' ' . $date_interval;
		
		if ( $calendar_numeric > 1 ) {
			$rotators_log = Tenant::DB()->table('rotators_log')
				->join('rotators', 'rotators.id', '=', 'rotators_log.rotator_id')
				->select('rotators_log.id', 'rotators_log.rotator_id', 'rotators_log.referer_id', 'rotators_log.client_ip', 'rotators_log.unique_click', DB::raw('from_unixtime(rotators_log.created_at) as created_at'), 'rotators_log.url', 'rotators_log.geoip_id', 'rotators_log.agent_id', 'rotators_log.device_id')
				->whereRaw('date(from_unixtime(rotators_log.created_at)) >= date_sub(now(), ' . $QueryInterval . ')')
				->where('rotators.status', '<>', '2')
				->where('rotators_log.rotator_reset', '=', '0');
			
			if ( $rotators_url_id > 0 ) {
				$rotators_log = $rotators_log->where('rotators_log.rotator_url_id', '=', $rotators_id);
			} else {
				$rotators_log = $rotators_log->where('rotators_log.rotator_id', '=', $rotators_id);
			}
			$rotators_log = $rotators_log->orderBy('rotators_log.created_at', 'desc')
				->paginate(10);
		} else {
			$rotators_log = Tenant::DB()->table('rotators_log')
				->join('rotators', 'rotators.id', '=', 'rotators_log.rotator_id')
				->select('rotators_log.id', 'rotators_log.rotator_id', 'rotators_log.referer_id', 'rotators_log.client_ip', 'rotators_log.unique_click', DB::raw('from_unixtime(rotators_log.created_at) as created_at'), 'rotators_log.url', 'rotators_log.geoip_id', 'rotators_log.agent_id', 'rotators_log.device_id')
				->whereRaw('date(from_unixtime(rotators_log.created_at)) = CURDATE()')
				->where('rotators.status', '<>', '2')
				->where('rotators_log.rotator_reset', '=', '0');
			
			if ( $rotators_url_id > 0 ) {
				$rotators_log = $rotators_log->where('rotators_log.rotator_url_id', '=', $rotators_id);
			} else {
				$rotators_log = $rotators_log->where('rotators_log.rotator_id', '=', $rotators_id);
			}
			$rotators_log = $rotators_log->orderBy('rotators_log.created_at', 'desc')
				->paginate(10);
		}
		
		return $rotators_log;
	}
	
	public function getRotatorsGroup($parent_id = null)
	{
		$rotators_group_arr = [];
		
		$rotators_group = Tenant::DB()->table('rotator_groups')->select('id', 'rotator_group', 'parent_id')
			->where('status', '<>', '2')
			->orderBy('parent_id', 'asc')
			->orderBy('created_at', 'desc');
		
		if ( isset($parent_id) ) {
			$rotators_group = $rotators_group->where('parent_id', '=', '0');
		}
		
		$rotators_group = $rotators_group->get();
		
		if ( sizeof($rotators_group) > 0 ) {
			foreach ( $rotators_group as $row ) {
				$rotators_group_arr[$row->id] = (($row->parent_id != 0) ? $this->getGroupValueById($row->parent_id)->rotator_group . "-" : "") . $row->rotator_group;
			}
		}
		
		return $rotators_group_arr;
	}
	
	public function getPluckRotatorsGroup($rotators_id)
	{
		return Tenant::DB()->table('rotator_groups')->where('id', $rotators_id)->pluck('rotator_group');
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
		return Tenant::DB()->table('rotator_groups')->find($id);
	}
	
	/**
	 * @param string $start_date
	 * @param string $end_date
	 * @param string $search_name
	 * @param string $group_id
	 *
	 * @return mixed
	 */
	public function buildRotatorsQuery($start_date = '', $end_date = '', $search_name = '', $group_id = 'all_rotators')
	{
		$qry = $this->model()->where('status', '=', '0');
		
		if ( $start_date != '' && $end_date != '' ) {
			$qry = $qry->whereRaw("(from_unixtime(created_at, '%Y-%m-%d') >= ? AND from_unixtime(created_at, '%Y-%m-%d') <= ?)", [ $start_date, $end_date ]);
		}
		
		if ( $search_name != '' ) {
			$qry = $qry->where('rotator_name', 'like', '%' . $search_name . '%');
		}
		
		if ( $group_id != 'all_rotators' )
			$qry = $qry->where('rotator_group_id', '=', $group_id);
		
		$qry->orderBy('created_at', 'desc');
		return $qry;
	}
	
	/**
	 * @param      $id
	 * @param      $start_date
	 * @param      $end_date
	 * @param      $url_id
	 * @param bool $unique
	 *
	 * @return mixed
	 */
	public function rotatorsClickCountById($id, $start_date, $end_date, $url_id, $unique = false)
	{
		$qry = Tenant::DB()->table('rotators_log')
			->select('id')
			->where('rotator_reset', '=', '0')
			->where('rotator_id', '=', $id)
			->whereRaw('date(from_unixtime(`updated_at`)) >= ? AND date(from_unixtime(`updated_at`)) <= ?', [ $start_date, $end_date ]);
		
		if ( $url_id > 0 ) {
			$qry = Tenant::DB()->table('rotators_log')
				->select('id')
				->where('rotator_reset', '=', '0')
				->where('rotator_url_id', '=', $url_id)
				->whereRaw('date(from_unixtime(`updated_at`)) >= ? AND date(from_unixtime(`updated_at`)) <= ?', [ $start_date, $end_date ]);
		}
		
		if ( $unique ) {
			$qry = $qry->where('unique_click_per_day', '=', '1');
		}
		
		return $qry->count();
	}
	
	/**
	 * get Rotators sub urls
	 *
	 * @param        $rotators_id
	 * @param string $url_type
	 *
	 * @return mixed
	 */
	public function getRotatorsUrls($rotators_id, $url_type = 'all')
	{
		$details = Tenant::DB()->table('rotator_urls')->Select('id', 'name', 'url', 'position', 'max_clicks', 'max_daily_clicks', 'bonus', 'min_t1', 'max_t1', 'min_mobile',
			'max_mobile', 'start_date', 'end_date', DB::raw("from_unixtime(start_date, '%d/%m/%Y %H:%i:%s') as url_start_date"),
			DB::raw("from_unixtime(end_date, '%d/%m/%Y %H:%i:%s') as url_end_date"), 'notes', 'notify_max_clicks_reached', 'status', 'total_clicks', 'unique_clicks', 'rotator_id')
			->where('rotator_id', '=', $rotators_id)->where('status', '!=', '3');
		if ( $url_type == 'active' ) {
			$details = $details->whereRaw('status = ?', [ '0' ]);
		} else if ( $url_type == 'paused' ) {
			$details = $details->whereRaw('status = ?', [ '1' ]);
		} else if ( $url_type == 'archived' ) {
			$details = $details->whereRaw('status = ?', [ '2' ]);
		} else if ( $url_type == 'allnotarchive' ) {
			$details = $details->whereRaw('status != ?', [ '2' ]);
		}
		$details = $details->orderBy('position', 'asc')->get();
		
		return $details;
	}
	
	/**
	 * @param $id
	 * @param $url_id
	 *
	 * @return mixed
	 */
	public function getUrlTodayClicks($id, $url_id)
	{
		return Tenant::DB()->table('rotators_log')
			->whereRaw('rotator_id = ? AND rotator_url_id = ? AND rotator_reset = ? AND filtered_click = ? AND unique_click = ?', [ $id, $url_id, '0', '0', '1' ])
			->whereRaw("(from_unixtime(rotators_log.created_at, '%Y-%m-%d') = CURDATE())")->count();
	}
	
	/**
	 * @param $rotator_url_id
	 *
	 * @return mixed
	 */
	public function getRotatorsUrlById($rotator_url_id)
	{
		return Tenant::DB()->table('rotator_urls')->where('id', '=', $rotator_url_id)->first();
	}
	
	/**
	 * @param $id
	 */
	public function deleteRotatorsUrl($id)
	{
		Tenant::DB()->table('rotators_log')->where('rotator_url_id', '=', $id)->delete();
	}
	
	/**
	 * @param $rotators
	 */
	public function deleteRotatorsLog($rotators)
	{
		//		Tenant::DB()->table('rotators_log')->where('rotator_id', '=', $rotators->id)->update([ 'rotator_reset' => '1' ]);
		Tenant::DB()->table('rotators_log')->where('rotator_id', '=', $rotators->id)->delete();
	}
	
	public function getRotatorDetailsbyName($rotator_name)
	{
		$rotator_details = $this->model()->select('id', 'user_id', 'rotator_link', 'rotator_mode', 'on_finish', 'cloak_rotator',
			'cloak_page_title', 'cloak_page_description', 'cloak_page_image_url', 'backup_url',
			'popup_id', 'magickbar_id', 'timer_id', 'cookie_duration', 'mobile_url', 'geo_targeting',
			'geo_targeting_include_countries', 'geo_targeting_exclude_countries', 'pixel_code')
			->where('rotator_link', '=', $rotator_name)->whereRaw('status = ?', [ '0' ])->first();
		
		return $rotator_details;
	}
	
	public function getUniqueClickPerDay($rotator_id)
	{
		$unique_click_per_day = 0;
		
		$rotator_details = $this->model()->select('unique_click_per_day')->where('id', '=', $rotator_id)->first();
		if ( count($rotator_details) > 0 ) {
			$unique_click_per_day = $rotator_details->unique_click_per_day;
		}
		
		return $unique_click_per_day;
	}
	
	public function getRotatorUrlsForTrack($rotator_id)
	{
		$details = Tenant::DB()->table('rotator_urls')->select('id', 'name', 'url', 'position', 'max_clicks', 'max_daily_clicks', 'bonus', 'min_t1', 'max_t1', 'min_mobile',
			'max_mobile', 'start_date', 'end_date', 'notes', 'notify_max_clicks_reached', 'status', 'total_clicks', 'unique_clicks', 'unique_click_per_day')
			->where('rotator_id', '=', $rotator_id)->where('status', '!=', '3')
			->whereRaw('((start_date = "" AND end_date = "") OR ((from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW())) OR ( start_date = "" AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR ( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND end_date = "" ))')
			->orderBy('position', 'asc')->get();
		
		return $details;
	}
	
	public function addRotatorUrlNotification($user_id, $rotator_id, $rotator_url_id)
	{
		$data_arr = [];
		
		$data_arr['user_id'] = $user_id;
		
		$data_arr['rotator_id']     = $rotator_id;
		$data_arr['rotator_url_id'] = $rotator_url_id;
		
		$data_arr['notification_sent'] = '0';
		
		$data_arr['created_at'] = time();
		$data_arr['updated_at'] = time();
		
		DB::table('rotator_url_notification')->insertGetId($data_arr);
	}
	
	public function getRotatorLogDetails($rotator_id, $rotator_url_id)
	{
		$rotator_log_details = Tenant::DB()->table('rotators_log')
			->whereRaw('rotator_id = ? AND rotator_url_id = ? AND rotator_reset = ? AND filtered_click = ?', [ $rotator_id, $rotator_url_id, '0', '0' ])
			->get();
		
		return $rotator_log_details;
	}
	
	public function getActiveRotatorUrl($rotator_id, $mobile_restrict_url)
	{
		$rotator_details = Tenant::DB()->table('rotator_urls')->whereRaw('rotator_id = ? AND status = ?', [ $rotator_id, '0' ])->whereRaw('((start_date = "" AND end_date = "") OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( start_date = "" AND  (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND  end_date = "" ))');
		if ( count($mobile_restrict_url) > 0 ) {
			$rotator_details = $rotator_details->whereNotIn('id', $mobile_restrict_url);
		}
		$rotator_details = $rotator_details->orderBy('position', 'asc')->first();
		
		return $rotator_details;
	}
	
	public function getLastVisitedRotatorUrl($cookie_id, $client_ip, $rotator_id)
	{
		$rotator_log_details = [];
		
		$rotator_details = Tenant::DB()->table('rotators_log')->whereRaw('cookie_id = ? AND rotator_id = ? AND rotator_url_id > 0 AND on_finish_url = ?', [ $cookie_id, $rotator_id, '0' ])->orderBy('rotators_log.id', 'desc')->first();
		if ( count($rotator_details) > 0 ) {
			$rotator_log_details = $rotator_details;
		}
		
		return $rotator_log_details;
	}
	
	public function getNextRotatorUrl($rotator_id, $url_id, $mobile_restrict_url)
	{
		$rotator_details = Tenant::DB()->table('rotator_urls')->whereRaw('rotator_id = ? AND status = ? AND id > ?', [ $rotator_id, '0', $url_id ])->whereRaw('((start_date = "" AND end_date = "") OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( start_date = "" AND  (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND  end_date = "" ))');
		if ( count($mobile_restrict_url) > 0 ) {
			$rotator_details = $rotator_details->whereNotIn('id', $mobile_restrict_url);
		}
		$rotator_details = $rotator_details->orderBy('position', 'asc')->first();
		
		return $rotator_details;
	}
	
	public function getRandomRotatorUrl($rotator_id, $mobile_restrict_url)
	{
		$rotator_details = Tenant::DB()->table('rotator_urls')->whereRaw('rotator_id = ? AND status = ?', [ $rotator_id, '0' ])->whereRaw('((start_date = "" AND end_date = "") OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( start_date = "" AND  (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND  end_date = "" ))');
		if ( count($mobile_restrict_url) > 0 ) {
			$rotator_details = $rotator_details->whereNotIn('id', $mobile_restrict_url);
		}
		$rotator_details = $rotator_details->orderBy(DB::Raw('RAND()'))->first();
		
		return $rotator_details;
	}
	
	public function getLastRotatorUrl($rotator_id, $mobile_restrict_url)
	{
		$rotator_details = Tenant::DB()->table('rotator_urls')->whereRaw('rotator_id = ? AND status = ?', [ $rotator_id, '0' ])->whereRaw('((start_date = "" AND end_date = "") OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( start_date = "" AND  (from_unixtime(end_date, "%Y-%m-%d %H:%i:%s") >= NOW()) ) OR( (from_unixtime(start_date, "%Y-%m-%d %H:%i:%s") <= NOW()) AND  end_date = "" ))');
		if ( count($mobile_restrict_url) > 0 ) {
			$rotator_details = $rotator_details->whereNotIn('id', $mobile_restrict_url);
		}
		$rotator_details = $rotator_details->orderBy('position', 'desc')->first();
		
		return $rotator_details;
	}
	
	public function chkValidRotatorName($rotator_name)
	{
		$exists = $this->model()->where('rotator_link', '=', $rotator_name)->whereRaw('status = ?', [ '0' ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkUniqueClick($cookie_id, $client_ip, $rotator_id, $rotator_url_id)
	{
		$exists = Tenant::DB()->table('rotators_log')->whereRaw('cookie_id = ? AND rotator_id = ? AND client_ip = ? AND rotator_url_id = ?', [ $cookie_id, $rotator_id, $client_ip, $rotator_url_id ])->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function chkUniqueClickPerDay($cookie_id, $client_ip, $rotator_id, $rotator_url_id)
	{
		$QueryInterval = 'INTERVAL 0 DAY';
		
		$exists = Tenant::DB()->table('rotators_log')
			->whereRaw('cookie_id = ? AND rotator_id = ? AND client_ip = ? AND rotator_url_id = ?', [ $cookie_id, $rotator_id, $client_ip, $rotator_url_id ])
			->whereRaw('from_unixtime(rotators_log.created_at) >= DATE_ADD(CURDATE(), ' . $QueryInterval . ')')
			->count();
		
		return ($exists > 0) ? true : false;
	}
	
	public function updateTrack($id, $update_arr)
	{
		$this->model()->where('id', '=', $id)->update($update_arr);
	}
	
	public function updateTrackUrl($id, $update_arr)
	{
		Tenant::DB()->table('rotator_urls')->where('id', '=', $id)->update($update_arr);
	}
	
	public function insertLog($data_arr)
	{
		Tenant::DB()->table('rotators_log')->insert($data_arr);
	}
	
	public function getRotatorUrlsById($rotator_url_id)
	{
		$details = Tenant::DB()->table('rotator_urls')->Select('id', 'name', 'url', 'rotator_id', 'position', 'max_clicks', 'max_daily_clicks', 'bonus', 'min_mobile', 'max_mobile', 'start_date', 'end_date', DB::raw("from_unixtime(start_date, '%d-%m-%Y') as url_start_date"), DB::raw("from_unixtime(end_date, '%d-%m-%Y') as url_end_date"), 'notes', 'notify_max_clicks_reached', 'status', 'geo_targeting', 'geo_targeting_include_countries', 'geo_targeting_exclude_countries', 'popup_id', 'magickbar_id', 'timer_id', 'total_clicks', 'unique_clicks', 'unique_click_per_day')
			->where('id', '=', $rotator_url_id)
			->first();
		
		return $details;
	}
}