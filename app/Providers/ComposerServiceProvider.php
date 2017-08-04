<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		view()->composer('*', 'App\Http\ViewComposers\SettingsComposer');
		view()->composer('layouts/welcomeIndex', 'App\Http\ViewComposers\SvgLoadComposer');
		view()->composer([
			'admin/members', 'admin/editmember', 'admin/addmember', 'admin/createPayPalUser', 'admin/billingHistory'
		], 'App\Http\ViewComposers\MembersComposer');
		
		view()->composer([
			'users/linksAdd', 'users/linksEdit'
		], 'App\Http\ViewComposers\LinksComposer');
		
		view()->composer([
			'users/rotatorsAddUrl', 'users/rotatorsAdd', 'users/rotatorsEdit'
		], 'App\Http\ViewComposers\RotatorsComposer');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
