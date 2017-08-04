<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/29/2017
 * Time: 7:00 PM
 */

namespace App\Http\Controllers;

use App\Http\Repositories\Accounts\IpManagerRepository;
use App\Http\Repositories\Accounts\LinksRepository;
use App\Http\Repositories\Accounts\PopBarsRepository;
use App\Http\Repositories\Accounts\PopupRepository;
use App\Http\Repositories\Accounts\TimersRepository;
use App\Http\Repositories\CountriesRepository;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tracker;

class TrackingLinkController extends Controller
{
	protected $linksRepo;
	protected $ipManRepo;
	protected $countryRepo;
	protected $popupRepo;
	protected $popbarRepo;
	protected $timerRepo;
	
	public function __construct(LinksRepository $linksRepository, IpManagerRepository $ipManagerRepository, CountriesRepository $countriesRepository, PopupRepository $popupRepository, PopBarsRepository $popBarsRepository, TimersRepository $timersRepository)
	{
		$this->linksRepo  = $linksRepository;
		$this->ipManRepo  = $ipManagerRepository;
		$this->popupRepo  = $popupRepository;
		$this->popbarRepo = $popBarsRepository;
		$this->timerRepo  = $timersRepository;
		
		$this->countryRepo = $countriesRepository;
	}
	
	public function index($sub_domain, $link_name, Request $request)
	{
		if ( $sub_domain != '' ) {
			session([ 'sub_domain' => $sub_domain ]);
		} else {
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $link_name));
		}
		
		$columns = [
			'id', 'user_id', 'primary_url', 'cloak_link', 'cloak_page_title', 'cloak_page_description', 'cloak_page_image_url', 'popup_id', 'password', 'max_clicks', 'backup_url',
			'total_clicks', 'unique_clicks', 'unique_click_per_day', 'geo_targeting', 'geo_targeting_include_countries', 'geo_targeting_exclude_countries', 'magickbar_id',
			'timer_id', 'mobile_url', 'repeat_url', 'tracking_link_visited', 'smartswap_id', 'smartswap_type', 'pixel_code'
		];
		
		$link_details = $this->linksRepo->getValueByColumns($columns, [ [ 'tracking_link', '=', $link_name ], [ 'status', '<>', 'Deleted' ] ]);
		
		if ( count($link_details) > 0 ) {
			$link_details = $link_details[0];
			
			$result = checkUserClickLimit($link_details['user_id']);
			
			if ( $result == false || $result == 'No_plan' ) {
				throw new NotFoundHttpException(sprintf('Invalid link - %s', $link_name));
			}
			
			$first_time = 'no';
			
			if ( $sub_domain != '' ) {
				if ( session()->has('cookie_set_' . $sub_domain . '_' . $link_details['id']) && session()->get('cookie_set_' . $sub_domain . '_' . $link_details['id']) == 'yes' ) {
					$first_time = 'yes';
					
					session()->forget('cookie_set_' . $sub_domain . '_' . $link_details['id']);
				}
			} else {
				if ( session()->has('cookie_set_' . $link_details['id']) && session()->get('cookie_set_' . $link_details['id']) != '' ) {
					$first_time = 'yes';
					
					session()->forget('cookie_set_' . $link_details['id']);
				}
			}
			
			$d_arr['link_name'] = $link_name;
			
			if ( $request->hasCookie('fp_id') ) {
				$finger_print = $request->cookie('fp_id');
			} else {
				$finger_print = md5($request->server('SERVER_ADDR'));
			}
			
			if ( $finger_print != '' || $first_time == 'yes' ) {
				return $this->redirectToTrackingLink($link_details, $finger_print, $link_name, $first_time);
			} else {
				return view('trackingLink', compact('d_arr'));
			}
		} else {
			throw new NotFoundHttpException(sprintf('Invalid link - %s', $link_name));
		}
	}
	
	public function redirectToTrackingLink($link_details, $finger_print, $link_name, $first_time = 'no')
	{
		if ( ($first_time == 'yes' && $finger_print == '') ) {
			header('Location:' . $link_details['primary_url']);
			exit;
		}
		
		$d_arr['link_name'] = $link_name;
		
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
		
		//Get Unique Clicks count
		$unique_clicks_perday = $this->linksRepo->getUniqueClicksPerday($link_details['id']);
		
		//Check Unique Click or not
		$unique_click = '0';
		$exists       = $this->linksRepo->chkUniqueClick($finger_print, $visitor->client_ip, $link_details['id']);
		if ( !$exists ) {
			$unique_click = '1';
		}
		
		$unique_click_per_day = '0';
		
		$unique_perday_click_exists = $this->linksRepo->chkUniqueClickPerDay($finger_print, $visitor->client_ip, $link_details['id']);
		if ( !$unique_perday_click_exists ) {
			$unique_click_per_day = '1';
		}
		
		//Moved to Rabbit MQ
		if ( !$filter_ip ) {
			$total_clicks = DB::raw('total_clicks + 1');
			
			$update_arr['total_clicks'] = $total_clicks;
			if ( $unique_click == '1' ) {
				$unique_clicks = DB::raw('unique_clicks + 1');
				
				$update_arr['unique_clicks'] = $unique_clicks;
			}
			if ( $unique_click_per_day == '1' ) {
				$unique_click_per_day = DB::raw('unique_click_per_day + 1');
				
				$update_arr['unique_click_per_day'] = $unique_click_per_day;
			}
			$this->linksRepo->updateTrack($link_details['id'], $update_arr);
		}
		
		$split_id  = 0;
		$track_url = $link_details['primary_url'];
		if ( $visitor->device->is_mobile ) {
			if ( $link_details['mobile_url'] != '' ) {
				$split_id  = 0;
				$track_url = $link_details['mobile_url'];
			}
		} else {
			$smart_swap_id = $link_details['smartswap_id'];
			if ( $smart_swap_id > 0 ) {
				$smart_swap_type = $link_details['smartswap_type'];
				
				$visited = false;
				if ( $smart_swap_type == '0' ) {
					$visited = $this->linksRepo->chkSmartSwapLinkAlreadyVisited($finger_print, $smart_swap_id);
				} else {
					$visited = $this->linksRepo->chkSmartSwapRotatorAlreadyVisited($finger_print, $smart_swap_id);
				}
				if ( $visited ) {
					$track_url = $link_details['backup_url'];
				}
			}
			
			$split_count = $this->linksRepo->chkSplitUrlExists($link_details['id']);
			if ( $split_count > 0 ) {
				$chkFingerPrintId = $this->linksRepo->checkCookieIdExists($finger_print, $link_details['id']);
				if ( $chkFingerPrintId ) {
					$lastCookieDetail = $this->linksRepo->getLastCookieDetail($finger_print, $link_details['id']);
					
					$split_id = $lastCookieDetail->split_url_id;
					//check if the split url exists
					if ( $links_split_url = $this->linksRepo->chkSplitUrlIdExists($split_id) ) {
						$track_url = $links_split_url->split_url;
					}
				}
			}
			
			//Checking repeat Url
			if ( $link_details['max_clicks'] == 0 || ($link_details['max_clicks'] > 0 && ($unique_clicks_perday < $link_details['max_clicks'])) ) {
				if ( $unique_click == '1' && $link_details['tracking_link_visited'] == 'Yes' && $link_details['repeat_url'] != '' ) {
					//Check this primary url exists in any other link
					$url_details = $this->linksRepo->chkPrimaryUrlExists($link_details['primary_url'], $link_details['id']);
					
					$ids = [];
					if ( count($url_details) > 0 ) {
						foreach ( $url_details AS $url_det ) {
							$ids[] = $url_det->id;
						}
					}
					
					if ( count($ids) > 0 ) {
						$link_ids = $ids;
						
						$url_exists = $this->linksRepo->chkTrackingLinkVisited($finger_print, $visitor->client_ip, $link_ids);
						if ( $url_exists ) {
							$split_id  = 0;
							$track_url = $link_details['repeat_url'];
						}
					}
				} else if ( $unique_click == '0' && $link_details['repeat_url'] != '' ) {
					$split_id  = 0;
					$track_url = $link_details['repeat_url'];
				}
			}
			
			if ( $link_details['max_clicks'] > 0 && ($unique_clicks_perday >= $link_details['max_clicks']) ) {
				$split_id  = 0;
				$track_url = $link_details['backup_url'];
			}
			
			if ( $link_details['geo_targeting'] == 'Specified' && ($link_details['geo_targeting_include_countries'] != '' || $link_details['geo_targeting_exclude_countries'] != '') ) {
				if ( $visitor->geoip_id != '' ) {
					$current_country = getCurrentCountry($visitor->geoip_id);
					
					$included_countries = $excluded_countries = [];
					
					$geotargeting_include_countries = $link_details['geo_targeting_include_countries'];
					$geotargeting_exclude_countries = $link_details['geo_targeting_exclude_countries'];
					
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
							$split_id  = 0;
							$track_url = $link_details['backup_url'];
						}
					}
					
					if ( count($excluded_countries) > 0 ) {
						if ( in_array($current_country, $excluded_countries) ) {
							$split_id  = 0;
							$track_url = $link_details['backup_url'];
						}
					}
				}
			}
		}
		
		//Moved to Rabbit MQ
		$filtered_click = '0';
		if ( $filter_ip ) {
			$filtered_click = '1';
		}
		
		if ( $visitor->device_id != '' ) {
			$data_arr = [
				'link_id'    => $link_details['id'],
				'cookie_id'  => $finger_print,
				'device_id'  => $visitor->device_id,
				'agent_id'   => $visitor->agent_id,
				'client_ip'  => $visitor->client_ip,
				'referer_id' => ($visitor->referer_id == '') ? 0 : $visitor->referer_id,
				'geoip_id'   => ($visitor->geoip_id == '') ? 0 : $visitor->geoip_id,
				'url'        => $track_url,
				
				'unique_click'         => $unique_click,
				'unique_click_per_day' => $unique_click_per_day,
				
				'type'           => '0',
				'split_url_id'   => $split_id,
				'link_reset'     => '0',
				'filtered_click' => $filtered_click,
				'created_at'     => time(),
				'updated_at'     => time()
			];
			
			$this->linksRepo->insertLog($data_arr);
		}
		
		$cookie_domain = config('site.site_domain');
		if ( isset($_SERVER['HTTP_HOST']) ) {
			$hostparts = explode(':', $_SERVER['HTTP_HOST']);
			if ( strstr($hostparts[0], 'clkpfct.com') ) {
				$cookie_domain = 'clkpfct.com';
			}
			
			if ( strstr($hostparts[0], 'clckperfect.com') ) {
				$cookie_domain = 'clckperfect.com';
			}
		}
		
		setcookie('track_lid_action', $link_details['id'] . '~~~' . $track_url . '~~~' . $split_id, time() + 3600, '/', '.' . $cookie_domain);
		setcookie('track_lid_sales', $link_details['id'] . '~~~' . $track_url . '~~~' . $split_id, time() + 3600, '/', '.' . $cookie_domain);
		setcookie('track_lid_event', $link_details['id'] . '~~~' . $track_url . '~~~' . $split_id, time() + 3600, '/', '.' . $cookie_domain);
		
		if ( $link_details['cloak_link'] == 'Yes' && xFrameOption($track_url) ) {
			$popup_details = $magickbar_details = [];
			
			if ( $link_details['popup_id'] > 0 ) {
				$popup_arr = $this->popupRepo->fetchPopupDetailsById($link_details['popup_id'], 'Active');
				
				if ( count($popup_arr) > 0 ) {
					$popup_details = $popup_arr;
					if ( $popup_details->delay_timing == '' ) {
						$popup_details->delay_timing = 0;
					} else {
						$popup_details->delay_timing = $popup_details->delay_timing * 1000;
					}
				}
			}
			
			$magickbar_details = [];
			if ( $link_details['magickbar_id'] > 0 ) {
				$magickbar_arr = $this->popbarRepo->fetchMagickbarDetailsById($link_details['magickbar_id'], '0');
				
				if ( count($magickbar_arr) > 0 ) {
					$magickbar_details           = $magickbar_arr;
					$magickbar_details->position = ($magickbar_arr->position == 1) ? 'top' : 'bottom';
					$magickbar_details->shadow   = ($magickbar_arr->shadow == 1) ? 'true' : '0';
					$magickbar_details->closable = ($magickbar_arr->closable == 1) ? 'right' : 'hidden';
					$magickbar_details->spacer   = ($magickbar_arr->spacer == 1) ? 'true' : '0';
					$magickbar_details->btcolor  = $magickbar_arr->button_color;
					$magickbar_details->transbg  = ($magickbar_arr->transparent_background == 1) ? 'transparent' : '#' . $magickbar_arr->button_color;
					$magickbar_details->url      = ($magickbar_arr->url <> '') ? $magickbar_arr->url : '';
					$magickbar_details->html     = $magickbar_arr->id;
					
					if ( $magickbar_details->delay_timing == '' ) {
						$magickbar_details->delay_timing = 0;
					} else {
						$magickbar_details->delay_timing = $magickbar_details->delay_timing * 1000;
					}
				}
			}
			
			$timer_details = [];
			if ( $link_details['timer_id'] > 0 ) {
				$timer_arr = $this->timerRepo->fetchTimerDetailsById($link_details['timer_id'], '0');
				if ( count($timer_arr) > 0 ) {
					$timer_details = $timer_arr;
					
					$timer_details->position         = ($timer_arr->position == 1) ? 'top' : 'bottom';
					$timer_details->background_color = ($timer_arr->transparent == 1) ? 'transparent' : '#' . $timer_arr->background_color;
				}
			}
			$link_details['track_url'] = $track_url;
			
			return view('trackLink', compact('link_details', 'popup_details', 'magickbar_details', 'timer_details', 'd_arr'));
		} else {
			if ( $link_details['pixel_code'] != '' ) {
				$d_arr['pixel_code'] = $link_details['pixel_code'];
				
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
			throw new NotFoundHttpException(sprintf('Not found sub domain for %s', $request->input('link_name')));
		}
		
		$link_name     = $request->input('link_name');
		$is_valid_link = $this->linksRepo->chkValidLinkName($link_name);
		if ( $is_valid_link ) {
			$link_details = $this->linksRepo->getLinkDetailsByName($link_name);
			if ( count($link_details) > 0 ) {
				if ( $request->has('cookie') && $request->input('cookie') != '' ) {
					return redirect('go/' . $link_name . '?c=f');
				} else {
					return redirect('go/' . $link_name);
				}
			} else {
				if ( $request->has('cookie') && $request->input('cookie') != '' ) {
					return redirect('go/' . $link_name . '?c=f');
				} else {
					return redirect('go/' . $link_name);
				}
			}
		} else {
			if ( $request->has('cookie') && $request->input('cookie') != '' ) {
				return redirect('go/' . $link_name . '?c=f');
			} else {
				return redirect('go/' . $link_name);
			}
		}
	}
}