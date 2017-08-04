<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 7/11/2017
 * Time: 7:32 AM
 */

namespace App\Http\Controllers;

use App\Facades\Tenant;
use App\Http\Repositories\Accounts\LinksRepository;
use App\Models\Link;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tracker;

class ConversionTrackingController extends Controller
{
	protected $linksRepo;
	
	public function __construct(LinksRepository $linksRepository)
	{
		$this->linksRepo = $linksRepository;
	}
	
	public function convertAction($user_id, Request $request)
	{
		$sub_domain = User::where('id', '=', $user_id)->pluck('domain');
		session([ 'sub_domain' => $sub_domain ]);
		$track_lid_action = $request->cookie('track_lid_action');
		if ( isset($track_lid_action) && !is_null($track_lid_action) && $track_lid_action != '' ) {
			$details = explode('~~~', $track_lid_action);
			
			$link_id      = $details[0];
			$url          = $details[1];
			$split_url_id = isset($details[2]) ? $details[2] : 0;
			
			$count = Link::where('id', $link_id)->where('user_id', $user_id)->count();
			if ( $count > 0 ) {
				//Increase action count
				Link::where('id', $link_id)->increment('actions');
				$visitor      = Tracker::currentSession();
				$finger_print = '';
				
				$unique_action         = '0';
				$unique_action_per_day = '0';
				if ( $visitor->client_ip != '' ) {
					$exists = $this->linksRepo->chkUniqueTracking($visitor->client_ip, $link_id, '1');
					
					if ( !$exists ) {
						$unique_action = '1';
					}
					
					$unique_action_per_day_exists = $this->linksRepo->chkUniqueTrackingPerDay($visitor->client_ip, $link_id, '1');
					if ( !$unique_action_per_day_exists ) {
						$unique_action_per_day = '1';
					}
				}
				if ( $visitor->device_id != '' ) {
					$data_arr = [
						'link_id'              => $link_id,
						'cookie_id'            => $finger_print,
						'device_id'            => $visitor->device_id,
						'agent_id'             => $visitor->agent_id,
						'client_ip'            => $visitor->client_ip,
						'referer_id'           => ($visitor->referer_id == '') ? 0 : $visitor->referer_id,
						'geoip_id'             => ($visitor->geoip_id == '') ? 0 : $visitor->geoip_id,
						'url'                  => $url,
						'unique_click'         => $unique_action,
						'unique_click_per_day' => $unique_action_per_day,
						'type'                 => '1',
						'split_url_id'         => $split_url_id,
						'created_at'           => DB::raw("unix_timestamp(NOW())"),
						'updated_at'           => DB::raw("unix_timestamp(NOW())")
					];
					Tenant::DB()->table('links_log')->insertGetId($data_arr);
					
					setcookie('track_lid_action', '', time() - 3600, '/', '.' . config('site.site_domain'));
				}
			}
		}
	}
	
	private function chkValidAmount($amt)
	{
		if ( preg_match("/^[0-9]+(\\.[0-9]{1,2})?$/", $amt) ) {
			return true;
		}
		
		return false;
	}
	
	public function convertSales($user_id, Request $request)
	{
		$sub_domain = User::where('id', '=', $user_id)->pluck('domain');
		session([ 'sub_domain' => $sub_domain ]);
		
		$track_lid_sales = $request->cookie('track_lid_sales');
		
		if ( isset($track_lid_sales) && !is_null($track_lid_sales) && $track_lid_sales != '' ) {
			$details = explode('~~~', $track_lid_sales);
			$link_id = $details[0];
			$url     = $details[1];
			
			$split_url_id = isset($details[2]) ? $details[2] : 0;
			
			$amt = 0.00;
			
			if ( $request->has('amt') && $request->input('amt') != '' ) {
				if ( $this->chkValidAmount($amt) ) {
					$amt = $request->input('amt');
				}
			}
			
			$count = Link::where('id', $link_id)->where('user_id', $user_id)->count();
			if ( $count > 0 ) {
				$unique_sale = '0';
				
				//Increase action count
				Link::where('id', $link_id)->increment('actions');
				
				$visitor = Tracker::currentSession();
				
				$finger_print = '';
				
				$unique_sale_per_day = '0';
				if ( $visitor->client_ip != '' ) {
					$exists = $this->linksRepo->chkUniqueTracking($visitor->client_ip, $link_id, '2');
					
					if ( !$exists ) {
						$unique_sale = '1';
					}
					
					$unique_sale_per_day_exists = $this->linksRepo->chkUniqueTrackingPerDay($visitor->client_ip, $link_id, '2');
					if ( !$unique_sale_per_day_exists ) {
						$unique_sale_per_day = '1';
					}
				}
				
				//Log Sales
				if ( $visitor->device_id != '' ) {
					$data_arr = [
						'link_id'              => $link_id,
						'cookie_id'            => $finger_print,
						'device_id'            => $visitor->device_id,
						'agent_id'             => $visitor->agent_id,
						'client_ip'            => $visitor->client_ip,
						'referer_id'           => ($visitor->referer_id == '') ? 0 : $visitor->referer_id,
						'geoip_id'             => ($visitor->geoip_id == '') ? 0 : $visitor->geoip_id,
						'url'                  => $url,
						'unique_click'         => $unique_sale,
						'unique_click_per_day' => $unique_sale_per_day,
						'type'                 => '2',
						'amount'               => $amt,
						'split_url_id'         => $split_url_id,
						'created_at'           => DB::Raw("unix_timestamp(NOW())"),
						'updated_at'           => DB::Raw("unix_timestamp(NOW())")
					];
					
					Tenant::DB()->table('links_log')->insertGetId($data_arr);
				}
				setcookie('track_lid_sales', '', time() - 3600, '/', '.' . config('site.site_domain'));
			}
		}
	}
	
	public function convertEvent($user_id, Request $request)
	{
		$sub_domain = User::where('id', '=', $user_id)->pluck('domain');
		session([ 'sub_domain' => $sub_domain ]);
		$track_lid_event = $request->cookie('track_lid_event');
		
		if ( isset($track_lid_event) && $track_lid_event != '' && !is_null($track_lid_event) ) {
			$details = explode('~~~', $track_lid_event);
			$link_id = $details[0];
			$url     = $details[1];
			
			$split_url_id = isset($details[2]) ? $details[2] : 0;
			
			//Check link id exists for the user
			$count = Link::where('id', $link_id)->where('user_id', $user_id)->count();
			if ( $count > 0 ) {
				//Increase action count
				Link::where('id', $link_id)->increment('events');
				
				$visitor = Tracker::currentSession();
				
				$finger_print = '';
				
				$unique_event = '0';
				
				$unique_event_per_day = '0';
				if ( $visitor->client_ip != '' ) {
					$exists = $this->linksRepo->chkUniqueTracking($visitor->client_ip, $link_id, '3');
					if ( !$exists ) {
						$unique_event = '1';
					}
					$unique_event_per_day_exists = $this->linksRepo->chkUniqueTrackingPerDay($visitor->client_ip, $link_id, '3');
					if ( !$unique_event_per_day_exists ) {
						$unique_event_per_day = '1';
					}
				}
				
				//Log Events
				if ( $visitor->device_id != '' ) {
					$data_arr = [
						'link_id'              => $link_id,
						'cookie_id'            => $finger_print,
						'device_id'            => $visitor->device_id,
						'agent_id'             => $visitor->agent_id,
						'client_ip'            => $visitor->client_ip,
						'referer_id'           => ($visitor->referer_id == '') ? 0 : $visitor->referer_id,
						'geoip_id'             => ($visitor->geoip_id == '') ? 0 : $visitor->geoip_id,
						'url'                  => $url,
						'unique_click'         => $unique_event,
						'unique_click_per_day' => $unique_event_per_day,
						'type'                 => '3',
						'split_url_id'         => $split_url_id,
						'created_at'           => DB::raw("unix_timestamp(NOW())"),
						'updated_at'           => DB::raw("unix_timestamp(NOW())")
					];
					Tenant::DB()->table('links_log')->insertGetId($data_arr);
				}
				setcookie('track_lid_event', '', time() - 3600, '/', '.' . config('site.site_domain'));
			}
		}
	}
}