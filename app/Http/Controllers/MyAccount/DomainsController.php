<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Repositories\Accounts\DomainsRepository;
use App\models\domain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DomainsController extends Controller
{
    protected $domainRepo;

    public function __construct(DomainsRepository $domainsRepository)
    {
        $this->middleware('auth');

        $this->domainRepo = $domainsRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = $this->domainRepo->getDomains(1);
        $linkDomains = $query->paginate(20);

        $query = $this->domainRepo->getDomains(2);
        $rotatorDomains = $query->paginate(20);
        return response()->view('users.domains', compact('linkDomains', 'rotatorDomains'));
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

                $newrow = new Domain();
                $newrow->domain_name = $params['domain_name'];
                $newrow->domain_for = $params['domain_for'];


                $newrow->created_at = time();
                $newrow->updated_at = time();
                $newrow->save();

                return response()->redirectTo('domains');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(domain $domain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(domain $domain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, domain $domain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_domain, Domain $domain, Request $request)
    {
        if ($domain) {
            if ($sub_domain == '' || is_null($sub_domain) || !$sub_domain) {
                abort(401, 'Session is expired.');
            }
            $domain->delete();
            $request->session()->flash('success', 'Link successfully deleted.');
        } else {
            $request->session()->flash('error', 'Link ID does not exits');

            return response('failure');
        }

        return response('success');
    }
}
