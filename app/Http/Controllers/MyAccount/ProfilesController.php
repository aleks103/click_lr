<?php

namespace App\Http\Controllers\MyAccount;

use App\Http\Controllers\Controller;
use App\Http\Repositories\LinkRotatorsAlertRepository;
use App\Http\Repositories\ProfileRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
	protected $profileRepo;
	protected $lrRepo;
	
	public function __construct(ProfileRepository $profileRepository, LinkRotatorsAlertRepository $linkRotatorsAlertRepository)
	{
		$this->middleware('auth');
		$this->profileRepo = $profileRepository;
		$this->lrRepo      = $linkRotatorsAlertRepository;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$chkProfile = $this->profileRepo->chkValidUserId(auth()->id());
		
		if ( !$chkProfile ) {
			$this->profileRepo->addUserProfile(auth()->id());
		}
		
		$lr = $this->profileRepo->getLRStatusByUserId(auth()->id());
		
		return response()->view('users.profile', compact('lr'));
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
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\User $profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $profile)
	{
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\User $profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($sub_domain, User $profile)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		
		return response()->view('users.profilePass', compact('profile'));
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\User                $profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update($sub_domain, Request $request, User $profile)
	{
		if ( $sub_domain == '' || is_null($sub_domain) || !$sub_domain ) {
			abort(401, 'Session is expired.');
		}
		
		if ( $request->has('flag') ) {
			if ( $request->input('flag') == 'avatar' ) {
				$fileTypes   = [ 'jpg', 'jpeg', 'gif', 'png' ];
				$templateImg = $request->file('my_photo');
				$extension   = $templateImg->getClientOriginalExtension();
				
				if ( in_array($extension, $fileTypes) ) {
					$f_name = $profile->id . '.' . $extension;
					
					$destinationPath = getConfig('users_img_path');
					
					$path       = public_path($destinationPath . '/' . $f_name);
					$thumb_path = public_path($destinationPath . '/thumb/' . $f_name);
					
					if ( File::exists($path) )
						File::delete($path);
					if ( File::exists($thumb_path) )
						File::delete($thumb_path);
					
					$templateImg->move($destinationPath, $f_name);
					
					list($width, $height) = getimagesize($destinationPath . '/' . $f_name);
					$new_width  = 176;
					$new_height = floor($height * ($new_width / $width));
					
					if ( !file_exists(public_path($destinationPath . '/thumb')) ) {
						mkdir(public_path($destinationPath . '/thumb'));
						@chmod(public_path($destinationPath . '/thumb'), '777');
					}
					
					Image::make($destinationPath . '/' . $f_name)->resize($new_width, $new_height)->save($destinationPath . '/thumb/' . $f_name);
					
					$profile->img_ext = $extension;
					$profile->save();
					
					$request->session()->flash('success', 'File uploaded successfully.');
				}
			} else if ( $request->input('flag') == 'changePassword' ) {
				$this->validate($request, [
					'current_password' => 'required',
					'new_password'     => 'required|min:6',
					'confirm_password' => 'required|min:6|same:new_password'
				]);
				
				$cur_pass = md5($request->input('current_password') . $profile->bba_token);
				if ( $cur_pass != $profile->password ) {
					$request->session()->flash('error', 'Current Password is invalid.');
					return response()->view('users.profilePass', compact('profile'));
				} else {
					$profile->password = md5($request->input('new_password') . $profile->bba_token);
					$profile->save();
					
					$request->session()->flash('success', 'Successfully updated.');
				}
				echo '<script>window.parent.$(".close").click();window.parent.location = window.parent.location.href;window.parent.$.fancybox.close();</script>';
				exit;
			}
		} else {
			$this->validate($request, [
				'first_name' => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
				'last_name'  => 'required|LikeRestricted:' . config('auth.restrict_keywords_like') . '|MatchRestricted:' . config('auth.restrict_keywords_exact'),
			], [
				'first_name.required'         => 'The first name field is required.',
				'first_name.like_restricted'  => 'The first name cannot be included ' . config('auth.restrict_keywords_like'),
				'first_name.match_restricted' => 'The first name cannot be matched ' . config('auth.restrict_keywords_exact'),
				'last_name.like_restricted'   => 'The last name cannot be matched ' . config('auth.restrict_keywords_like'),
				'last_name.match_restricted'  => 'The last name cannot be matched ' . config('auth.restrict_keywords_exact'),
				'last_name.required'          => 'The last name field is required.',
			]);
			
			$profile->first_name = $request->input('first_name');
			$profile->last_name  = $request->input('last_name');
			$profile->save();
			
			$l = $request->input('mobile_tracking_links') == '2' ? '0' : '1';
			$this->lrRepo->update($profile->id, '', '0', $l);
			
			$r = $request->input('monitor_rotator_url') == '2' ? '0' : '1';
			$this->lrRepo->update($profile->id, '', '1', $r);
			
			
			$upd_data = [
				'mobile_tracking_links' => $request->input('mobile_tracking_links'),
				'monitor_rotator_url'   => $request->input('monitor_rotator_url'),
			];
			
			$this->profileRepo->updateUserProfile($upd_data, $profile->id);
			
			$request->session()->flash('success', 'Successfully updated.');
		}
		
		return response()->redirectTo('profiles');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User $profile
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $profile)
	{
		//
	}
}
