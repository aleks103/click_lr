<?php

namespace App\Providers;

use App\Users\Cache\Cacheable;
use App\Users\Cache\NoCache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app->bind('MyCache', function ($app) {
			if (config('cache.enable') == 'true') {
				return new Cacheable();
			} else {
				return new NoCache();
			}
		});
	}
}
