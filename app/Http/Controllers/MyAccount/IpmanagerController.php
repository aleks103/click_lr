<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Repositories\Accounts\IpManagerRepository;
use App\models\blockipaddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IpmanagerController extends Controller
{
    protected $ipRepo;

    public function __construct(IpManagerRepository $ipManagerRepository)
    {
        $this->middleware('auth');

        $this->ipRepo = $ipManagerRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $ipRows = $this->ipRepo->getAll();
        $myIp = $request->server('REMOTE_ADDR');

        return response()->view('users.ipmanager', compact('ipRows', 'myIp'));
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
            if ($request->input('flag') == 'add') {
                $params = $request->all();
                foreach ($params as $key => $val) {
                    if (is_null($val))
                        $params[$key] = '';
                }

                $newrow = new Blockipaddress();
                $newrow->from_ip_address = $params['from_ip_address'];
                $newrow->to_ip_address = $params['to_ip_address'];
                $newrow->note = $params['note'];


                $newrow->created_at = time();
                $newrow->updated_at = time();
                $newrow->save();

                return response()->redirectTo('ipmanager');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Blockipaddress  $ipmanager
     * @return \Illuminate\Http\Response
     */
    public function show(Blockipaddress $ipmanager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Blockipaddress  $ipmanager
     * @return \Illuminate\Http\Response
     */
    public function edit(Blockipaddress $ipmanager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Blockipaddress  $ipmanager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blockipaddress $ipmanager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Blockipaddress  $ipmanager
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_domain, Blockipaddress $ipmanager, Request $request)
    {
        if ($ipmanager) {
            if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
                abort(401, 'Session is expired.');
            }
            $ipmanager->delete();
            $request->session()->flash('success', 'Link successfully deleted.');
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response('failure');
        }

        return response('success');
    }
}
