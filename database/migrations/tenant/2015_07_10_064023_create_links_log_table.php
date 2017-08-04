<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksLogTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links_log', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_id')->index('Ind_link_id');
			$table->string('cookie_id', 250);
			$table->bigInteger('device_id');
			$table->bigInteger('agent_id');
			$table->string('client_ip', 250);
			$table->bigInteger('referer_id');
			$table->bigInteger('geoip_id');
			$table->text('url');
			$table->enum('unique_click', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes');
			$table->enum('unique_click_per_day', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes');
			$table->enum('type', array('0', '1', '2', '3'))->default('0')->comment('0 - Link, 1- Action, 2 - Sales 3 - Event');
			$table->decimal('amount', 15, 2);
			$table->bigInteger('split_url_id')->default('0');
			$table->enum('link_reset', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes');
			$table->enum('filtered_click', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes');
			$table->string('created_at', 100);
			$table->string('updated_at', 100);
			$table->index(['cookie_id', 'link_id'], 'Ind_cookie_linkid');
			$table->index(['updated_at'], 'Ind_updated_at');
			$table->index(['created_at'], 'Ind_created_at');
			$table->index(['link_reset'], 'Ind_link_reset');
			$table->index(['unique_click_per_day'], 'Ind_unique_click_per_day');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('links_log');
	}
	
}
