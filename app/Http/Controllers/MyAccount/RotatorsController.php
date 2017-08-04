<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\DataService;
use App\Http\Repositories\Accounts\RotatorsRepository;
use App\Http\Repositories\LinkRotatorsAlertRepository;
use App\Models\Rotators;
use App\Models\RotatorsUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RotatorsController extends Controller
{
	protected $rotatorsRepo;
	
	public function __construct(RotatorsRepository $rotatorsRepository)
	{
		$this->middleware('auth');
		$this->rotatorsRepo = $rotatorsRepository;
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
			'rotator_name'  => '',
			'rotators_type' => 'all_rotators',
			'start_date'    => '',
			'end_date'      => '',
			'page'          => 1
		];
		
		if ( $request->has('rotator_name') ) {
			$searchParams['rotator_name'] = $request->input('rotator_name');
		}
		
		if ( $request->has('rotators_type') ) {
			$searchParams['rotators_type'] = $request->input('rotators_type');
		}
		
		if ( $request->has('start_date') ) {
			$searchParams['start_date'] = $request->input('start_date');
		}
		
		if ( $request->has('end_date') ) {
			$searchParams['end_date'] = $request->input('end_date');
		}
		
		if ( $request->has('page') ) {
			$searchParams['page'] = $request->input('page');
		}
		
		$rotators_group = $this->rotatorsRepo->getRotatorsGroup();
		
		$start_date = ($searchParams['start_date'] != '') ? $searchParams['start_date'] : config('site.start_date');
		$end_date   = ($searchParams['end_date'] != '') ? $searchParams['end_date'] : date('Y-m-d');
		
		$rotators_query = $this->rotatorsRepo->buildRotatorsQuery($start_date, $end_date, $searchParams['rotator_name'], $searchParams['rotators_type']);
		
		$rotators_list = $rotators_query->paginate(20);
		
		$rotators = [];
		
		if ( sizeof($rotators_list) > 0 ) {
			foreach ( $rotators_list as $key => $row ) {
				$total_clicks  = $this->rotatorsRepo->rotatorsClickCountById($row->id, $start_date, $end_date, '0');
				$unique_clicks = $this->rotatorsRepo->rotatorsClickCountById($row->id, $start_date, $end_date, '0', true);
				
				if ( $row->tracking_domain > 0 ) {
					$tracking_domain = DataService::getTrackingDomain($row->tracking_domain, '2');
					
					if ( substr($tracking_domain, -1, 1) == '/' ) {
						$tracking_domain = substr($tracking_domain, 0, -1);
					}
				} else {
					if ( config('site.site_domain_name') == 'clickperfect' ) {
						$tracking_url = 'http://';
						
						if ( auth()->user()->domain != '' ) {
							$tracking_url .= strtolower(auth()->user()->domain) . '.';
						}
						
						$tracking_domain = $tracking_url . config('site.custom_domains')[$row->tracking_domain] . '/tr';
					} else {
						$tracking_domain = $request->root() . '/tr';
					}
				}
				
				$rotators[$key] = $row;
				
				$rotators[$key]['full_name']    = (isset($row->rotator_name) && $row->rotator_name != '') ? $row->rotator_name : $row->rotator_link;
				$rotators[$key]['rotator_name'] = (strlen($rotators[$key]['full_name']) > 8) ? substr($rotators[$key]['full_name'], 0, 8) . '...' : $rotators[$key]['full_name'];
				$rotators[$key]['rotator_name'] = htmlentities($rotators[$key]['rotator_name']);
				$rotators[$key]['preview_url']  = $tracking_domain . '/' . $row->rotator_link;
				
				$rotators[$key]['cloak'] = ($row->cloak_rotator == '1') ? 'Yes' : 'No';
				$rotators[$key]['popup'] = ($row->popup_id > 0) ? 'Yes' : 'No';
				$rotators[$key]['timer'] = ($row->timer_id > 0) ? 'Yes' : 'No';
				
				$rotators[$key]['magickbar']  = ($row->magickbar_id > 0) ? 'Yes' : 'No';
				$rotators[$key]['created_at'] = date('Y-m-d H:i:s', $row->created_at);
				
				$rotators[$key]['total_clicks']  = $total_clicks;
				$rotators[$key]['unique_clicks'] = $unique_clicks;
				
				if ( $row->rotator_mode == '0' ) {
					$rotators[$key]['rotator_mode'] = 'Fulfillment';
				} else if ( $row->rotator_mode == '1' ) {
					$rotators[$key]['rotator_mode'] = 'Spillover';
				} else {
					$rotators[$key]['rotator_mode'] = 'Random';
				}
				
				$rotators[$key]['url_select_type'] = 'rotator_url_type_' . $row->id;
				
				$rotators_urls = $this->rotatorsRepo->getRotatorsUrls($row->id);
				
				
				$active_urls = 0;
				if ( count($rotators_urls) > 0 ) {
					foreach ( $rotators_urls as $rotator_url ) {
						if ( $rotator_url->status == '0' ) {
							$active_urls++;
							
							$rotator_url->status = 'Active';
						} else if ( $rotator_url->status == '1' ) {
							$rotator_url->status = 'Paused';
						} else {
							$rotator_url->status = 'Archived';
						}
						
						$rotator_url->start_date = ($rotator_url->start_date == '') ? 'NA' : $rotator_url->url_start_date;
						$rotator_url->end_date   = ($rotator_url->end_date == '') ? 'NA' : $rotator_url->url_end_date;
						
						$rotator_url->today_clicks = $this->rotatorsRepo->getUrlTodayClicks($row->id, $rotator_url->id);
						
						if ( $rotator_url->max_daily_clicks > 0 ) {
							$rotator_url->today_clicks = $rotator_url->today_clicks . '/' . $rotator_url->max_daily_clicks;
						}
						
						if ( $rotator_url->max_clicks > 0 ) {
							$rotator_url->unique_clicks = $rotator_url->unique_clicks . '/' . $rotator_url->max_clicks;
						}
					}
				}
				$rotators[$key]['rotators_urls'] = $rotators_urls;
				
				$rotators[$key]['active_urls'] = $active_urls;
				$rotators[$key]['total_urls']  = count($rotators_urls);
			}
		}
		
		return response()->view('users.rotatorsList', compact('rotators_group', 'searchParams', 'rotators', 'rotators_list'));
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
		
		return response()->view('users.rotatorsAdd');
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
			'rotator_name' => 'min:4|max:255|unique:mysql_tenant.rotators|nullable',
			'rotator_link' => 'required|alpha_dash|min:4|max:255|unique:mysql_tenant.rotators',
			'backup_url'   => 'required|url|max:255',
			'mobile_url'   => 'url|different:backup_url|nullable',
			'popup_id'     => 'required_with:cloak_rotator|nullable',
			'magickbar_id' => 'required_with:cloak_rotator|nullable',
			'timer_id'     => 'required_with:cloak_rotator|nullable',
		];
		
		if ( $request->has('geo_targeting') && $request->input('geo_targeting') == '1' ) {
			$rules['geo_targeting'] = 'required_with:geo_targeting_include_countries,geo_targeting_exclude_countries';
		}
		
		$this->validate($request, $rules);
		
		if ( $request->has('cloak_rotator') && $request->input('cloak_rotator') == '1' ) {
			if ( !xFrameOption($request->input('backup_url')) ) {
				return redirect()->back()->withErrors([ 'cloak_rotator' => 'Cloak Rotators not supported' ]);
			}
		}
		
		$input_arr = $request->all();
		foreach ( $input_arr as $key => $val ) {
			if ( is_null($val) ) {
				$input_arr[$key] = '';
				if ( $key == 'tracking_domain' || $key == 'geo_targeting' )
					$input_arr[$key] = '0';
			}
		}
		
		$geo_targeting_include_countries = isset($input_arr['geo_targeting_include_countries']) ? implode(",", $input_arr['geo_targeting_include_countries']) : '';
		$geo_targeting_exclude_countries = isset($input_arr['geo_targeting_exclude_countries']) ? implode(",", $input_arr['geo_targeting_exclude_countries']) : '';
		
		$delet_new_bot = isset($input_arr['detect_new_bots']) ? '1' : '0';
		
		$rotator = new Rotators();
		
		$rotator->fill($input_arr);
		
		$rotator->user_id = auth()->id();
		
		$rotator->tracking_domain = isset($input_arr['tracking_domain']) ? $input_arr['tracking_domain'] : '0';
		$rotator->ignore_last_url = isset($input_arr['ignore_last_url']) ? $input_arr['ignore_last_url'] : '0';
		$rotator->detect_new_bots = $delet_new_bot;
		
		$rotator->geo_targeting_include_countries = $geo_targeting_include_countries;
		$rotator->geo_targeting_exclude_countries = $geo_targeting_exclude_countries;
		
		$rotator->status     = '0';
		$rotator->created_at = time();
		$rotator->updated_at = time();
		
		$rotator->save();
		
		$userLR = getLRStatusByUser();
		
		$alert_status = $userLR['rotator_url'] == '1' ? '1' : '0';
		
		$linkAlertsRepo = new LinkRotatorsAlertRepository();
		
		$linkAlertsRepo->create(auth()->id(), $rotator->id, '1', $alert_status);
		
		$request->session()->flash('success', 'Rotator added successfully.');
		
		return response()->redirectTo('rotators');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param String                    $sub_domain
	 * @param  \App\Models\Rotators     $rotator
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($sub_domain, Rotators $rotator, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $rotator ) {
			if ( $request->has('flag') ) {
				$flag = $request->input('flag');
				switch ( $flag ) {
					case 'rotatorsReportGraph':
						$date_interval = $request->input('date_interval') * 1;
						
						$interval_unit = 'DAY';
						
						$GraphInformation = [
							'no_report'       => 'No Report has been found',
							'performanceName' => 'Rotators Group',
							'chart_width'     => 950,
						];
						
						$d_arr = [
							'rotators_id'     => $rotator->id,
							'rotators_url_id' => $request->input('url_id'),
						];
						
						$axisData = $this->rotatorsRepo->getRotatorsXYAxisArray($date_interval, $interval_unit, $d_arr);
						
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
						
						$link_logs = $this->rotatorsRepo->getClickedList($date_interval, $interval_unit, $d_arr);
						
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
						
						if ( $request->has('page') ) {
							$searchParams['page'] = $request->input('page');
						} else {
							$searchParams['page'] = 1;
						}
						
						return response()->view('users.reportGraph', compact('clicks', 'GraphInformation', 'link_logs', 'searchParams'));
						break;
				}
			} else {
				$rotators_group_name = 'All Rotators';
				
				if ( $rotator->rotator_group_id != 0 ) {
					$rotators_group_name = $this->rotatorsRepo->getPluckRotatorsGroup($rotator->rotator_group_id);
				}
				
				if ( $rotator->tracking_domain > 0 ) {
					$tracking_domain = DataService::getTrackingDomain($rotator->tracking_domain, '1');
					
					if ( substr($tracking_domain, -1, 1) == '/' ) {
						$tracking_domain = substr($tracking_domain, 0, -1);
					}
				} else {
					if ( config('site.site_domain_name') == 'clickperfect' ) {
						$tracking_url = 'http://';
						
						if ( auth()->user()->domain != '' ) {
							$tracking_url .= strtolower(auth()->user()->domain) . '.';
						}
						
						$tracking_domain = $tracking_url . config('site.custom_domains')[$rotator->tracking_domain] . '/tr';
					} else {
						$tracking_domain = $request->root() . '/tr';
					}
				}
				
				$end_date   = date('Y-m-d H:i:s');
				$start_date = date('Y-m-d H:i:s', strtotime('-29 days', strtotime($end_date)));
				
				$url_id = $request->has('url_id') ? $request->input('url_id') : 0;
				
				if ( $url_id > 0 ) {
					$url_data = $this->rotatorsRepo->getRotatorsUrlById($url_id);
				} else {
					$url_data = [];
				}
				
				$rotator->total_clicks  = $this->rotatorsRepo->rotatorsClickCountById($rotator->id, $start_date, $end_date, $url_id);
				$rotator->unique_clicks = $this->rotatorsRepo->rotatorsClickCountById($rotator->id, $start_date, $end_date, $url_id, true);
				
				if ( $request->has('page') ) {
					$searchParams['page'] = $request->input('page');
				} else {
					$searchParams['page'] = 1;
				}
				
				return response()->view('users.rotatorsReport', compact('rotator', 'rotators_group_name', 'tracking_domain', 'url_id', 'url_data', 'searchParams'));
			}
		} else {
			$request->session()->flash('error', 'Rotators ID does not exits');
			
			return response()->redirectTo('rotators');
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param String                    $sub_domain
	 * @param  \App\Models\Rotators     $rotator
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($sub_domain, Rotators $rotator, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $rotator ) {
			if ( $request->has('flag') ) {
				switch ( $request->input('flag') ) {
					case 'add_rotators_url':
						if ( $request->has('rotators_url_id') ) {
							$rotators_urls = $this->rotatorsRepo->getRotatorsUrlById($request->input('rotators_url_id'));
							
							$rotators_url = (array) $rotators_urls;
						} else {
							$rotators_url = [];
						}
						
						return response()->view('users.rotatorsAddUrl', compact('rotator', 'rotators_url'));
						break;
					case 'cloneRotators':
						// showing clone link popup
						$d_arr = [
							'rotator_name'    => $rotator->name != '' ? $rotator->name : $rotator->rotator_link,
							'tracking_domain' => $request->root() . '/demo/',
							'id'              => $rotator->id
						];
						
						return response()->view('users.rotatorsClone', compact('d_arr'));
						break;
					case 'linkAlert':
						$linkAlertsD = DataService::getLinkAlertsByLinkID(auth()->id(), $rotator->id, '1');
						
						$rotatorStatus = sizeof($linkAlertsD) > 0 ? $linkAlertsD[0]['status'] : '0';
						
						return response()->view('users.rotatorsLinksAlert', compact('rotator', 'rotatorStatus'));
						break;
				}
			} else {
				return response()->view('users.rotatorsEdit', compact('rotator'));
			}
		} else {
			$request->session()->flash('error', 'Rotators ID does not exits');
			
			return response()->redirectTo('rotators');
		}
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param String                    $sub_domain
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Models\Rotators     $rotator
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function update($sub_domain, Request $request, Rotators $rotator)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $rotator ) {
			if ( $request->has('flag') ) {
				switch ( $request->input('flag') ) {
					case 'editRotatorsUrl':
						$rules = [
							'name'             => 'required|min:4|max:255|unique:mysql_tenant.rotator_urls',
							'url'              => 'required|url|max:255',
							'max_clicks'       => 'numeric|nullable|min:1|max:99999',
							'max_daily_clicks' => 'numeric|nullable|min:1|max:99999',
							'min_mobile'       => 'numeric|nullable|min:1|max:100',
							'max_mobile'       => 'numeric|nullable|min:1|max:100',
						];
						
						$rotators_url = new RotatorsUrl();
						
						if ( $request->has('url_id') && $request->input('url_id') > 0 ) {
							$rules = [
								'name'             => [ 'min:4', 'max:255', 'required', Rule::unique('mysql_tenant.rotator_urls')->ignore($request->input('url_id')) ],
								'url'              => 'required|url|max:255',
								'max_clicks'       => 'numeric|nullable|min:1|max:99999',
								'max_daily_clicks' => 'numeric|nullable|min:1|max:99999',
								'min_mobile'       => 'numeric|nullable|min:1|max:100',
								'max_mobile'       => 'numeric|nullable|min:1|max:100',
							];
							
							$rotators_url = $rotators_url->find($request->input('url_id'));
						}
						
						Validator::make($request->all(), $rules)->validate();
						
						$params = $request->all();
						foreach ( $params as $key => $val ) {
							if ( is_null($val) ) {
								$params[$key] = '';
							}
						}
						$geo_targeting_include_countries = ($params['geo_targeting'] == 'Specified' && $params['geo_targeting_include_countries'] != '') ? implode(",", $params['geo_targeting_include_countries']) : '';
						$geo_targeting_exclude_countries = ($params['geo_targeting'] == 'Specified' && $params['geo_targeting_exclude_countries'] != '') ? implode(",", $params['geo_targeting_exclude_countries']) : '';
						
						if ( $params['start_date'] != '' ) {
							$params['start_date'] = strtotime($params['start_date']);
						}
						
						if ( $params['end_date'] != '' ) {
							$params['end_date'] = strtotime($params['end_date']);
						}
						
						$rotators_url->fill($params);
						
						$rotators_url->rotator_id = $rotator->id;
						$rotators_url->user_id    = auth()->id();
						
						$rotators_url->geo_targeting_include_countries = $geo_targeting_include_countries;
						$rotators_url->geo_targeting_exclude_countries = $geo_targeting_exclude_countries;
						
						$rotators_url->save();
						
						return response()->redirectTo('rotators');
						break;
					case 'resetRotatorsUrl':
						$url_id = $request->input('url_id');
						
						$this->rotatorsRepo->deleteRotatorsUrl($url_id);
						
						$rotators_urls = new RotatorsUrl();
						$rotators_urls = $rotators_urls->find($url_id);
						
						$rotators_urls->total_clicks  = 0;
						$rotators_urls->unique_clicks = 0;
						
						$rotators_urls->unique_click_per_day = 0;
						
						$rotators_urls->save();
						
						$request->session()->flash('success', 'Rotators URL successfully reset');
						
						return response('success');
						break;
					case 'resetState':
						$this->rotatorsRepo->deleteRotatorsLog($rotator);
						
						$rotator->total_clicks  = 0;
						$rotator->unique_clicks = 0;
						
						$rotator->unique_click_per_day = 0;
						
						$rotator->save();
						
						$request->session()->flash('success', 'Rotators successfully reset');
						
						return response('success');
						break;
					case 'cloneRotator':
						$this->validate($request, [
							'rotator_link' => 'required|min:4|max:255|unique:mysql_tenant.rotators'
						], [
							'rotator_link.required' => 'The rotator link is required.',
						]);
						
						$duplicateRotator = $rotator->replicate();
						
						$duplicateRotator->rotator_name = $request->input('rotator_link');
						
						$duplicateRotator->rotator_link  = $request->input('rotator_link');
						$duplicateRotator->total_clicks  = 0;
						$duplicateRotator->unique_clicks = 0;
						
						$duplicateRotator->unique_click_per_day = 0;
						$duplicateRotator->backup_url_clicks    = 0;
						$duplicateRotator->mobile_url_clicks    = 0;
						
						$duplicateRotator->created_at = time();
						$duplicateRotator->updated_at = time();
						
						$duplicateRotator->save();
						
						$userLR = getLRStatusByUser();
						
						$alert_status = $userLR['rotator_url'] == '1' ? '1' : '0';
						
						$linkAlertsRepo = new LinkRotatorsAlertRepository();
						
						$linkAlertsRepo->create(auth()->id(), $duplicateRotator->id, '1', $alert_status);
						
						$request->session()->flash('success', 'Rotator successfully cloned.');
						
						echo '<script>window.parent.$(".close").click();window.parent.location = window.parent.location.href;window.parent.$.fancybox.close();</script>';
						break;
					case 'linkAlert':
						$linkAlertsRepo = new LinkRotatorsAlertRepository();
						
						$linkAlertsD = $linkAlertsRepo->getValueByColumns([ 'id' ], [ [ 'user_id', '=', auth()->id() ], [ 'ref_id', '=', $rotator->id ], [ 'ref_type', '=', '1' ] ]);
						
						$status = $request->input('alert_status') == 'status_yes' ? '1' : '0';
						if ( count($linkAlertsD) > 0 ) {
							$linkAlertsRepo->update(auth()->id(), $rotator->id, '1', $status);
						} else {
							$linkAlertsRepo->create(auth()->id(), $rotator->id, '1', $status);
						}
						
						return response()->redirectTo('rotators/' . $rotator->id . '/edit?flag=' . $request->input('flag'));
						break;
				}
			} else {
				$rules = [
					'rotator_name' => [ 'min:4', 'max:255', Rule::unique('mysql_tenant.rotators')->ignore($rotator->id), 'nullable' ],
					'rotator_link' => [ 'required', 'alpha_dash', 'min:4', 'max:255', Rule::unique('mysql_tenant.rotators')->ignore($rotator->id), 'nullable' ],
					'backup_url'   => 'required|url|max:255',
					'mobile_url'   => 'url|different:backup_url|nullable',
					'popup_id'     => 'required_with:cloak_rotator|nullable',
					'magickbar_id' => 'required_with:cloak_rotator|nullable',
					'timer_id'     => 'required_with:cloak_rotator|nullable',
				];
				
				if ( $request->has('geo_targeting') && $request->input('geo_targeting') == '1' ) {
					$rules['geo_targeting'] = 'required_with:geo_targeting_include_countries,geo_targeting_exclude_countries';
				}
				
				Validator::make($request->all(), $rules)->validate();
				
				if ( $request->has('cloak_rotator') && $request->input('cloak_rotator') == '1' ) {
					if ( !xFrameOption($request->input('backup_url')) ) {
						return redirect()->back()->withErrors([ 'cloak_rotator' => 'Cloak Rotators not supported' ]);
					}
				}
				
				$input_arr = $request->all();
				foreach ( $input_arr as $key => $val ) {
					if ( is_null($val) ) {
						$input_arr[$key] = '';
						if ( $key == 'tracking_domain' || $key == 'geo_targeting' )
							$input_arr[$key] = '0';
					}
				}
				
				$geo_targeting_include_countries = isset($input_arr['geo_targeting_include_countries']) ? implode(",", $input_arr['geo_targeting_include_countries']) : '';
				$geo_targeting_exclude_countries = isset($input_arr['geo_targeting_exclude_countries']) ? implode(",", $input_arr['geo_targeting_exclude_countries']) : '';
				
				$delet_new_bot = isset($input_arr['detect_new_bots']) ? '1' : '0';
				
				$rotator->fill($input_arr);
				
				$rotator->tracking_domain = isset($input_arr['tracking_domain']) ? $input_arr['tracking_domain'] : '0';
				$rotator->ignore_last_url = isset($input_arr['ignore_last_url']) ? $input_arr['ignore_last_url'] : '0';
				$rotator->detect_new_bots = $delet_new_bot;
				
				$rotator->geo_targeting_include_countries = $geo_targeting_include_countries;
				$rotator->geo_targeting_exclude_countries = $geo_targeting_exclude_countries;
				
				$rotator->updated_at = time();
				
				$rotator->save();
				
				$request->session()->flash('success', 'Rotator updated successfully.');
				
				return response()->redirectTo('rotators');
			}
		} else {
			$request->session()->flash('error', 'Rotators ID does not exits');
			
			return response()->redirectTo('rotators');
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param String                    $sub_domain
	 * @param  \App\Models\Rotators     $rotator
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($sub_domain, Rotators $rotator, Request $request)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		if ( $rotator ) {
			if ( $request->has('flag') ) {
				switch ( $request->input('flag') ) {
					case 'rotators_url':
						$url_id = $request->input('url_id');
						
						$this->rotatorsRepo->deleteRotatorsUrl($url_id);
						
						$rotators_urls = new RotatorsUrl();
						
						$rotators_urls->where('id', '=', $url_id)->delete();
						
						$request->session()->flash('success', 'Rotators URL successfully deleted');
						
						return response('success');
						break;
				}
			} else {
				$this->rotatorsRepo->deleteRotatorsLog($rotator);
				
				$rotators_urls = new RotatorsUrl();
				
				$rotators_urls->where('rotator_id', '=', $rotator->id)->delete();
				
				$rotator->delete();
				
				$request->session()->flash('success', 'Rotators successfully deleted');
				
				return response('success');
			}
		} else {
			$request->session()->flash('error', 'Rotators ID does not exits');
			
			return response()->redirectTo('rotators');
		}
	}
}
