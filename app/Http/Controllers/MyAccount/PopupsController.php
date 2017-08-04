<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\PopupRepository;
use App\Models\Popups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PopupsController extends Controller
{
    protected $popupsRepo;

    public function __construct(PopupRepository $popupRepository)
    {
        $this->middleware('auth');

        $this->popupsRepo = $popupRepository;
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
        $query = $this->popupsRepo->getPopups($start_date, $end_date);

        $popups = $popups_list = $query->paginate(20);

        return response()->view('users.popup', compact('searchParams', 'popups', 'popups_list'));
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
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        return response()->view('users.popupAdd');
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
                    'popupname' => 'required|min:4|unique:mysql_tenant.popup',
                    'width' => 'required|numeric|min:100|max:1200',
                    'height' => 'required|numeric|min:100|max:800'
                ]);
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }
                //var_dump($params);
                //exit;
                $popup = new Popups();
                $popup->created_at = date('Y-m-d H:i:s');
                $popup->updated_at = date('Y-m-d H:i:s');
                $popup->unix_created_at = time();
                $popup->unix_updated_at = time();
                $popup->fill($params);
                $popup->save();

                return response()->redirectTo('popups');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Popups $popup
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($sub_domain, Popups $popup, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($popup) {
            if ($request->has('flag')) {
                $flag = $request->input('flag');
                switch ($flag) {
                    case 'previewPopup':
                        //$popup_details = $this->popupsRepo->getPopupById($popup->id);
                        return response()->view('users.popupPreview', compact('popup'));
                        break;
                }
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('popups');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Popups $popup
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($sub_domain, Popups $popup, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        return response()->view('users.popupEdit', compact('popup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Popups $popup
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($sub_domain, Popups $popup, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($popup) {
            if ($request->has('flag')) {
                if ($request->input('flag') == 'edit') {
                    // Split Url update
                    Validator::make($request->all(), [
                        'popupname' => [
                            'required',
                            'min:4',
                            Rule::unique('mysql_tenant.popup')->ignore($popup->id)
                        ],
                        'width' => 'required|numeric|min:100|max:1200',
                        'height' => 'required|numeric|min:100|max:800'
                    ])->validate();

                    $popup->fill($request->all());
                    $popup->save();

                    return response()->redirectTo('popups');
                } else if ($request->input('flag') == 'clonePopup') {
                    $duplicatePopup = $popup->replicate();

                    $duplicatePopup->popupname = $popup->popupname . ' Clone';
                    $duplicatePopup->created_at = date('Y-m-d H:i:s');
                    $duplicatePopup->updated_at = date('Y-m-d H:i:s');
                    $duplicatePopup->save();
                } else if ($request->input('flag') == 'resetPopup') {
                    $popup->display_count = 0;
                    $popup->save();
                }
                return response('success');
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('popups');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Popups $popup
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_domain, Popups $popup, Request $request)
    {
        if ($popup) {
            if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
                abort(401, 'Session is expired.');
            }
            $popup->delete();
            $request->session()->flash('success', 'Link successfully deleted.');
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response('failure');
        }

        return response('success');
    }
}
