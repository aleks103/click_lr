<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\MapRepository;
use App\Map;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigsController extends Controller
{
	protected $maps;
	
	public function __construct(MapRepository $mapRepository)
	{
		$this->maps = $mapRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('admin.configManage');
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
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
		$inputs = [];
		
		$inputs['admin_email']     = $request->admin_email;
		$inputs['main_domain_url'] = $request->main_domain_url;
		$inputs['users_img_path']  = $request->users_img_path;
		$inputs['support_email']   = $request->support_email;
		$inputs['billing_perpage'] = $request->billing_perpage;
		$inputs['db_prefix']       = $request->db_prefix;
		$inputs['per_page_news']   = $request->per_page_news;
		$inputs['per_page_list']   = $request->per_page_list;
		
		$this->maps->saveSettings($inputs);
		
		$request->session()->flash('success', 'Settings saved successfully');
		
		return redirect('admin/configs');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Map $map
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Map $map)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Map $map
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Map $map)
	{
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Map                 $map
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Map $map)
	{
		$id = $request->route()->parameter('config');
		
		if ($id == 'all') {
			$this->maps->clearAllCache();
		} else {
			$this->maps->clearCache('map');
		}
		
		$request->session()->flash('success', 'Cache cleared successfully');
		
		return response('success');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Map $map
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Map $map)
	{
	}
}
