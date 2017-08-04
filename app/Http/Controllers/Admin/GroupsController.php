<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Repositories\GroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GroupsController extends Controller
{
	protected $groups;
	
	public function __construct(GroupRepository $groupRepository)
	{
		$this->groups = $groupRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param Group $group
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$list = $this->groups->getData();
		
		return view('admin.groupList', compact('list'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.addGroup');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
		], [
			'name.required' => 'Group Name is required.',
		]);
		
		$this->groups->create($request);
		
		return redirect('admin/groups');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Group $group
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Group $group)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Group $group
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Group $group)
	{
		return view('admin.editGroup', compact('group'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Group               $group
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Group $group)
	{
		$this->validate($request, [
			'name' => 'required',
		], [
			'name.required' => 'Group Name is required.',
		]);
		
		$this->groups->update($request, $group);
		
		return redirect('admin/groups');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Group $group
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Group $group)
	{
		session()->flash('success', 'Group deleted successfully');
		
		DB::table('users_groups')->where('group_id', '=', $group->id)->delete();
		
		$group->delete();
		
		return redirect('admin/groups');
	}
}
