<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTrackerTable extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'clear:tracker';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear Tacker Tables';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$track_sessions = DB::table('tracker_sessions')->select('tracker_sessions.id')
			->whereRaw('tracker_sessions.updated_at <= DATE_SUB(NOW(), INTERVAL 12 HOUR)')->take(100)->get();
		if ( count($track_sessions) > 0 ) {
			foreach ( $track_sessions as $row ) {
				DB::table('tracker_sessions')->where('id', '=', $row->id)->delete();
				DB::table('tracker_log')->where('session_id', '=', $row->id)->delete();
			}
		}
		
		$tracker_agents = DB::table('tracker_agents')->select('tracker_agents.id')
			->whereRaw('tracker_agents.updated_at <= DATE_SUB(NOW(), INTERVAL 30 DAY)')->take(10)->get();
		if ( count($tracker_agents) > 0 ) {
			foreach ( $tracker_agents as $row ) {
				DB::table('tracker_agents')->where('id', '=', $row->id)->delete();
			}
		}
		
		$tracker_errors = DB::table('tracker_errors')->select('tracker_errors.id')
			->whereRaw('tracker_errors.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_errors) > 0 ) {
			foreach ( $tracker_errors as $row ) {
				DB::table('tracker_errors')->where('id', '=', $row->id)->delete();
			}
		}
		
		$tracker_domains = DB::table('tracker_domains')->select('tracker_domains.id')
			->whereRaw('tracker_domains.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_domains) > 0 ) {
			foreach ( $tracker_domains as $row ) {
				DB::table('tracker_domains')->where('id', '=', $row->id)->delete();
			}
		}
		
		$tracker_geoip = DB::table('tracker_geoip')->select('tracker_geoip.id')
			->whereRaw('tracker_geoip.updated_at <= DATE_SUB(NOW(), INTERVAL 30 DAY)')->take(10)->get();
		if ( count($tracker_geoip) > 0 ) {
			foreach ( $tracker_geoip as $row ) {
				DB::table('tracker_geoip')->where('id', '=', $row->id)->delete();
			}
		}
		
		$tracker_paths = DB::table('tracker_paths')->select('tracker_paths.id')
			->whereRaw('tracker_paths.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_paths) > 0 ) {
			foreach ( $tracker_paths as $row ) {
				DB::table('tracker_paths')->where('id', '=', $row->id)->delete();
			}
		}
		
		$tracker_query = DB::table('tracker_queries')->select('tracker_queries.id')
			->whereRaw('tracker_queries.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_query) > 0 ) {
			foreach ( $tracker_query as $row ) {
				DB::table('tracker_queries')->where('id', '=', $row->id)->delete();
				DB::table('tracker_query_arguments')->where('query_id', '=', $row->id)->delete();
			}
		}
		
		$tracker_query = DB::table('tracker_referers')
			->select('tracker_referers.id')
			->whereRaw('tracker_referers.updated_at <= DATE_SUB(NOW(), INTERVAL 30 DAY)')->take(10)->get();
		if ( count($tracker_query) > 0 ) {
			foreach ( $tracker_query as $row ) {
				DB::table('tracker_referers')->where('id', '=', $row->id)->delete();
				DB::table('tracker_referers_search_terms')->where('referer_id', '=', $row->id)->delete();
			}
		}
		
		$tracker_query = DB::table('tracker_routes')
			->select('tracker_routes.id')
			->whereRaw('tracker_routes.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_query) > 0 ) {
			foreach ( $tracker_query as $row ) {
				DB::table('tracker_routes')->where('id', '=', $row->id)->delete();
				DB::table('tracker_route_paths')->where('route_id', '=', $row->id)->delete();
			}
		}
		
		$tracker_query = DB::table('tracker_route_path_parameters')
			->select('tracker_route_path_parameters.id')
			->whereRaw('tracker_route_path_parameters.updated_at <= DATE_SUB(NOW(), INTERVAL 6 HOUR)')->take(100)->get();
		if ( count($tracker_query) > 0 ) {
			foreach ( $tracker_query as $row ) {
				DB::table('tracker_route_path_parameters')->where('id', '=', $row->id)->delete();
			}
		}
	}
}
