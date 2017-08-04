<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/22/2017
 * Time: 6:17 AM
 */

namespace App\Http\Controllers\MyAccount;


use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\LinksRepository;
use Illuminate\Http\Request;

class ConversionByTimeController extends Controller
{
	protected $linksRepo;
	
	public function __construct(LinksRepository $linksRepository)
	{
		$this->middleware('auth');
		
		$this->linksRepo = $linksRepository;
	}
	
	/**
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$searchParams = [
			'link_type'  => 'all-links',
			'start_date' => config('site.start_date'),
			'end_date'   => date('Y-m-d'),
		];
		
		$links_group = $this->linksRepo->getLinkGroups();
		
		return response()->view('users.conversionByTime', compact('links_group', 'searchParams'));
	}
	
	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getconversionbytime(Request $request)
	{
		$searchParams = [
			'link_type'  => 'all-links',
			'start_date' => config('site.start_date'),
			'end_date'   => date('Y-m-d'),
		];
		if ( $request->has('link_type') ) {
			$searchParams['link_type'] = $request->input('link_type');
		}
		
		if ( $request->has('start_date') ) {
			$searchParams['start_date'] = $request->input('start_date');
		}
		
		if ( $request->has('end_date') ) {
			$searchParams['end_date'] = $request->input('end_date');
		}
		
		$actions   = [ 'action', 'sales', 'event' ];
		$durations = [ 'hour', 'week', 'month' ];
		$results   = [];
		foreach ( $durations as $duration ) {
			foreach ( $actions as $action ) {
				$results[] = $this->linksRepo->getLinkLogs($searchParams['start_date'], $searchParams['end_date'], $searchParams['link_type'], $action, $duration);
			}
		}
		
		return response()->json(json_encode($results));
	}
}