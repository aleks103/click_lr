<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		\App\Console\Commands\GraceEmails::class,
		\App\Console\Commands\CheckNoHubFileCron::class,
		\App\Console\Commands\LinkAlertMsg::class,
		\App\Console\Commands\ClearTrackerTable::class,
		\App\Console\Commands\FirstFreeDaysCancelled::class,
		\App\Console\Commands\LinkNotification::class,
		\App\Console\Commands\PlanExpired::class,
		\App\Console\Commands\PlanCreated::class,
	];
	
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// $schedule->command('inspire')
		//          ->hourly();
		$schedule->command('Payment:GraceEmails')
			->dailyAt('00:01');
		$schedule->command('Payment:FirstFreeDaysCancelled')
			->dailyAt('00:01');
		$schedule->command('Payment:CheckNoHubFileCron');
		$schedule->command('EmailSend:linkAlert');
		$schedule->command('EmailSend:linkNotification');
		$schedule->command('clear:tracker');
		$schedule->command('Payment:PlanExpired');
//		$schedule->command('Payment:CreateUser')->dailyAt('00:01');
	}
	
	/**
	 * Register the Closure based commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		require base_path('routes/console.php');
	}
}
