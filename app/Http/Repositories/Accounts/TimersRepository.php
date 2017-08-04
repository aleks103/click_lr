<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\Timers;

class TimersRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(Timers::class);
	}
	
	public function getAll()
	{
		$rotators = $this->model()->where('status', '<>', '2')->get();
		
		return $rotators;
	}
	
	public function getListsByDate($start_date, $end_date)
	{
		$qry = $this->model()->where('status', '<>', 'Deleted');
		$qry = $qry->whereRaw("(from_unixtime(created_at, '%Y-%m-%d') >= ? AND from_unixtime(created_at, '%Y-%m-%d') <= ?)", [ $start_date, $end_date ]);
		
		return $qry;
	}
	
	public function fetchTimerDetailsById($timer_id, $status = '')
	{
		$timer_details = $this->model()->where('id', '=', $timer_id);
		if ( $status != '' ) {
			$timer_details = $timer_details->where('status', '=', $status);
		}
		$timer_details = $timer_details->first();
		
		return $timer_details;
	}
}