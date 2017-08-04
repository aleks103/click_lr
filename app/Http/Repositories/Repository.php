<?php
/**
 * Created by PhpStorm.
 * Time: 13:07
 */

namespace App\Http\Repositories;

abstract class Repository
{
	public abstract function model();
	
	public function count()
	{
		return $this->model()->count();
	}
	
	public function all()
	{
		return $this->model()->all();
	}
	
	public function getValueByColumns($columns, $where = [], $order = '')
	{
		$query = $this->model()->select($columns);
		
		if ( !empty($where) ) {
			$query = $query->where($where);
		}
		
		if ( !empty($order) ) {
			if (is_array($order)) {
				$query = $query->orderBy($order[0], $order[1]);
			} else {
				$query = $query->orderBy($order);
			}
		}
		
		$re_rows = $query->get();
		
		$reArray = [];
		
		foreach ( $re_rows as $key => $row ) {
			$reArray[$key] = [];
			
			for ( $i = 0; $i < count($columns); $i++ ) {
				$reArray[$key][$columns[$i]] = $row->$columns[$i];
			}
		}
		
		return $reArray;
	}
	
	public function getValueById($id)
	{
		return $this->model()->find($id);
	}
}