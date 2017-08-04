<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\Popbars;

class PopBarsRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(Popbars::class);
	}
	
	public function getAll()
	{
		$pop_bars = $this->model()->where('status', '<>', '2')->get();
		
		return $pop_bars;
	}
	
	public function getListsByDate($start_date, $end_date)
	{
		$qry = $this->model()->where('status', '<>', 'Deleted');
		$qry = $qry->whereRaw("(from_unixtime(created_at, '%Y-%m-%d') >= ? AND from_unixtime(created_at, '%Y-%m-%d') <= ?)", [ $start_date, $end_date ]);
		
		return $qry;
	}
	
	public function fetchMagickbarDetailsById($magickbar_id, $status = '')
	{
		$magickbar_details = $this->model()->where('id', '=', $magickbar_id);
		
		if ( $status != '' ) {
			$magickbar_details = $magickbar_details->where('status', '=', $status);
		}
		
		$magickbar_details = $magickbar_details->first();
		
		return $magickbar_details;
	}
	
	public function updateDisplayCount($magickbar_id)
	{
		$bar_count_update = $this->model()->where('id', '=', $magickbar_id)->increment('display_count');
		
		return $bar_count_update;
	}
}