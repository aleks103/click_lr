<?php

namespace App\Providers;

use App\Http\Repositories\MapRepository;
use App\Http\Repositories\ProfileRepository;
use App\Users\CustomValidator;
use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
		Validator::resolver(function ($translator, $data, $rules, $messages) {
			return new CustomValidator($translator, $data, $rules, $messages);
		});
	}
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('MyConfig', function ($app) {
			return new MapRepository();
		});
		$this->app->singleton('ProfileConfig', function ($app) {
			return new ProfileRepository();
		});
		require_once __DIR__ . '/../Http/Helpers/Helper.php';
	}
}
