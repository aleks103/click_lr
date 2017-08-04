<?php

namespace App\Http\Controllers\MyAccount;

use App\Models\LinkGroup;
use App\Models\RotatorsGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\LinkgroupsRepository;

class LinkgroupsController extends Controller
{
    protected $linkRepo;

    public function __construct(LinkgroupsRepository $linkgroupsRepository)
    {
        $this->middleware('auth');

        $this->linkRepo = $linkgroupsRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = $this->linkRepo->getLinkGroups();
        $linkGroups = $query->paginate(10);

        $query = $this->linkRepo->getRotatorGroups();
        $rotatorGroups = $query->paginate(10);

        return response()->view('users.groups', compact('linkGroups', 'rotatorGroups'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($sub_domain, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($request->has('flag')) {
            if ($request->input('flag') == 'add-group') {
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }
                if($request->has('group_type') && $request->input('group_type') == 1){
                    $newrow = new LinkGroup();
                    $newrow->link_group = $params['group_name'];
                } else {
                    $newrow = new RotatorsGroup();
                    $newrow->rotator_group = $params['group_name'];
                }

                $newrow->created_at = time();
                $newrow->updated_at = time();
                $newrow->save();

                return response()->redirectTo('linkgroups');

            } else if ($request->input('flag') == 'add-subgroup') {
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }
                if($request->has('group_type') && $request->input('group_type') == 1){
                    $newrow = new LinkGroup();
                    $newrow->link_group = $params['group_name'];
                    $newrow->parent_id = $params['parent_group1'];
                } else {
                    $newrow = new RotatorsGroup();
                    $newrow->rotator_group = $params['group_name'];
                    $newrow->parent_id = $params['parent_group2'];
                }

                $newrow->created_at = time();
                $newrow->updated_at = time();
                $newrow->save();

                return response()->redirectTo('linkgroups');

            } else if ($request->input('flag') == 'edit-group') {
                if ($request->has('group_id')) {
                    $params = $request->all();
                    foreach ($params as $key => $val) {
                        if (is_null($val))
                            $params[$key] = '';
                    }
                    if($request->has('group_type') && $request->input('group_type') == 1){
                        $updaterow = LinkGroup::find($params['group_id']);
                        $updaterow->link_group = $params['group_name'];
                    } else {
                        $updaterow = RotatorsGroup::find($params['group_id']);
                        $updaterow->rotator_group = $params['group_name'];
                    }

                    //$updaterow->created_at = time();
                    $updaterow->updated_at = time();
                    $updaterow->save();
                }

                return response()->redirectTo('linkgroups');
            } else if ($request->input('flag') == 'edit-subgroup') {
                if ($request->has('group_id')) {
                    $params = $request->all();
                    foreach ($params as $key => $val) {
                        if (is_null($val))
                            $params[$key] = '';
                    }
                    if ($request->has('group_type') && $request->input('group_type') == 1) {
                        $updaterow = LinkGroup::find($params['group_id']);
                        $updaterow->link_group = $params['group_name'];
                        $updaterow->parent_id = $params['parent_group1'];
                    } else {
                        $updaterow = RotatorsGroup::find($params['group_id']);
                        $updaterow->rotator_group = $params['group_name'];
                        $updaterow->parent_id = $params['parent_group2'];
                    }

                    //$newrow->created_at = time();
                    $updaterow->updated_at = time();
                    $updaterow->save();
                }

                return response()->redirectTo('linkgroups');

            } else if ($request->input('flag') == 'delete') {
                if ($request->has('id')) {

                    $id = $request->input('id');
                    $tblflag = $request->input('tblflag');
                    $this->linkRepo->deleteGroups($id, $tblflag);

                    $request->session()->flash('success', 'Group successfully deleted.');
                } else {
                    $request->session()->flash('error', 'Group ID does not exits');

                    return response('failure');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LinkGroup  $linkGroup
     * @return \Illuminate\Http\Response
     */
    public function show(LinkGroup $linkGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LinkGroup  $linkGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(LinkGroup $linkGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LinkGroup  $linkGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LinkGroup $linkGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LinkGroup  $linkGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }
}
