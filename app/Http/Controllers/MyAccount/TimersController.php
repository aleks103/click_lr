<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounts\TimersRepository;
use App\Models\Timers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TimersController extends Controller
{
    protected $timersRepo;

    public function __construct(TimersRepository $timersRepository)
    {
        $this->middleware('auth');

        $this->timersRepo = $timersRepository;
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
        $query = $this->timersRepo->getListsByDate($start_date, $end_date);

        $dataRows = $query->paginate(20);

        return response()->view('users.timer', compact('searchParams', 'dataRows'));
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
        return response()->view('users.timerAdd');
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
                    'timer_name' => 'required|min:4|unique:mysql_tenant.timer'
                ]);
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }
                $timer = new Timers();
                $timer->created_at = time();
                $timer->updated_at = time();
                $timer->fill($params);
                $timer->save();

                return response()->redirectTo('timers');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Timers $timer
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show($sub_domain, Timers $timer, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($timer) {
            if ($request->has('flag')) {
                $flag = $request->input('flag');
                switch ($flag) {
                    case 'preview':
                        return response()->view('users.timerPreview', compact('timer'));
                        break;
                }
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('timers');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param String $sub_domain
     * @param \App\Models\Timers $timer
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($sub_domain, Timers $timer)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        return response()->view('users.timerEdit', compact('timer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Timers $timer
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($sub_domain, Timers $timer, Request $request)
    {
        if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
            abort(401, 'Session is expired.');
        }
        if ($timer) {
            if ($request->has('flag')) {
                if ($request->input('flag') == 'edit') {
                    Validator::make($request->all(), [
                        'timer_name' => [
                            'required',
                            'min:4',
                            Rule::unique('mysql_tenant.timer')->ignore($timer->id)
                        ]
                    ])->validate();

                    $timer->fill($request->all());
                    $timer->save();

                    return response()->redirectTo('timers');
                } else if ($request->input('flag') == 'clone') {
                    $duplicate = $timer->replicate();
                    $duplicate->timer_name = $timer->timer_name . ' Clone';
                    $duplicate->created_at = time();
                    $duplicate->updated_at = time();
                    $duplicate->save();
                } else if ($request->input('flag') == 'reset') {
                    $timer->display_count = 0;
                    $timer->save();
                }
                return response('success');
            }
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response()->redirectTo('timers');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param String $sub_domain
     * @param \App\Models\Timers $timer
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_domain, Timers $timer, Request $request)
    {
        if ($timer) {
            if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
                abort(401, 'Session is expired.');
            }
            $timer->delete();
            $request->session()->flash('success', 'Link successfully deleted.');
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response('failure');
        }

        return response('success');
    }
}
