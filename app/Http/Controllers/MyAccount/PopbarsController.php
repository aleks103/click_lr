<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\PopBarsRepository;
use App\Models\Popbars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PopbarsController extends Controller
{
    protected $popBarsRepo;

    public function __construct(PopBarsRepository $popBarsRepository)
    {
        $this->middleware('auth');

        $this->popBarsRepo = $popBarsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchParams = [
            'start_date' => '',
            'end_date' => '',
            'page' => 1
        ];
        if ($request->has('start_date')) {
            $searchParams['start_date'] = $request->input('start_date');
        }

        if ($request->has('end_date')) {
            $searchParams['end_date'] = $request->input('end_date');
        }
        if ($request->has('page')) {
            $searchParams['page'] = $request->input('page');
        }

        $start_date = ($searchParams['start_date'] != '') ? $searchParams['start_date'] : config('site.start_date');
        $end_date   = ($searchParams['end_date'] != '') ? $searchParams['end_date'] : date('Y-m-d');
        $query = $this->popBarsRepo->getListsByDate($start_date, $end_date);

        $dataRows = $query->paginate(20);

        $statusAry = ['Active', 'Inactive', 'Deleted'];
        return response()->view('users.popbar', compact('searchParams', 'dataRows', 'statusAry'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param String $sub_domain
     *
     * @return \Illuminate\Http\Response
     */
    public function create($sub_domain)
    {
        //
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        return response()->view('users.popbarAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param String $sub_domain
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($sub_domain, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($request->has('flag')) {
            if ($request->input('flag') == 'add') {
                $this->validate($request, [
                    'bar_name' => 'required|min:4|unique:mysql_tenant.popbar',
                    'height' => 'required|numeric|min:10|max:200'
                ]);
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }
                $popbar = new Popbars();
                $popbar->created_at = time();
                $popbar->updated_at = time();
                $popbar->fill($params);
                $popbar->save();

                return response()->redirectTo('popbars');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Popbars $popbar
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($sub_domain, Popbars $popbar, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($popbar) {
            if ($request->has('flag')) {
                $flag = $request->input('flag');
                switch ($flag) {
                    case 'preview':
                        return response()->view('users.popbarPreview', compact('popbar'));
                        break;
                }
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('Popbars');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Popbars $popbar
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($sub_domain, Popbars $popbar)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        return response()->view('users.popbarEdit', compact('popbar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Popbars $popbar
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($sub_domain, Popbars $popbar, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($popbar) {
            if ($request->has('flag')) {
                if ($request->input('flag') == 'edit') {
                    Validator::make($request->all(), [
                        'bar_name' => [
                            'required',
                            'min:4',
                            Rule::unique('mysql_tenant.magick_bar')->ignore($popbar->id)
                        ],
                        'height' => 'required|numeric|min:10|max:200'
                    ])->validate();

                    $popbar->fill($request->all());
                    $popbar->save();

                    return response()->redirectTo('popbars');
                } else if ($request->input('flag') == 'clone') {
                    $duplicatePopbar = $popbar->replicate();
                    $duplicatePopbar->bar_name = $popbar->bar_name . ' Clone';
                    $duplicatePopbar->created_at = time();
                    $duplicatePopbar->updated_at = time();
                    $duplicatePopbar->save();
                } else if ($request->input('flag') == 'reset') {
                    $popbar->display_count = 0;
                    $popbar->save();
                }
                return response('success');
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('popbars');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Popbars $popbar
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_domain, Popbars $popbar, Request $request)
    {
        if ($popbar) {
            if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
                abort(401, 'Session is expired.');
            }
            $popbar->delete();
            $request->session()->flash('success', 'Link successfully deleted.');
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response('failure');
        }

        return response('success');
    }
}
