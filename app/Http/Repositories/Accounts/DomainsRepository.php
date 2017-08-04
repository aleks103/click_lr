<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\Domain;

class DomainsRepository extends Repository
{
    public function model()
    {
        // TODO: Implement model() method.
        return app(Domain::class);
    }

    public function getAll()
    {
        $pop_bars = $this->model()->where('status', '<>', '2')->get();

        return $pop_bars;
    }
    public function getDomains($domain_for)
    {
        $qry = $this->model()->where('status', '<>', '2')->where('domain_for', $domain_for);

        return $qry;
    }
}