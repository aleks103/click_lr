<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

session([ 'tenant_connect' => 'mysql' ]);
session([ 'sub_domain' => null ]);

Auth::routes();

Route::get('demo', function () {
	return view('/landing/demo');
});

Route::get('pricing', function () {
	return view('/landing/pricing');
});

Route::get('annual', function () {
	return view('/landing/annual');
});

Route::get('privacy', function () {
	return view('/landing/privacy');
});

Route::get('tos', function () {
	return view('/landing/tos');
});

Route::group([ 'prefix' => 'pixel' ], function () {
	Route::get('/event/{user_id}', 'ConversionTrackingController@convertEvent');
	Route::get('/sales/{user_id}', 'ConversionTrackingController@convertSales');
	Route::get('/action/{user_id}', 'ConversionTrackingController@convertAction');
});

Route::group([ 'prefix' => 'post' ], function () {
	Route::get('/sales/{user_id}', 'ConversionTrackingController@convertSales');
	Route::get('/action/{user_id}', 'ConversionTrackingController@convertAction');
});

Route::group([ 'domain' => '{sub_domain}.clkpfct.com', 'middleware' => 'tenant' ], function () {
	Route::group([ 'prefix' => 'go' ], function () {
		Route::get('/{link_name}', 'TrackingLinkController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingLinkController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingLinkController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingLinkController@getUpdateClicks');
	});
	
	Route::get('/track/{link_name}', 'TrackingLinkController@index');
	
	Route::group([ 'prefix' => 'tr' ], function () {
		Route::get('/{rotator_name}', 'TrackingRotatorController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingRotatorController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingRotatorController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingRotatorController@getUpdateClicks');
	});
});

Route::group([ 'domain' => '{sub_domain}.clckperfect.com', 'middleware' => 'tenant' ], function () {
	Route::group([ 'prefix' => 'go' ], function () {
		Route::get('/{link_name}', 'TrackingLinkController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingLinkController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingLinkController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingLinkController@getUpdateClicks');
	});
	
	Route::get('/track/{link_name}', 'TrackingLinkController@index');
	
	Route::group([ 'prefix' => 'tr' ], function () {
		Route::get('/{rotator_name}', 'TrackingRotatorController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingRotatorController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingRotatorController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingRotatorController@getUpdateClicks');
	});
});

Route::post('ipn', 'IpnController@IpnListener');

Route::group([ 'domain' => '{sub_domain}.' . config('site.site_domain'), 'middleware' => 'tenant' ], function () {
	Route::get('/', 'MyAccount\DashboardController@index')->name('customer.dashboard');
	
	Route::group([ 'prefix' => 'users' ], function () {
		Route::get('/loginas/{user_key}/{fp_id}', 'MyAccount\DashboardController@adminLoginAsUser');
		
		Route::get('/logintoadmin', 'MyAccount\DashboardController@userLoginAsAdmin');
	});
	
	Route::group([ 'prefix' => 'dashboard' ], function () {
		Route::get('/dashboard-links-graph', 'MyAccount\DashboardController@dashboardLinksGraph');
		
		Route::get('/dashboard-rotators-graph', 'MyAccount\DashboardController@dashboardRotatorsGraph');
	});
	
	Route::resource('links', 'MyAccount\LinksController', [ 'names' => [ 'index' => 'links' ] ]);
	
	Route::get('/users/training', 'MyAccount\TrainingController@index')->name('customer.training');
	
	Route::resource('rotators', 'MyAccount\RotatorsController', [ 'names' => [ 'index' => 'rotators' ] ]);
	
	Route::resource('popbars', 'MyAccount\PopbarsController', [ 'names' => [ 'index' => 'popbars' ] ]);
	
	Route::resource('popups', 'MyAccount\PopupsController', [ 'names' => [ 'index' => 'popups' ] ]);
	
	Route::resource('timers', 'MyAccount\TimersController', [ 'names' => [ 'index' => 'timers' ] ]);
	
	Route::resource('linkgroups', 'MyAccount\LinkgroupsController', [ 'names' => [ 'index' => 'linkgroups' ] ]);

	Route::resource('profiles', 'MyAccount\ProfilesController', [ 'names' => [ 'index' => 'profiles' ] ]);
	
	Route::resource('billingupgrade', 'MyAccount\BillingUpgradeController', [ 'names' => [ 'index' => 'billingupgrade' ] ]);

	Route::resource('domains', 'MyAccount\DomainsController', [ 'names' => [ 'index' => 'domains' ] ]);
	
	Route::resource('ipmanager', 'MyAccount\IpmanagerController', [ 'names' => [ 'index' => 'ipmanager' ] ]);
	
	Route::resource('customdomain', 'MyAccount\CustomDomainController', [ 'names' => [ 'index' => 'customdomain' ] ]);
	
	Route::get('/users/conversionbytime', 'MyAccount\ConversionByTimeController@index')->name('conversionbytime');
	Route::post('/users/getconversionbytime', 'MyAccount\ConversionByTimeController@getconversionbytime');
	
	Route::group([ 'prefix' => 'go' ], function () {
		Route::get('/{link_name}', 'TrackingLinkController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingLinkController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingLinkController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingLinkController@getUpdateClicks');
	});
	
	Route::get('/track/{link_name}', 'TrackingLinkController@index');
	Route::get('/preview-timer/timer-code/timer/{timer_id}', 'TimerGetCodeController@index');
	Route::get('/cron/timer_preview/timer/{timer_id}', 'TimerGetCodeController@index');
	
	Route::group([ 'prefix' => 'tr' ], function () {
		Route::get('/{rotator_name}', 'TrackingRotatorController@index');
		Route::get('/preview-html/{magickbar_id}', 'TrackingRotatorController@getPreviewHtml');
		Route::get('/load-popup-page/{popup_id}', 'TrackingRotatorController@getLoadPopupPage');
		Route::get('/link/update-clicks', 'TrackingRotatorController@getUpdateClicks');
	});
});

Route::group([ 'domain' => config('site.site_domain') ], function () {
	Route::get('/', 'IndexController@index');
});

Route::group([ 'prefix' => 'admin', 'middleware' => 'admin' ], function () {
	Route::resource('members', 'Admin\MembersController', [ 'names' => [ 'index' => 'members' ] ]);
	
	Route::resource('paypals', 'Admin\PaypalsController', [ 'names' => [ 'index' => 'paypals' ], 'except' => [ 'create', 'store' ] ]);
	
	Route::post('/plans/postProductId', 'Admin\PlansController@postProductId')->name('plans.postProductId');
	
	Route::delete('/plans/destroyProductId', 'Admin\PlansController@destroyProductId')->name('plans.destroyProductId');
	
	Route::resource('plans', 'Admin\PlansController', [ 'names' => [ 'index' => 'plans' ], 'except' => [ 'show' ] ]);
	
	Route::resource('groups', 'Admin\GroupsController', [ 'names' => [ 'index' => 'groups' ], 'except' => [ 'show' ] ]);
	
	Route::resource('configs', 'Admin\ConfigsController', [ 'names' => [ 'index' => 'configs' ], 'except' => [ 'create', 'show', 'edit', 'destroy' ] ]);
	
	Route::resource('paykickstarts', 'Admin\PaykickstartsController', [ 'names' => [ 'index' => 'paykickstarts' ], 'except' => [ 'create', 'store', 'edit' ] ]);
	
	Route::get('/billing-history', 'Admin\BillingController@billingHistoryList')->name('billing-history');
});
