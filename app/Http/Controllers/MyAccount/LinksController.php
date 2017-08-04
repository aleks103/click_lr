<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\DataService;
use App\Http\Repositories\Accounts\LinksRepository;
use App\Http\Repositories\LinkRotatorsAlertRepository;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LinksController extends Controller
{
	protected $linksRepo;
	
	public function __construct(LinksRepository $linksRepository)
	{
		$this->middleware('auth');
		$this->linksRepo = $linksRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$searchParams = [
			'link_name'  => '',
			'link_type'  => 'all-links',
			'start_date' => '',
			'end_date'   => '',
			'page'       => 1
		];
		
		if ( $request->has('link_name') ) {
			$searchParams['link_name'] = $request->input('link_name');
		}
		
		if ( $request->has('link_type') ) {
			$searchParams['link_type'] = $request->input('link_type');
		}
		
		if ( $request->has('start_date') ) {
			$searchParams['start_date'] = $request->input('start_date');
		}
		
		if ( $request->has('end_date') ) {
			$searchParams['end_date'] = $request->input('end_date');
		}
		
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->input('page');
		} else {
			$searchParams['page'] = 1;
		}
		
		$links_group = $this->linksRepo->getLinkGroups();
		
		$start_date = ($searchParams['start_date'] != '') ? $searchParams['start_date'] : config('site.start_date');
		$end_date   = ($searchParams['end_date'] != '') ? $searchParams['end_date'] : date('Y-m-d');
		
		$links_query = $this->linksRepo->buildLinksQuery($start_date, $end_date, $searchParams['link_name'], $searchParams['link_type']);
		
		$links_list = $links_query->paginate(20);
		
		$links = [];
		
		if ( sizeof($links_list) > 0 ) {
			foreach ( $links_list as $key => $row ) {
				$total_clicks  = $this->linksRepo->linkClickCountById($row->id, $start_date, $end_date);
				$unique_clicks = $this->linksRepo->linkClickCountById($row->id, $start_date, $end_date, true);
				
				if ( $row->tracking_domain > 0 ) {
					$tracking_domain = DataService::getTrackingDomain($row->tracking_domain, '1');
					
					if ( substr($tracking_domain, -1, 1) == '/' ) {
						$tracking_domain = substr($tracking_domain, 0, -1);
					}
				} else {
					if ( config('site.site_domain_name') == 'clickperfect' ) {
						$tracking_url = 'http://';
						
						if ( auth()->user()->domain != '' ) {
							$tracking_url .= strtolower(auth()->user()->domain) . '.';
						}
						
						$tracking_domain = $tracking_url . config('site.custom_domains')[$row->tracking_domain] . '/go';
					} else {
						$tracking_domain = $request->root() . '/go';
					}
				}
				
				$links[$key] = $row;
				
				$links[$key]['full_name'] = (isset($row->link_name) && $row->link_name != '') ? $row->link_name : $row->tracking_link;
				$links[$key]['link_name'] = (strlen($links[$key]['full_name']) > 8) ? substr($links[$key]['full_name'], 0, 8) . '...' : $links[$key]['full_name'];
				$links[$key]['link_name'] = htmlentities($links[$key]['link_name']);
				
				$links[$key]['total_clicks']  = $total_clicks;
				$links[$key]['unique_clicks'] = $unique_clicks;
				
				$links[$key]['preview_url'] = $tracking_domain . '/' . $row->tracking_link;
			}
		}
		
		return response()->view('users.linksList', compact('links_group', 'searchParams', 'links_list', 'links'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @param String $sub_domain
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($sub_domain)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		
		return response()->view('users.linksAdd');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param String                    $sub_domain
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function store($sub_domain, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		
		$rules = [
			'link_name'     => 'min:4|max:255|unique:mysql_tenant.links|nullable',
			'tracking_link' => 'required|alpha_dash|min:4|max:255|unique:mysql_tenant.links',
			'primary_url'   => 'required|url|max:255',
			'max_clicks'    => 'numeric|nullable',
			'mobile_url'    => 'url|different:primary_url|nullable',
			'repeat_url'    => 'url|different:primary_url|nullable',
			'backup_url'    => 'url|different:primary_url|nullable',
			'popup_id'      => 'required_with:cloak_link|nullable',
			'magickbar_id'  => 'required_with:cloak_link|nullable',
			'timer_id'      => 'required_with:cloak_link|nullable',
		];
		
		if ( ($request->has('max_clicks') && $request->input('max_clicks') > 0) || ($request->has('geo_targeting') && $request->input('geo_targeting') == 'Specified') || ($request->has('smartswap_id') && $request->input('smartswap_id') != '0') ) {
			$rules['backup_url'] .= '|required';
		}
		
		if ( $request->has('geo_targeting') && $request->input('geo_targeting') == 'Specified' ) {
			$rules['geo_targeting'] = 'required_with:geo_targeting_include_countries,geo_targeting_exclude_countries';
		}
		
		$this->validate($request, $rules);
		
		if ( $request->has('cloak_link') && $request->input('cloak_link') == 'Yes' ) {
			if ( !xFrameOption($request->input('primary_url')) ) {
				return redirect()->back()->withErrors([ 'cloak_link' => 'Cloak not supported this link' ]);
			}
		}
		
		$input_arr = $request->all();
		
		$input_arr = (array) $input_arr;
		
		$link_id = $this->linksRepo->create(auth()->id(), $input_arr);
		
		$userLR = getLRStatusByUser();
		
		$alert_status = $userLR['link_url'] == '1' ? '1' : '0';
		
		$linkAlertsRepo = new LinkRotatorsAlertRepository();
		
		$linkAlertsRepo->create(auth()->id(), $link_id, '0', $alert_status);
		
		$request->session()->flash('success', 'Link added successfully.');
		
		return response()->redirectTo('links');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param String                    $sub_domain
	 * @param \App\Models\Link          $link
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($sub_domain, Link $link, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $link ) {
			if ( $request->has('flag') ) {
				$flag = $request->input('flag');
				switch ( $flag ) {
					case 'linksReportGraph':
						$date_interval = $request->input('date_interval') * 1;
						
						$interval_unit = 'DAY';
						
						if ( strtolower(auth()->user()->domain) == 'vssmind' ) {
							if ( $date_interval == 30 ) {
								$date_interval = 25;
							}
						} else if ( strtolower(auth()->user()->domain) == 'ddlm' ) {
							if ( $date_interval == 30 ) {
								$date_interval = 10;
							}
						}
						
						$GraphInformation = [
							'no_report'       => 'No Report has been found',
							'performanceName' => 'Link Group',
							'chart_width'     => 950,
						];
						
						$d_arr = [ 'link_id' => $link->id ];
						
						$axisData = $this->linksRepo->getLinkXYAxisArray($date_interval, $interval_unit, $d_arr);
						
						$clicks = [
							'total_click'                 => $axisData[0],
							'total_unique'                => $axisData[1],
							'total_non_unique'            => $axisData[2],
							'percentage_unique_clicks'    => $axisData[3],
							'percentage_nonunique_clicks' => $axisData[4],
							'total_pclicks'               => $axisData[5],
							'rotators_log'                => [],
							'xaxis_step'                  => (($date_interval == 7 || $date_interval == 1) ? 1 : 4)
						];
						
						if ( $request->has('page') ) {
							$searchParams['page'] = $request->input('page');
						} else {
							$searchParams['page'] = 1;
						}

						$link_logs = $this->linksRepo->getClickedList($date_interval, $interval_unit, $link->id);
						
						if ( count($link_logs) > 0 ) {
							foreach ( $link_logs as $key => $link_log ) {
								$clicks['rotators_log'][$key] = (array) $link_log;
								
								$clicks['rotators_log'][$key]['referer_id']   = getRefererName($link_log->referer_id);
								$clicks['rotators_log'][$key]['geoip_id']     = ($link_log->geoip_id == '0') ? 'Nil' : getCurrentCountry($link_log->geoip_id);
								$clicks['rotators_log'][$key]['browser']      = ($link_log->agent_id == '0') ? 'Nil' : getCurrentBrowser($link_log->agent_id);
								$clicks['rotators_log'][$key]['platform']     = ($link_log->device_id == '0') ? 'Nil' : getCurrentPlatform($link_log->device_id);
								$clicks['rotators_log'][$key]['unique_click'] = ($link_log->unique_click_per_day == '1') ? 'Yes' : 'No';
								
								$clicks['rotators_log'][$key]['client_ip']  = $link_log->client_ip;
								$clicks['rotators_log'][$key]['created_at'] = $link_log->created_at;
							}
						}
						
						return response()->view('users.reportGraph', compact('clicks', 'GraphInformation', 'link_logs', 'searchParams'));
						break;
					case 'track_conversion':
						$main_domain_url = config('site.site_domain');
						
						$second_domain_url = config('site.custom_domains.0');
						
						$d_arr = [
							'site_name'         => config('app.name'),
							'main_domain_url'   => $main_domain_url,
							'second_domain_url' => $second_domain_url,
							
							'action_pixel_url' => 'http://' . $main_domain_url . '/pixel/action/' . auth()->id(),
							'sales_pixel_url'  => 'http://' . $main_domain_url . '/pixel/sales/' . auth()->id() . '?amt=0.00',
							'action_post_url'  => 'http://' . $main_domain_url . '/post/action/' . auth()->id(),
							'sales_post_url'   => 'http://' . $main_domain_url . '/post/sales/' . auth()->id() . '?amt=0.00',
							
							'second_action_pixel_url' => 'http://' . $second_domain_url . '/pixel/action/' . auth()->id(),
							'second_sales_pixel_url'  => 'http://' . $second_domain_url . '/pixel/sales/' . auth()->id() . '?amt=0.00',
							'second_action_post_url'  => 'http://' . $second_domain_url . '/post/action/' . auth()->id(),
							'second_sales_post_url'   => 'http://' . $second_domain_url . '/post/sales/' . auth()->id() . '?amt=0.00'
						];
						
						return response()->view('users.linksTrackConversion', compact('d_arr'));
						break;
					case 'track_engagements':
						// Showing Track Engagements
						$main_domain_url = config('site.site_domain');
						
						$second_domain_url = config('site.custom_domains.0');
						
						$d_arr = [
							'site_name'         => config('app.name'),
							'main_domain_url'   => $main_domain_url,
							'second_domain_url' => $second_domain_url,
							
							'engagement_url'        => 'http://' . $main_domain_url . '/pixel/event/' . auth()->id(),
							'second_engagement_url' => 'http://' . $second_domain_url . '/pixel/event/' . auth()->id()
						];
						
						return response()->view('users.linksTrackEngagements', compact('d_arr'));
						break;
					case 'links_traffic_quality':
						// Showing Traffic Quality
						$unique_ips_percent   = 0;
						$mobile_click_percent = 0;
						
						$links_log_details = $this->linksRepo->linksTrafficQuality($link->id);
						
						$device_id = DeviceId();
						
						$mobile_click_count = 0;
						if ( count($links_log_details['link_details']) > 0 ) {
							foreach ( $links_log_details['link_details'] as $link_det ) {
								if ( array_search($link_det->device_id, $device_id) !== false )//mobile clicks
									$mobile_click_count += 1;
							}
							
							$unique_ips_percent = round((count($links_log_details['unique_ip']) / count($links_log_details['link_details'])) * 100);
							
							$mobile_click_percent = round(($mobile_click_count / count($links_log_details['link_details'])) * 100);
						}
						
						return response()->view('users.linksTrafficQuality', compact('link', 'unique_ips_percent', 'mobile_click_percent'));
						break;
					case 'links_filtered_clicks':
						return response()->view('users.linksFilteredClicks', compact('link'));
						break;
					case 'links_filtered_clicks_graph':
						$d_arr = [
							'link_id'     => $link->id,
							'link_url_id' => '0'
						];
						
						$GraphInformation = [
							'no_report'       => 'No Report has been found',
							'chartName'       => 'charts',
							'performanceName' => $link->link_name . ' - ' . $link->tracking_link,
							'chart_width'     => 1000,
						];
						
						$calendar_numeric   = $request->input('calendar_numeric');
						$date_interval      = 'DAY';
						$unique_click_count = 0;
						
						$link_filtered_clicks = $this->linksRepo->getFilteredLinkClickGraph($calendar_numeric, $date_interval, $d_arr);
						
						$clicks = [
							'total_click'     => $link_filtered_clicks[0],
							'link_created_at' => date('d-m-Y H:i:s', $link->unix_date_added),
							'xaxis_step'      => 1,
						];
						
						$link_urls = [];
						if ( count($link_filtered_clicks[1]) > 0 ) {
							$unique_click_count_per_day = 0;
							
							foreach ( $link_filtered_clicks[1] as $key => $link_log ) {
								$link_urls[$key]['url'] = $link_log->url;
								
								$link_urls[$key]['referer_id'] = getRefererName($link_log->referer_id);
								$link_urls[$key]['geoip_id']   = ($link_log->geoip_id == 0) ? 'Nil' : getCurrentCountry($link_log->geoip_id);
								$link_urls[$key]['browser']    = ($link_log->agent_id == 0) ? 'Nil' : getCurrentBrowser($link_log->agent_id);
								$link_urls[$key]['platform']   = ($link_log->device_id == 0) ? 'Nil' : getCurrentPlatform($link_log->device_id);
								
								$link_urls[$key]['unique_click'] = ($link_log->unique_click_per_day == '1') ? 'Yes' : 'No';
								
								if ( $link_log->unique_click_per_day == '1' ) {
									$unique_click_count += 1;
									
									$unique_click_count_per_day += 1;
								}
								
								$link_urls[$key]['client_ip']  = $link_log->client_ip;
								$link_urls[$key]['created_at'] = $link_log->created_at;
							}
						}
						
						$clicks['links_log'] = $link_urls;
						
						$button_class = '';
						
						if ( $calendar_numeric == 30 && count($link_urls) <= 0 )
							$button_class = 'hidden';
						
						$clicks['xaxis_step'] = ($calendar_numeric == 7 || $calendar_numeric == 1) ? 1 : 4;
						
						$link_id = $link->id;
						
						return response()->view('users.filteredClicksGraph', compact('clicks', 'GraphInformation', 'unique_click_count', 'link_id', 'button_class', 'calendar_numeric', 'unique_click_count_per_day'));
						break;
					default:
						break;
				}
			} else {
				$link_group_name = 'All links';
				
				if ( $link->link_group_id != 0 ) {
					$link_group_name = $this->linksRepo->getPluckLinkGroup($link->link_group_id);
				}
				
				if ( $link->tracking_domain > 0 ) {
					$tracking_domain = DataService::getTrackingDomain($link->tracking_domain, '1');
					
					if ( substr($tracking_domain, -1, 1) == '/' ) {
						$tracking_domain = substr($tracking_domain, 0, -1);
					}
				} else {
					if ( config('site.site_domain_name') == 'clickperfect' ) {
						$tracking_url = 'http://';
						
						if ( auth()->user()->domain != '' ) {
							$tracking_url .= strtolower(auth()->user()->domain) . '.';
						}
						
						$tracking_domain = $tracking_url . config('site.custom_domains')[$link->tracking_domain] . '/go';
					} else {
						$tracking_domain = $request->root() . '/go';
					}
				}
				
				$end_date   = date('Y-m-d H:i:s');
				$start_date = date('Y-m-d H:i:s', strtotime('-29 days', strtotime($end_date)));
				
				$link->total_clicks  = $this->linksRepo->linkClickCountById($link->id, $start_date, $end_date);
				$link->unique_clicks = $this->linksRepo->linkClickCountById($link->id, $start_date, $end_date, true);
				
				$action_conversion_rate = $engagement_conversion_rate = $sales_conversion_rate = '-';
				$unique_action_count    = $this->linksRepo->getUniqueActionCount($link->id);
				if ( $unique_action_count > 0 && $link->unique_click_per_day > 0 ) {
					$action_conversion_rate = round(($unique_action_count / $link->unique_click_per_day) * 100) . '%';
				}
				
				$unique_engage_count = $this->linksRepo->getUniqueEngagementCount($link->id);
				if ( $unique_engage_count > 0 && $link->unique_click_per_day > 0 ) {
					$engagement_conversion_rate = round(($unique_engage_count / $link->unique_click_per_day) * 100) . '%';
				}
				
				$unique_sales_count = $this->linksRepo->getUniqueSalesCount($link->id);
				if ( $unique_sales_count > 0 && $link->unique_click_per_day > 0 ) {
					$sales_conversion_rate = round(($unique_sales_count / $link->unique_click_per_day) * 100) . '%';
				}
				
				$link->acr = $action_conversion_rate;
				$link->ecr = $engagement_conversion_rate;
				$link->scr = $sales_conversion_rate;
				
				if ( $request->has('page') ) {
					$searchParams['page'] = $request->input('page');
				} else {
					$searchParams['page'] = 1;
				}
				
				return response()->view('users.linksReport', compact('link', 'link_group_name', 'tracking_domain', 'searchParams'));
			}
		} else {
			$request->session()->flash('error', 'Link ID does not exits');
			if ( $request->has('flag') ) {
				if ( $request->input('flag') == 'track_conversion' || $request->input('flag') == 'track_engagements' ) {
					echo '<script>window.parent.$(".close").click();window.parent.location = window.parent.location.href;window.parent.$.fancybox.close();</script>';
					exit;
				}
			}
			
			return response()->redirectTo('links');
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param String                    $sub_domain
	 * @param \App\Models\Link          $link
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($sub_domain, Link $link, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $link ) {
			if ( $request->has('flag') ) {
				$flag = $request->input('flag');
				switch ( $flag ) {
					case 'cloneLink':
						// showing clone link popup
						$d_arr = [
							'link_name'       => $link->link_name != '' ? $link->link_name : $link->tracking_link,
							'tracking_domain' => $request->root() . '/go/',
							'id'              => $link->id
						];
						
						return response()->view('users.linksClone', compact('d_arr'));
						break;
					case 'splitUrl':
						// edit split Url
						$split_url_lists = $this->linksRepo->getLinksSplitUrl($link);
						
						return response()->view('users.linksSplitList', compact('split_url_lists', 'link'));
						break;
					case 'equalWeight':
						$split_url_lists = $this->linksRepo->getLinksSplitUrl($link);
						
						if ( sizeof($split_url_lists) > 0 ) {
							$w = round((100 / count($split_url_lists)));
							
							foreach ( $split_url_lists as $row ) {
								$this->linksRepo->equalWeight($row['id'], $w);
							}
						}
						
						$request->session()->flash('success', 'Links split url weights successfully updated.');
						
						return response()->redirectTo('links/' . $link->id . '/edit?flag=splitUrl');
						break;
					case 'linkAlert':
						$linkAlertsD = DataService::getLinkAlertsByLinkID(auth()->id(), $link->id);
						
						$linkStatus = sizeof($linkAlertsD) > 0 ? $linkAlertsD[0]['status'] : '0';
						
						return response()->view('users.linksAlert', compact('link', 'linkStatus'));
						break;
					case 'linkNotification':
						$list = $this->linksRepo->getLinkNotifications($link);
						
						$d_arr = [];
						
						if ( count($list) > 0 ) {
							foreach ( $list as $key => $value ) {
								$d_arr[$key]       = (array) $value;
								$notification_type = $value->notification_type;
								$relational        = $value->relational;
								switch ( $notification_type ) {
									case '1':
										$d_arr[$key]['notification_type'] = 'Action Conversion Rate';
										break;
									case '2':
										$d_arr[$key]['notification_type'] = 'Engagement Conversion Rate';
										break;
									case '3':
										$d_arr[$key]['notification_type'] = 'Sales Conversion Rate';
										break;
									case '4':
										$d_arr[$key]['notification_type'] = 'Earnings Per Click';
										break;
									case '5':
										$d_arr[$key]['notification_type'] = 'Average Customer Value';
										break;
								}
								switch ( $relational ) {
									case '1':
										$d_arr[$key]['relational'] = 'Greater Than';
										break;
									case '2':
										$d_arr[$key]['relational'] = 'Lesser Than';
										break;
								}
								$d_arr[$key]['value']  = $value->value;
								$d_arr[$key]['clicks'] = $value->clicks;
							}
						}
						
						return response()->view('users.linksNotification', compact('link', 'd_arr'));
						break;
					default:
						break;
				}
			} else {
				return response()->view('users.linksEdit', compact('link'));
			}
		} else {
			$request->session()->flash('error', 'Link ID does not exits');
			if ( $request->has('flag') ) {
				if ( $request->input('flag') == 'cloneLink' ) {
					echo '<script>window.parent.$(".close").click();window.parent.location = window.parent.location.href;window.parent.$.fancybox.close();</script>';
					exit;
				}
			}
			
			return response()->redirectTo('links');
		}
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param String                    $sub_domain
	 * @param \App\Models\Link          $link
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function update($sub_domain, Link $link, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $link ) {
			if ( $request->has('flag') ) {
				if ( $request->input('flag') == 'cloneLink' ) {
					// Clone link.
					$this->validate($request, [
						'tracking_link' => 'required|min:4|max:255|unique:mysql_tenant.links'
					], [
						'tracking_link.required' => 'The tracking link is required.',
					]);
					
					$input_array = $link->toArray();
					
					$input_array['link_name']     = '';
					$input_array['tracking_link'] = $request->input('tracking_link');
					
					unset($input_array['id']);
					
					$clone_id = $this->linksRepo->create(auth()->id(), $input_array, 'edit');
					
					$userLR = getLRStatusByUser();
					
					$alert_status = $userLR['link_url'] == '1' ? '1' : '0';
					
					$linkAlertsRepo = new LinkRotatorsAlertRepository();
					
					$linkAlertsRepo->create(auth()->id(), $clone_id, '0', $alert_status);
					
					$request->session()->flash('success', 'Link successfully cloned.');
					
					echo '<script>window.parent.$(".close").click();window.parent.location = window.parent.location.href;window.parent.$.fancybox.close();</script>';
				} else if ( $request->input('flag') == 'archive' ) {
					// Link Archive
					if ( $request->input('type_link') == 'all-links' ) {
						$link->link_type = 'archived-link';
						
						$request->session()->flash('success', 'Link successfully archived.');
					} else {
						$link->link_type = 'all-links';
						
						$request->session()->flash('success', 'Link successfully unarchived.');
					}
					
					$link->save();
					
					return response('success');
				} else if ( $request->input('flag') == 'resetState' ) {
					// Link reset stat
					$link->total_clicks         = 0;
					$link->unique_clicks        = 0;
					$link->unique_click_per_day = 0;
					$link->actions              = 0;
					$link->sales                = 0;
					$link->events               = 0;
					
					$link->save();
					
					$this->linksRepo->resetLinkStat($link);
					
					return response('success');
				} else if ( $request->input('flag') == 'splitUrl' ) {
					$split_url_lists = $this->linksRepo->getLinksSplitUrl($link);
					
					$ratio = 0;
					if ( sizeof($split_url_lists) > 0 ) {
						foreach ( $split_url_lists as $row ) {
							if ( $row['primary_url'] == '1' ) {
								continue;
							}
							$ratio += $row['weight'] * 1;
						}
					}
					$left_ratio = 100 - $ratio;
					
					// Split Url Create
					$this->validate($request, [
						'url_name'  => 'required|unique:mysql_tenant.links_split_url|min:4|max:255',
						'split_url' => 'required|unique:mysql_tenant.links_split_url|min:4|max:255|url',
						'weight'    => 'required|numeric|min:1|max:' . $left_ratio,
					]);
					
					$request->ratio = $left_ratio;
					
					$this->linksRepo->createSplitUrl($link, $request);
					
					$request->session()->flash('success', 'Links split url successfully added.');
					
					return response()->redirectTo('links/' . $link->id . '/edit?flag=' . $request->input('flag'));
				} else if ( $request->input('flag') == 'splitUrlUpdate' ) {
					$split_url_lists = $this->linksRepo->getLinksSplitUrl($link);
					
					$ratio = 0;
					if ( sizeof($split_url_lists) > 0 ) {
						foreach ( $split_url_lists as $row ) {
							if ( $row['primary_url'] == '1' || $row['id'] == $request->split_id ) {
								continue;
							}
							$ratio += $row['weight'] * 1;
						}
					}
					$left_ratio = 100 - $ratio;
					
					// Split Url update
					Validator::make($request->all(), [
						'url_name'  => [
							'required',
							'min:4',
							'max:255',
							Rule::unique('mysql_tenant.links_split_url')->ignore($request->split_id)
						],
						'split_url' => [
							'required',
							'min:4',
							'max:255',
							'url',
							Rule::unique('mysql_tenant.links_split_url')->ignore($request->split_id)
						],
						'weight'    => 'required|numeric|min:1|max:' . $left_ratio,
					])->validate();
					
					$request->ratio = $left_ratio;
					
					$this->linksRepo->updateSplitUrl($link, $request);
					
					$request->session()->flash('success', 'Links split url successfully updated.');
					
					return response()->redirectTo('links/' . $link->id . '/edit?flag=splitUrl');
				} else if ( $request->input('flag') == 'linkAlert' ) {
					$linkAlertsRepo = new LinkRotatorsAlertRepository();
					
					$linkAlertsD = $linkAlertsRepo->getValueByColumns([ 'id' ], [ [ 'user_id', '=', auth()->id() ], [ 'ref_id', '=', $link->id ] ]);
					
					$status = $request->input('alert_status') == 'status_yes' ? '1' : '0';
					if ( count($linkAlertsD) > 0 ) {
						$linkAlertsRepo->update(auth()->id(), $link->id, '0', $status);
					} else {
						$linkAlertsRepo->create(auth()->id(), $link->id, '0', $status);
					}
					
					return response()->redirectTo('links/' . $link->id . '/edit?flag=' . $request->input('flag'));
				} else if ( $request->input('flag') == 'linkNotification' ) {
					$this->linksRepo->createLinkNotifications($link, $request);
					
					$this->validate($request, [
						'value'  => 'required|numeric|min:1',
						'clicks' => 'required|numeric|min:50'
					]);
					
					return response()->redirectTo('links/' . $link->id . '/edit?flag=' . $request->input('flag'));
				}
			} else {
				$rules = [
					'link_name'     => [ 'min:4', 'max:255', 'nullable', Rule::unique('mysql_tenant.links')->ignore($link->id) ],
					'tracking_link' => [ 'required', 'alpha_dash', 'min:4', 'max:255', Rule::unique('mysql_tenant.links')->ignore($link->id) ],
					'primary_url'   => 'required|url|max:255',
					'max_clicks'    => 'numeric|nullable',
					'mobile_url'    => 'url|different:primary_url|nullable',
					'repeat_url'    => 'url|different:primary_url|nullable',
					'backup_url'    => 'url|different:primary_url|nullable',
					'popup_id'      => 'required_with:cloak_link|nullable',
					'magickbar_id'  => 'required_with:cloak_link|nullable',
					'timer_id'      => 'required_with:cloak_link|nullable',
				];
				
				if ( ($request->has('max_clicks') && $request->input('max_clicks') > 0) || ($request->has('geo_targeting') && $request->input('geo_targeting') == 'Specified') || ($request->has('smartswap_id') && $request->input('smartswap_id') != '0') ) {
					$rules['backup_url'] .= '|required';
				}
				
				if ( $request->has('geo_targeting') && $request->input('geo_targeting') == 'Specified' ) {
					$rules['geo_targeting'] = 'required_with:geo_targeting_include_countries,geo_targeting_exclude_countries';
				}
				
				Validator::make($request->all(), $rules)->validate();
				
				if ( $request->has('cloak_link') && $request->input('cloak_link') == 'Yes' ) {
					if ( !xFrameOption($request->input('primary_url')) ) {
						return redirect()->back()->withErrors([ 'cloak_link' => 'Cloak not supported this link' ]);
					}
				}
				
				$params = $request->all();
				foreach ( $params as $key => $val ) {
					if ( is_null($val) ) {
						$params[$key] = '';
						if ( $key == 'tracking_domain' || $key == 'smartswap_id' )
							$params[$key] = '0';
						
						if ( $key == 'tracking_link_visited' || $key == 'detect_new_bots' )
							$params[$key] = 'No';
						
						if ( $key == 'link_type' )
							$params[$key] = 'all-links';
						
						if ( $key == 'geo_targeting' )
							$params[$key] = 'All';
					}
					if ( ($key == 'geo_targeting_include_countries' || $key == 'geo_targeting_exclude_countries') && $val != '' ) {
						$params[$key] = implode(",", $val);
					}
				}
				
				$link->fill($params);
				$link->save();
				
				$request->session()->flash('success', 'Link successfully updated.');
				
				return response()->redirectTo('links');
			}
		} else {
			$request->session()->flash('error', 'Link ID does not exits');
			
			return response()->redirectTo('links');
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param String           $sub_domain
	 * @param \App\Models\Link $link
	 * @param Request          $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($sub_domain, Link $link, Request $request)
	{
		if ( $link ) {
			if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
				abort(401, 'Session is expired.');
			}
			if ( $request->has('flag') ) {
				if ( $request->input('flag') == 'splitUrl' ) {
					$this->linksRepo->deleteSplitUrl($link, $request->input('split_id'));
					
					$request->session()->flash('success', 'Split URL successfully deleted.');
				} else if ( $request->input('flag') == 'linkNotification' ) {
					$this->linksRepo->deleteLinkNotifications($request);
					
					return response()->redirectTo('links/' . $link->id . '/edit?flag=' . $request->input('flag'));
				}
			} else {
				$this->linksRepo->delete($link->id);
				
				$linkAlertsRepo = new LinkRotatorsAlertRepository();
				
				$linkAlertsRepo->delete(auth()->id(), $link->id);
				
				$link->delete();
				
				$request->session()->flash('success', 'Link successfully deleted.');
			}
		} else {
			$request->session()->flash('error', 'Link ID does not exits');
			
			return response('failure');
		}
		
		return response('success');
	}
}
