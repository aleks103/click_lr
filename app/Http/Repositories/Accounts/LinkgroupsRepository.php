<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 6/7/2017
 * Time: 5:09 PM
 */

namespace App\Http\Repositories\Accounts;

use App\Http\Repositories\Repository;
use App\Models\LinkGroup;
use App\Models\RotatorsGroup;

class LinkgroupsRepository extends Repository
{
	public function model()
	{
		// TODO: Implement model() method.
		return app(LinkGroup::class);
	}
    public function rotatorgroup_model()
    {
        // TODO: Implement model() method.
        return app(RotatorsGroup::class);
    }
	
	public function getAll()
	{
		$pop_bars = $this->model()->where('status', '<>', '2')->get();
		
		return $pop_bars;
	}
    public function getLinkGroups()
    {
        $qry = $this->model()->where('status', '<>', '2')->orderBy('created_at', 'DESC');

        return $qry;
    }
    public function getRotatorGroups()
    {
        $qry = $this->rotatorgroup_model()->where('status', '<>', '2')->orderBy('created_at', 'DESC');

        return $qry;
    }
    public function deleteGroups($id, $flag){
	    if($flag == 1){
            $this->model()->where('id', $id)->delete();

            $this->model()->where('parent_id', $id)->delete();
        } else {
            $this->rotatorgroup_model()->where('id', $id)->delete();

            $this->rotatorgroup_model()->where('parent_id', $id)->delete();
        }
    }
}