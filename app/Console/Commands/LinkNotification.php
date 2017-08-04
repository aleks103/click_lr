<?php

namespace App\Console\Commands;

use App\LinkNotificationLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LinkNotification extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'EmailSend:linkNotification';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Link Notification Message Email Send';
	
	/**
	 * Create a new command instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$select_users = DB::table('users')->whereRaw('domain != ? AND domain != ? AND activated = ?', [ 'NULL', '', '1' ])->get();
		
		if ( count($select_users) > 0 ) {
			foreach ( $select_users as $select_user ) {
				$sub_domain = $select_user->domain;
				if ( $sub_domain == '' || is_null($sub_domain) ) {
					continue;
				}
				
				config([ 'database.connections.mysql_tenant.database' => config('site.db_prefix') . strtolower($sub_domain) ]);
				$db_connection = 'mysql_tenant';
				
				$notification_links = DB::connection($db_connection)->table('links_notification')->whereRaw('is_deleted =?', [ '0' ])->get();
				
				if ( count($notification_links) > 0 ) {
					foreach ( $notification_links as $notification_link ) {
						$update_notification_log = [];
						
						$update_notification_log['user_id']    = $select_user->id;
						$update_notification_log['created_at'] = time();
						
						$update_notification_log['notification_id'] = $notification_link->id;
						
						$select_links = DB::connection($db_connection)->table('links')->whereraw('id =? AND status =?', [ $notification_link->link_id, 'Active' ])
							->first();
						
						$notification_log = LinkNotificationLog::whereRaw('user_id = ? AND notification_id = ? AND status = ?', [ $select_user->id, $notification_link->id, '1' ])
							->count();
						
						if ( isset($select_links) > 0 && $notification_log <= 0 ) {
							$percent_value = $notification_link->value;
							
							$click_check_condition = $notification_link->clicks;
							
							if ( $notification_link->notification_type == '1' ) {
								$unique_action_count = DB::connection($db_connection)->table('links_log')->whereraw('link_id =? AND unique_click_per_day =? AND type = ? AND filtered_click =?', [ $notification_link->link_id, '1', '1', '0' ])
									->count();
								
								$conversion_action_rate = 0;
								if ( $unique_action_count != 0 && $select_links->unique_click_per_day != 0 )
									$conversion_action_rate = round(($unique_action_count / $select_links->unique_click_per_day) * 100);
								if ( $notification_link->relational == '1' ) {
									if ( $conversion_action_rate > $percent_value && $select_links->unique_click_per_day > $click_check_condition ) {
										LinkNotificationLog::insert($update_notification_log);
									}
								} else {
									if ( $conversion_action_rate < $percent_value && $select_links->unique_click_per_day > $click_check_condition ) {
										LinkNotificationLog::insert($update_notification_log);
									}
								}
							}
						}
					}
				}
				DB::disconnect('mysql_tenant');
			}
		}
	}
}
