<?php

namespace App\Console\Commands;

use App\LinkAlertEmailLog;
use App\LinkRotatorsAlert;
use App\Mail\linksUrlAlertClickPerfect;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LinkAlertMsg extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'EmailSend:linkAlert';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Link Alert Message Email Send';
	
	protected $linkAlert;
	
	/**
	 * LinkAlertMsg constructor. Create a new command instance.
	 *
	 * @param LinkRotatorsAlert $linkRotatorsAlert
	 */
	public function __construct(LinkRotatorsAlert $linkRotatorsAlert)
	{
		parent::__construct();
		$this->linkAlert = $linkRotatorsAlert;
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$get_links = $this->linkAlert
			->select('link_rotator_alerts.id', 'link_rotator_alerts.user_id', 'link_rotator_alerts.ref_id', 'link_rotator_alerts.ref_type', 'users.email', 'users.first_name', 'users.domain')
			->from('link_rotator_alerts')
			->leftJoin('users', 'link_rotator_alerts.user_id', '=', 'users.id')
			->where('link_rotator_alerts.status', '=', '1')
			->orderBy('link_rotator_alerts.updated_at', 'asc')
			->take(10)->get();
		if ( count($get_links) > 0 ) {
			foreach ( $get_links as $get_link ) {
				$sub_domain = $get_link->domain;
				if ( $sub_domain == '' || is_null($sub_domain) ) {
					continue;
				}
				
				config([ 'database.connections.mysql_tenant.database' => config('site.db_prefix') . strtolower($sub_domain) ]);
				$db_connection = 'mysql_tenant';
				
				$id       = $get_link->id;
				$user_id  = $get_link->user_id;
				$ref_id   = $get_link->ref_id;
				$ref_type = $get_link->ref_type; //0-links, 1-rotators
				
				$up_data = [ 'updated_at' => time() ];
				$this->linkAlert->where('id', '=', $id)->update($up_data);
				
				if ( $ref_type == '0' ) {
					$select_links = DB::connection($db_connection)->table('links')->whereRaw('id = ? AND status = ?', [ $ref_id, 'Active' ])->first();
					if ( isset($select_links) ) {
						$update_email_log = [];
						$links_error_type = [];
						
						$mail_send = false;
						
						$update_email_log['user_id']    = $user_id;
						$update_email_log['ref_id']     = $ref_id;
						$update_email_log['ref_type']   = $ref_type;
						$update_email_log['status']     = '1';
						$update_email_log['created_at'] = time();
						$update_email_log['updated_at'] = time();
						
						$primary_count = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '0', '0', '1' ])
							->count();
						if ( $select_links->primary_url != '' && $primary_count <= 0 ) {
							$checking_primary_url = remoteFileExists($select_links->primary_url);
							
							if ( !$checking_primary_url ) {
								$mail_send = true;
								
								$links_error_type['primary_url']   = $select_links->primary_url;
								$update_email_log['error_message'] = 'Unresponsive link';
								
								$update_email_log['url_type'] = '0';
								$update_email_log['url']      = $select_links->primary_url;
								
								LinkAlertEmailLog::insert($update_email_log);
							}
						}
						
						$backup_count = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '0', '1', '1' ])
							->count();
						if ( $select_links->backup_url != '' && $backup_count <= 0 ) {
							$checking_backup_url = remoteFileExists($select_links->backup_url);
							if ( !$checking_backup_url ) {
								$mail_send = true;
								
								$links_error_type['backup_url']    = $select_links->backup_url;
								$update_email_log['error_message'] = 'Unresponsive link';
								
								$update_email_log['url_type'] = '1';
								$update_email_log['url']      = $select_links->backup_url;
								
								LinkAlertEmailLog::insert($update_email_log);
							}
						}
						
						$mobile_count = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '0', '2', '1' ])
							->count();
						if ( $select_links->mobile_url != '' && $mobile_count <= 0 ) {
							$checking_mobile_url = remoteFileExists($select_links->mobile_url);
							if ( !$checking_mobile_url ) {
								$mail_send = true;
								
								$links_error_type['mobile_url']    = $select_links->mobile_url;
								$update_email_log['error_message'] = 'Unresponsive link';
								
								$update_email_log['url_type'] = '2';
								$update_email_log['url']      = $select_links->mobile_url;
								
								LinkAlertEmailLog::insert($update_email_log);
							}
						}
						
						$repeat_url_count = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '0', '3', '1' ])
							->count();
						if ( $select_links->repeat_url != '' && $repeat_url_count <= 0 ) {
							$checking_repeat_url = remoteFileExists($select_links->repeat_url);
							if ( !$checking_repeat_url ) {
								$mail_send = true;
								
								$links_error_type['repeat_url']    = $select_links->repeat_url;
								$update_email_log['error_message'] = 'Unresponsive link';
								
								$update_email_log['url_type'] = '3';
								$update_email_log['url']      = $select_links->repeat_url;
								
								LinkAlertEmailLog::insert($update_email_log);
							}
						}
						
						if ( $mail_send ) {
							$mail_data = [];
							
							$mail_data['subject'] = 'Hi ' . $get_link->first_name . ',<br>Unresponsive Link Alert of Click Perfect!';
							
							$mail_data['body_message'] = [
								'Link Id: ' . $ref_id,
								'Link Name: ' . ($select_links->link_name != '') ? $select_links->link_name : $select_links->tracking_link,
								'Link Error Type:',
								''
							];
							
							$mail_data['out_line'] = [ 'Update the particular links in link list page.' ];
							
							if ( count($links_error_type) > 0 ) {
								foreach ( $links_error_type as $key => $single_link ) {
									if ( $key == 'sub_url' ) {
										$mail_data['body_message'][3] .= $single_link;
									} else {
										$mail_data['body_message'][3] .= implode($single_link, ', ');
									}
								}
							}
							Mail::to($get_link->email)->send(new linksUrlAlertClickPerfect($mail_data));
						}
					}
					
					DB::disconnect('mysql_tenant');
				} else {
					$select_rotators = DB::connection($db_connection)->table('rotators')->whereRaw('id = ? AND status = ?', [ $ref_id, '0' ])->first();
					
					if ( isset($select_rotators) ) {
						$mail_send_rotators = false;
						
						$links_error_type_rotators = [];
						
						$update_email_log_rotators = [];
						
						$update_email_log_rotators['user_id']    = $user_id;
						$update_email_log_rotators['ref_id']     = $ref_id;
						$update_email_log_rotators['ref_type']   = $ref_type;
						$update_email_log_rotators['status']     = '1';
						$update_email_log_rotators['created_at'] = time();
						$update_email_log_rotators['updated_at'] = time();
						
						$backup_count_rotators = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '1', '1', '1' ])
							->count();
						if ( $select_rotators->backup_url != '' && $backup_count_rotators <= 0 ) {
							$checking_rotators_backup_url = remoteFileExists($select_rotators->backup_url);
							if ( !$checking_rotators_backup_url ) {
								$mail_send_rotators = true;
								
								$links_error_type_rotators['backup_url']    = $select_rotators->backup_url;
								$update_email_log_rotators['error_message'] = 'Unresponsive link';
								
								$update_email_log_rotators['url_type'] = '1';
								$update_email_log_rotators['url']      = $select_rotators->backup_url;
								LinkAlertEmailLog::insert($update_email_log_rotators);
							}
						}
						
						$mobile_count_rotators = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '1', '2', '1' ])
							->count();
						if ( $select_rotators->mobile_url != '' && $mobile_count_rotators <= 0 ) {
							$checking_rotators_mobile_url = remoteFileExists($select_rotators->mobile_url);
							if ( !$checking_rotators_mobile_url ) {
								$mail_send_rotators = true;
								
								$links_error_type_rotators['mobile_url']    = $select_rotators->mobile_url;
								$update_email_log_rotators['error_message'] = 'Unresponsive link';
								
								$update_email_log_rotators['url_type'] = '2';
								$update_email_log_rotators['url']      = $select_rotators->mobile_url;
								LinkAlertEmailLog::insert($update_email_log_rotators);
							}
						}
						
						$cloak_page_count_rotators = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND status = ?', [ $user_id, $ref_id, '1', '4', '1' ])
							->count();
						if ( $select_rotators->cloak_page_image_url != '' && $cloak_page_count_rotators <= 0 ) {
							$checking_rotators_cloak_url = remoteFileExists($select_rotators->cloak_page_image_url);
							if ( !$checking_rotators_cloak_url ) {
								$mail_send_rotators = true;
								
								$links_error_type_rotators['cloak_page_image_url'] = $select_rotators->cloak_page_image_url;
								$update_email_log_rotators['error_message']        = 'Unresponsive link';
								
								$update_email_log_rotators['url_type'] = '4';
								$update_email_log_rotators['url']      = $select_rotators->cloak_page_image_url;
								LinkAlertEmailLog::insert($update_email_log_rotators);
							}
						}
						
						$links_error_type_rotators_all = [];
						
						$rotators_sub_urls = DB::connection($db_connection)->table('rotator_urls')->whereRaw("rotator_id = ? AND status = ?", [ $ref_id, '0' ])->get();
						if ( count($rotators_sub_urls) > 0 ) {
							foreach ( $rotators_sub_urls as $rotators_sub_url ) {
								$sub_rotators_count_rotators = LinkAlertEmailLog::whereRaw('user_id = ? AND ref_id = ? AND ref_type = ? AND url_type = ? AND sub_rotator_id = ? AND status = ?', [ $user_id, $ref_id, '1', '5', $rotators_sub_url->id, '1' ])
									->count();
								if ( $rotators_sub_url->url != '' && $sub_rotators_count_rotators <= 0 ) {
									$checking_sub_rotators_url = remoteFileExists($rotators_sub_url->url);
									if ( !$checking_sub_rotators_url ) {
										$mail_send_rotators = true;
										
										$links_error_type_rotators_all[$rotators_sub_url->id] = $rotators_sub_url->url;
										
										$update_email_log_rotators['error_message']  = 'Unresponsive link';
										$update_email_log_rotators['sub_rotator_id'] = $rotators_sub_url->id;
										
										$update_email_log_rotators['url_type'] = '5';
										$update_email_log_rotators['url']      = $rotators_sub_url->url;
										LinkAlertEmailLog::insert($update_email_log_rotators);
									}
								}
							}
							$links_error_type_rotators['sub_url'] = $links_error_type_rotators_all;
						}
						
						if ( $mail_send_rotators ) {
							$mail_data = [];
							
							$mail_data['subject'] = 'Hi ' . $get_link->first_name . ',<br>Unresponsive Rotators Alert of Click Perfect!';
							
							$mail_data['body_message'] = [
								'Rotators Id: ' . $ref_id,
								'Rotators Name: ' . ($select_rotators->rotator_name != '') ? $select_rotators->rotator_name : $select_rotators->rotator_link,
								'Rotators Error Type:',
								''
							];
							
							$mail_data['out_line'] = [ 'Update the particular rotators in rotators list page.' ];
							
							if ( count($links_error_type_rotators) > 0 ) {
								foreach ( $links_error_type_rotators as $key => $single_link ) {
									if ( $key == 'sub_url' ) {
										$mail_data['body_message'][3] .= $single_link;
									} else {
										$mail_data['body_message'][3] .= implode($single_link, ', ');
									}
								}
							}
							Mail::to($get_link->email)->send(new linksUrlAlertClickPerfect($mail_data));
						}
					}
					
					DB::disconnect('mysql_tenant');
				}
			}
		}
		
		return;
	}
}
