<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckNoHubFileCron extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Payment:CheckNoHubFileCron';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$file_path = '/home/clickprf/html/app/webroot/nohup.out';
		if ( file_exists($file_path) ) {
			if ( file_get_contents($file_path) != '' ) {
				file_put_contents($file_path, '');
				shell_exec('nohup /usr/bin/php /home/clickprf/html/app/webroot/artisan queue:listen');
			}
		}
	}
}
