<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\Blockipaddress;

class IpManagerRepository extends Repository
{
    public function model()
    {
        // TODO: Implement model() method.
        return app(Blockipaddress::class);
    }

    public function getAll()
    {
        $pop_bars = $this->model()->where('status', '=', '0')->get();

        return $pop_bars;
    }
	
}