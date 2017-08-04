<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\CustomDomain;

class CustomDomainRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(CustomDomain::class);
	}
	
	/**
	 * @param $domain_id
	 * @param $domain_for
	 *
	 * @return mixed
	 */
	public function getTrackingDomain($domain_id, $domain_for)
	{
		return $this->model()->whereRaw('id = ? AND domain_for = ? AND status = ?', [ $domain_id, $domain_for, '0' ])->pluck('domain_name');
	}
}