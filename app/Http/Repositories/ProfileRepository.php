<?php
/**
 * Created by PhpStorm.
 * User: TalentDeveloper
 * Date: 5/8/2017
 * Time: 5:31 AM
 */

namespace App\Http\Repositories;

use App\UserProfile;

class ProfileRepository extends Repository
{
	public function model()
	{
		return app(UserProfile::class);
	}
	
	public function getLRStatusByUserId($user_id)
	{
		$return_text = [ 'link_url' => '2', 'rotator_url' => '2' ];
		
		$select_user_profile = $this->model()->where('user_id', $user_id)->select('mobile_tracking_links', 'monitor_rotator_url')->first();
		
		if ( isset($select_user_profile) )
			$return_text = [ 'link_url' => $select_user_profile->mobile_tracking_links, 'rotator_url' => $select_user_profile->monitor_rotator_url ];
		
		return $return_text;
	}
	
	public function fetchUserProfileByUserId($user_id)
	{
		return $this->model()->where('user_id', $user_id)->first();
	}
	
	public function chkValidUserId($user_id)
	{
		return $this->model()->where('user_id', $user_id)->count();
	}
	
	public function addUserProfile($user_id)
	{
		return $this->model()->insertGetId([ 'user_id' => $user_id ]);
	}
	
	public function updateUserProfile($param, $user_id)
	{
		$this->model()->where('user_id', $user_id)->update($param);
	}
}