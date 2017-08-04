<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/29/2017
 * Time: 7:00 PM
 */

namespace App\Http\Controllers;

use App\Http\Repositories\Accounts\IpManagerRepository;
use App\Http\Repositories\Accounts\PopBarsRepository;
use App\Http\Repositories\Accounts\PopupRepository;
use App\Http\Repositories\Accounts\RotatorsRepository;
use App\Http\Repositories\Accounts\TimersRepository;
use App\Http\Repositories\CountriesRepository;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tracker;

class TrackingRotatorController extends Controller
{
	protected $rotatorsRepo;
	protected $ipManRepo;
	protected $countryRepo;
	protected $popupRepo;
	protected $popbarRepo;
	protected $timerRepo;
	
	public function __construct(RotatorsRepository $rotatorsRepository, IpManagerRepository $ipManagerRepository, CountriesRepository $countriesRepository, PopupRepository $popupRepository, PopBarsRepository $popBarsRepository, TimersRepository $timersRepository)
	{
		$this->rotatorsRepo = $rotatorsRepository;
		$this->ipManRepo    = $ipManagerRepository;
		$this->popupRepo    = $popupRepository;
		$this->popbarRepo   = $popBarsRepository;
		$this->timerRepo    = $timersRepository;
		$this->countryRepo  = $countriesRepository;
	}
	
	public function index($sub_domain, $rotator_name, Request $request)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $rotator_name));
		}
		
		$columns = [
			'id', 'user_id', 'rotator_link', 'rotator_mode', 'on_finish', 'cloak_rotator', 'cloak_page_title', 'cloak_page_description', 'cloak_page_image_url', 'backup_url',
			'popup_id', 'magickbar_id', 'timer_id', 'cookie_duration', 'mobile_url', 'geo_targeting', 'geo_targeting_include_countries', 'geo_targeting_exclude_countries',
			'pixel_code'
		];
		
		$rotator_details = $this->rotatorsRepo->getValueByColumns($columns, [ [ 'rotator_link', '=', $rotator_name ], [ 'status', '=', '0' ] ]);
		
		if ( count($rotator_details) > 0 ) {
			$rotator_details = $rotator_details[0];
			
			$result = checkUserClickLimit($rotator_details['user_id']);
			if ( $result == false || $result == 'No_plan' ) {
				throw new NotFoundHttpException(sprintf('Invalid rotators - %s', $rotator_name));
			}
			
			$first_time = 'no';
			
			if ( $sub_domain != '' ) {
				if ( session()->has('cookie_set_' . $sub_domain . '_' . $rotator_details['id']) && session()->get('cookie_set_' . $sub_domain . '_' . $rotator_details['id']) == 'yes' ) {
					$first_time = 'yes';
					
					session()->forget('cookie_set_' . $sub_domain . '_' . $rotator_details['id']);
				}
			} else {
				if ( session()->has('cookie_set_' . $rotator_details['id']) && session()->get('cookie_set_' . $rotator_details['id']) != '' ) {
					$first_time = 'yes';
					
					session()->forget('cookie_set_' . $rotator_details['id']);
				}
			}
			
			$d_arr['rotator_name'] = $rotator_name;
			
			if ( $request->hasCookie('fp_id') ) {
				$finger_print = $request->cookie('fp_id');
			} else {
				$finger_print = md5($request->server('SERVER_ADDR'));
			}
			
			if ( $finger_print != '' || $first_time == 'yes' ) {
				return $this->redirectToTrackingRotator($rotator_details, $finger_print, $rotator_name, $first_time);
			} else {
				return view('trackingRotator', compact('d_arr'));
			}
		} else {
			throw new NotFoundHttpException(sprintf('Invalid link - %s', $rotator_name));
		}
	}
	
	public function redirectToTrackingRotator($rotator_details, $finger_print, $rotator_name, $first_time = 'no')
	{
		if ( ($first_time == 'yes' && $finger_print == '') ) {
			header('Location:' . $rotator_details['backup_url']);
			exit;
		}
		
		$d_arr['rotator_name'] = $rotator_name;
		
		$visitor = Tracker::currentSession();
		
		//Moved to Rabbit MQ
		$filter_ip = false;
		//Filter Clicks
		if ( $visitor->client_ip != '' ) {
			$ipaddress = $this->ipManRepo->getAll();
			
			if ( count($ipaddress) > 0 ) {
				foreach ( $ipaddress as $ip ) {
					$visitor_ip = $visitor->client_ip;
					$ip_addr    = ip2long($visitor_ip);
					
					if ( $ip->from_ip_address == $ip->to_ip_address ) {
						if ( $ip_addr == $ip->from_ip_address ) {
							$filter_ip = true;
						}
					} else {
						$range_low  = $ip->from_ip_address;
						$range_high = $ip->to_ip_address;
						
						if ( $ip_addr >= $range_low && $ip_addr <= $range_high ) {
							$filter_ip = true;
						}
					}
				}
			}
		}
		
		$track_url      = $rotator_details['backup_url'];
		$track          = 'backup';
		$rotator_url_id = 0;
		$on_finish      = '0';
		$mobile_click   = '0';
		
		if ( $visitor->device->is_mobile && $rotator_details['mobile_url'] != "" ) {
			$track_url = $rotator_details['mobile_url'];
			
			$mobile_click = '1';
		} else {
			$rotator_urls = $this->rotatorsRepo->getRotatorUrlsForTrack($rotator_details['id']);
			
			$mobile_restrict_url = [];
			if ( count($rotator_urls) > 0 ) {
				//Update the url as paused
				foreach ( $rotator_urls as $rotator_url ) {
					$paused_status = false;
					if ( $rotator_url->status != '1' ) {
						$today_clicks = $this->rotatorsRepo->getUrlTodayClicks($rotator_details['id'], $rotator_url->id);
						
						if ( $rotator_url->max_daily_clicks > 0 && ($today_clicks >= $rotator_url->max_daily_clicks) ) {
							$paused_status = true;
							
							$status_update_arr = [ 'status' => '1' ];
							$this->rotatorsRepo->updateTrackUrl($rotator_url->id, $status_update_arr);
						} else {
							$max_clicks = $rotator_url->max_clicks;
							if ( $rotator_url->bonus > 0 ) {
								$bonus_count = ($max_clicks * $rotator_url->bonus) / 100;
								$max_clicks  = $max_clicks + $bonus_count;
							}
							
							if ( $max_clicks > 0 && ($rotator_url->unique_click_per_day >= $max_clicks) ) {
								$paused_status = true;
								
								$status_update_arr = [ 'status' => '1' ];
								$this->rotatorsRepo->updateTrackUrl($rotator_url->id, $status_update_arr);
								
								if ( $rotator_url->notify_max_clicks_reached == '1' ) {
									//Insert into notification table
									$this->rotatorsRepo->addRotatorUrlNotification($rotator_details['user_id'], $rotator_details['id'], $rotator_url->id);
								}
							}
						}
						
						if ( $visitor->device->is_mobile && $rotator_url->max_mobile > 0 ) {
							if ( !$paused_status ) {
								$rotator_log_details = $this->rotatorsRepo->getRotatorLogDetails($rotator_details['id'], $rotator_url->id);
								
								$device_id = DeviceId();
								
								$mobile_click_count = 0;
								if ( count($rotator_log_details) > 0 ) {
									foreach ( $rotator_log_details as $rotator_log_det ) {
										if ( array_search($rotator_log_det->device_id, $device_id) !== false )//mobile clicks
											$mobile_click_count += 1;
									}
									
									$mobile_click_percent = round(($mobile_click_count / count($rotator_log_details)) * 100);
									if ( $mobile_click_percent >= $rotator_url->max_mobile ) {
										$mobile_restrict_url[] = $rotator_url->id;
									}
								}
							}
						}
					}
				}
				
				if ( $rotator_details['rotator_mode'] == '0' ) {
					//Fullfillment - Get first active url
					$active_rotator_url = $this->rotatorsRepo->getActiveRotatorUrl($rotator_details['id'], $mobile_restrict_url);
					if ( count($active_rotator_url) > 0 ) {
						$track_url = $active_rotator_url->url;
						
						$rotator_url_id = $active_rotator_url->id;
						
						$track = 'active_url';
					}
				} else if ( $rotator_details['rotator_mode'] == '1' ) {
					//Spillover
					$last_url_id = 0;
					
					$last_visited_url = $this->rotatorsRepo->getLastVisitedRotatorUrl($finger_print, $visitor->client_ip, $rotator_details['id']);
					if ( count($last_visited_url) > 0 ) {
						$last_url_id = $last_visited_url->rotator_url_id;
					}
					
					//Get Next Rotator Url
					$next_url_details = $this->rotatorsRepo->getNextRotatorUrl($rotator_details['id'], $last_url_id, $mobile_restrict_url);
					if ( count($next_url_details) > 0 ) {
						$track_url = $next_url_details->url;
						
						$rotator_url_id = $next_url_details->id;
						
						$track = 'active_url';
					} else {
						$on_finish = '1';
						//Fetch spillover onfinish details
						if ( $rotator_details['on_finish'] == '1' ) {
							//Last url
							$last_rotator_url = $this->rotatorsRepo->getLastRotatorUrl($rotator_details['id'], $mobile_restrict_url);
							if ( count($last_rotator_url) > 0 ) {
								$track_url = $last_rotator_url->url;
								
								$rotator_url_id = $last_rotator_url->id;
								
								$track = 'active_url';
							}
						} else if ( $rotator_details['on_finish'] == '2' ) {
							//Top of Rotator
							$active_rotator_url = $this->rotatorsRepo->getActiveRotatorUrl($rotator_details['id'], $mobile_restrict_url);
							if ( count($active_rotator_url) > 0 ) {
								$track_url = $active_rotator_url->url;
								
								$rotator_url_id = $active_rotator_url->id;
								
								$track = 'active_url';
							}
						}
					}
				} else {
					//Get Random URL
					$random_url_details = $this->rotatorsRepo->getRandomRotatorUrl($rotator_details['id'], $mobile_restrict_url);
					if ( count($random_url_details) > 0 ) {
						$track_url = $random_url_details->url;
						
						$rotator_url_id = $random_url_details->id;
						
						$track = 'active_url';
					}
				}
			}
		}
		
		//Check Unique Click or not
		$unique_click = '0';
		
		$exists = $this->rotatorsRepo->chkUniqueClick($finger_print, $visitor->client_ip, $rotator_details['id'], $rotator_url_id);
		if ( !$exists ) {
			$unique_click = '1';
		}
		
		$unique_click_per_day       = '0';
		$unique_perday_click_exists = $this->rotatorsRepo->chkUniqueClickPerDay($finger_print, $visitor->client_ip, $rotator_details['id'], $rotator_url_id);
		if ( !$unique_perday_click_exists ) {
			$unique_click_per_day = '1';
		}
		
		$rotator_url_details = [];
		$url_geo_target      = false;
		if ( $rotator_url_id > 0 ) {
			$rotator_url_details = $this->rotatorsRepo->getRotatorUrlsById($rotator_url_id);
			if ( count($rotator_url_details) > 0 ) {
				if ( $rotator_url_details->geo_targeting == '1' ) {
					$url_geo_target = true;
				}
			}
		}
		
		if ( $url_geo_target ) {
			if ( count($rotator_url_details) > 0 ) {
				if ( $visitor->geoip_id != '' ) {
					$current_country = getCurrentCountry($visitor->geoip_id);
					
					//Check for include / exclude countries
					$included_countries = $excluded_countries = [];
					
					$geotargeting_include_countries = $rotator_url_details->geo_targeting_include_countries;
					$geotargeting_exclude_countries = $rotator_url_details->geo_targeting_exclude_countries;
					
					$countries_list = $this->countryRepo->getIncludedExcludedCountries($geotargeting_include_countries, $geotargeting_exclude_countries);
					if ( count($countries_list) > 0 ) {
						if ( count($countries_list['included_countries']) > 0 ) {
							foreach ( $countries_list['included_countries'] as $included ) {
								$included_countries[] = $included['code'];
							}
						}
						
						//Check for exclude countries
						if ( count($countries_list['excluded_countries']) > 0 ) {
							foreach ( $countries_list['excluded_countries'] AS $excluded ) {
								$excluded_countries[] = $excluded['code'];
							}
						}
					}
					
					if ( count($included_countries) > 0 ) {
						if ( !in_array($current_country, $included_countries) ) {
							$track_url = $rotator_details['backup_url'];
							$track     = 'backup';
						}
					}
					
					if ( count($excluded_countries) > 0 ) {
						if ( in_array($current_country, $excluded_countries) ) {
							$track_url = $rotator_details['backup_url'];
							$track     = 'backup';
						}
					}
				}
			}
		} else {
			if ( $rotator_details['geo_targeting'] == '1' ) {
				if ( $visitor->geoip_id != '' ) {
					$current_country = getCurrentCountry($visitor->geoip_id);
					
					//Check for include / exclude countries
					$included_countries = $excluded_countries = [];
					
					$geotargeting_include_countries = $rotator_details['geo_targeting_include_countries'];
					$geotargeting_exclude_countries = $rotator_details['geo_targeting_exclude_countries'];
					
					$countries_list = $this->countryRepo->getIncludedExcludedCountries($geotargeting_include_countries, $geotargeting_exclude_countries);
					if ( count($countries_list) > 0 ) {
						if ( count($countries_list['included_countries']) > 0 ) {
							foreach ( $countries_list['included_countries'] AS $included ) {
								$included_countries[] = $included['code'];
							}
						}
						
						//Check for exclude countries
						if ( count($countries_list['excluded_countries']) > 0 ) {
							foreach ( $countries_list['excluded_countries'] AS $excluded ) {
								$excluded_countries[] = $excluded['code'];
							}
						}
					}
					
					if ( count($included_countries) > 0 ) {
						if ( !in_array($current_country, $included_countries) ) {
							$track_url = $rotator_details['backup_url'];
							$track     = 'backup';
						}
					}
					
					if ( count($excluded_countries) > 0 ) {
						if ( in_array($current_country, $excluded_countries) ) {
							$track_url = $rotator_details['backup_url'];
							$track     = 'backup';
						}
					}
				}
			}
		}
		
		//Moved to Rabbit MQ
		if ( !$filter_ip ) {
			$total_clicks = DB::raw('total_clicks + 1');
			
			$update_arr['total_clicks'] = $total_clicks;
			if ( $mobile_click == '1' ) {
				$mobile_url_clicks = DB::raw('mobile_url_clicks + 1');
				
				$update_arr['mobile_url_clicks'] = $mobile_url_clicks;
			}
			if ( $unique_click == '1' ) {
				$unique_clicks = DB::raw('unique_clicks + 1');
				
				$update_arr['unique_clicks'] = $unique_clicks;
			}
			if ( $unique_click_per_day == '1' ) {
				$unique_click_per_day = DB::raw('unique_click_per_day + 1');
				
				$update_arr['unique_click_per_day'] = $unique_click_per_day;
			}
			if ( $track == 'backup' ) {
				$backup_url_clicks = DB::raw('backup_url_clicks + 1');
				
				$update_arr['backup_url_clicks'] = $backup_url_clicks;
			}
			$this->rotatorsRepo->updateTrack($rotator_details['id'], $update_arr);
			
			if ( $rotator_url_id > 0 ) {
				$url_total_clicks = DB::raw('total_clicks + 1');
				
				$update_url_arr['total_clicks'] = $url_total_clicks;
				if ( $unique_click == '1' ) {
					$url_unique_clicks = DB::raw('unique_clicks + 1');
					
					$update_url_arr['unique_clicks'] = $url_unique_clicks;
				}
				$this->rotatorsRepo->updateTrackUrl($rotator_url_id, $update_url_arr);
			}
		}
		
		$filtered_click = '0';
		
		if ( $filter_ip ) {
			$filtered_click = '1';
		}
		
		if ( $visitor->device_id != '' ) {
			$data_arr = [
				'rotator_id'           => $rotator_details['id'],
				'rotator_url_id'       => $rotator_url_id,
				'cookie_id'            => $finger_print,
				'device_id'            => $visitor->device_id,
				'agent_id'             => $visitor->agent_id,
				'client_ip'            => $visitor->client_ip,
				'referer_id'           => ($visitor->referer_id == '') ? 0 : $visitor->referer_id,
				'geoip_id'             => ($visitor->geoip_id == '') ? 0 : $visitor->geoip_id,
				'url'                  => $track_url,
				'unique_click'         => $unique_click,
				'unique_click_per_day' => $unique_click_per_day,
				'on_finish_url'        => $on_finish,
				'created_at'           => time(),
				'updated_at'           => time(),
				'rotator_reset'        => '0',
				'filtered_click'       => $filtered_click,
			];
			$this->rotatorsRepo->insertLog($data_arr);
		}
		
		if ( $rotator_details['cloak_rotator'] == '1' && xFrameOption($track_url) ) {
			$popup_details = $magickbar_details = [];
			
			$popup_id     = $rotator_details['popup_id'];
			$magickbar_id = $rotator_details['magickbar_id'];
			$timer_id     = $rotator_details['timer_id'];
			
			if ( count($rotator_url_details) > 0 ) {
				$url_popup_id = $rotator_url_details->popup_id;
				if ( $url_popup_id > 0 ) {
					$popup_id = $url_popup_id;
				}
				
				$url_magickbar_id = $rotator_url_details->magickbar_id;
				if ( $url_magickbar_id > 0 ) {
					$magickbar_id = $url_magickbar_id;
				}
			}
			
			if ( $popup_id > 0 ) {
				$popup_arr = $this->popupRepo->fetchPopupDetailsById($popup_id, 'Active');
				
				if ( count($popup_arr) > 0 ) {
					$popup_details = $popup_arr;
					if ( $popup_details->cookie_duration == '' ) {
						$popup_details->cookie_duration = 0;
					}
					if ( $popup_details->delay_timing == '' ) {
						$popup_details->delay_timing = 0;
					} else {
						$popup_details->delay_timing = $popup_details->delay_timing * 1000;
					}
				}
			}
			$magickbar_details = [];
			if ( $magickbar_id > 0 ) {
				$magickbar_arr = $this->popbarRepo->fetchMagickbarDetailsById($magickbar_id, '0');
				
				if ( count($magickbar_arr) > 0 ) {
					$magickbar_details = $magickbar_arr;
					
					$magickbar_details->position            = ($magickbar_arr->position == 1) ? 'top' : 'bottom';
					$magickbar_details->shadow              = ($magickbar_arr->shadow == 1) ? 'true' : '0';
					$magickbar_details->closable            = ($magickbar_arr->closable == 1) ? 'right' : 'hidden';
					$magickbar_details->spacer              = ($magickbar_arr->spacer == 1) ? 'true' : '0';
					$magickbar_details->btcolor             = $magickbar_arr->button_color;
					$magickbar_details->transbg             = ($magickbar_arr->transparent_background == 1) ? 'transparent' : '#' . $magickbar_arr->button_color;
					$magickbar_details->url                 = ($magickbar_arr->url <> '') ? $magickbar_arr->url : '';
					$magickbar_details->html                = $magickbar_arr->id;
					$magickbar_details->preview_before_save = 'no';
					
					if ( $magickbar_details->delay_timing == '' ) {
						$magickbar_details->delay_timing = 0;
					} else {
						$magickbar_details->delay_timing = $magickbar_details->delay_timing * 1000;
					}
				}
			}
			
			$timer_details = [];
			if ( $timer_id > 0 ) {
				$timer_arr = $this->timerRepo->fetchTimerDetailsById($timer_id, '0');
				
				if ( count($timer_arr) > 0 ) {
					$timer_details = $timer_arr;
					
					$timer_details->position = ($timer_arr->position == 1) ? 'top' : 'bottom';
					
					$timer_details->background_color = ($timer_arr->transparent == 1) ? 'transparent' : '#' . $timer_arr->background_color;
				}
			}
			$rotator_details['track_url'] = $track_url;
			
			return view('trackRotator', compact('rotator_details', 'popup_details', 'magickbar_details', 'timer_details', 'd_arr'));
		} else {
			if ( $rotator_details['pixel_code'] != '' ) {
				$d_arr['pixel_code'] = $rotator_details['pixel_code'];
				
				return view('trackPixelCode', compact('track_url', 'd_arr'));
			} else {
				header('Location:' . $track_url);
				exit;
			}
		}
	}
	
	public function getPreviewHtml($sub_domain, $magickbar_id)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $magickbar_id));
		}
		
		$data['html'] = '';
		
		$magibar = $this->popbarRepo->fetchMagickbarDetailsById($magickbar_id);
		if ( count($magibar) > 0 ) {
			if ( $magibar->url != '' ) {
				$data['html'] = ($magibar->url <> '') ? $magibar->url : '';
				$data['url']  = 'true';
			} else {
				$data['html'] = ($magibar->content <> '') ? $magibar->content : '';
				$data['url']  = 'false';
			}
			//Update Magickbar Display Count
			$this->popbarRepo->updateDisplayCount($magickbar_id);
		}
		
		return view('magikbarHtmlPreview', compact('data'));
	}
	
	public function getLoadPopupPage($sub_domain, $popup_id, Request $request)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $popup_id));
		}
		
		$popup_details = $this->popupRepo->fetchPopupDetailsById($popup_id);
		if ( $request->has('tracking') && $request->input('tracking') == '1' ) {
			//Update popup Display Count
			$this->popupRepo->updateDisplayCount($popup_id);
		}
		
		return view('loadPopup', compact('popup_details'));
	}
	
	public function getUpdateClicks($sub_domain, Request $request)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $request->input('rotator_name')));
		}
		
		$rotator_name = $request->input('rotator_name');
		
		$is_valid_rotator = $this->rotatorsRepo->chkValidRotatorName($rotator_name);
		if ( $is_valid_rotator ) {
			return redirect('tr/' . $rotator_name);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $request->input('rotator_name')));
		}
	}
}