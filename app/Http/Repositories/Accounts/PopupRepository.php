<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 6:47 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\Popups;

class PopupRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(Popups::class);
	}
	
	public function getAll()
	{
		$popups = $this->model()->where('status', '<>', 'Deleted')->get();
		
		return $popups;
	}
	
	public function getPopups($start_date, $end_date)
	{
		$qry = $this->model()->where('status', '<>', 'Deleted');
		$qry = $qry->whereRaw("(from_unixtime(unix_created_at, '%Y-%m-%d') >= ? AND from_unixtime(unix_created_at, '%Y-%m-%d') <= ?)", [ $start_date, $end_date ]);
		
		return $qry;
	}
	
	public function fetchPopupDetailsById($popup_id, $status = '')
	{
		$popup_details = $this->model()->where('id', '=', $popup_id);
		if ( $status != '' ) {
			$popup_details = $popup_details->where('status', '=', $status);
		}
		$popup_details = $popup_details->first();
		
		return $popup_details;
	}
	
	public function updateDisplayCount($popup_id)
	{
		$popup_count_update = $this->model()->where('id', '=', $popup_id)->increment('display_count');
		
		return $popup_count_update;
	}
}