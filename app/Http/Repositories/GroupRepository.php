<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 7:44 AM
 */

namespace App\Http\Repositories;

use App\Group;
use Illuminate\Support\Facades\DB;

class GroupRepository extends Repository
{
	public function model()
	{
		return app(Group::class);
	}
	
	/**
	 * Get Group data.
	 *
	 * @return mixed
	 */
	public function getData()
	{
		return $this->model()->select('groups.*', DB::raw('COUNT(users_groups.group_id) as user_count'))
			->leftJoin('users_groups', 'groups.id', '=', 'users_groups.group_id')
			->groupBy('groups.id')->get();
	}
	
	/**
	 * Create Group data
	 *
	 * @param $request
	 */
	public function create($request)
	{
		$ins_data = [
			'name'       => $request->name,
			'group_code' => is_null($request->group_code) ? '' : $request->group_code,
			'is_admin'   => $request->is_admin
		];
		
		$this->model()->insert($ins_data);
		
		$request->session()->flash('success', 'Group created successfully');
	}
	
	public function update($request, $group)
	{
		$group->name       = $request->name;
		$group->group_code = is_null($request->group_code) ? '' : $request->group_code;
		$group->is_admin   = $request->is_admin;
		
		$group->save();
		
		$request->session()->flash('success', 'Group updated successfully');
	}
}