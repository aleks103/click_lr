<?php

/**
 * General Config Settings.
 * 04/30/2017
 */
return [
	'support_email'  => 'support@clickperfect.com',
	'support_url'    => 'http://support.clickperfect.com',
	'app_version'    => '2.0',
	'company_domain' => 'snaptactix.com',
	
	'calendar_array' => [ 'day' => 'Day(s)', 'week' => 'Week(s)', 'month' => 'Month(s)', 'year' => 'Year(s)' ],
	
	'user_plan_change_to' => 'After VAR_DURATION, your plan will be changed to "<strong>VAR_PLAN_NAME</strong>"',
	
	'plan_level' => [
		'1' => 'Unlimited Accounts', '2' => 'Click Perfect Silver', '3' => 'Click Perfect Gold', '4' => 'Click Perfect Platinum', '5' => 'Click Perfect Enterprise'
	],
	
	'bad_clicks_arr' => [ 'Filter' => 'Filter', 'Block' => 'Block', 'Nothing' => 'Nothing' ],
	
	'rotator_bad_clicks_arr' => [ '0' => 'Filter', '1' => 'Block', '2' => 'Nothing' ],
	
	'skip_domains' => [ 'https://afformations.com' ],
];