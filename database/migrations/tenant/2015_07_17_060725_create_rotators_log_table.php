<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotatorsLogTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rotators_log', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('rotator_id');
			$table->bigInteger('rotator_url_id');
			$table->string('cookie_id', 250);
			$table->bigInteger('device_id');
			$table->bigInteger('agent_id');
			$table->string('client_ip', 250);
			$table->bigInteger('referer_id');
			$table->bigInteger('geoip_id');
			$table->text('url');
			$table->enum('unique_click', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('unique_click_per_day', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('on_finish_url', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->string('created_at', 100);
			$table->string('updated_at', 100);
			$table->enum('rotator_reset', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('filtered_click', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->index(['cookie_id', 'rotator_id'], 'Ind_cookie_linkid');
			$table->index(['rotator_id', 'rotator_url_id'], 'Ind_rotator_id');
			$table->index(['rotator_id'], 'Indrotator_id');
			$table->index(['created_at'], 'Ind_created_at');
			$table->index(['updated_at'], 'Ind_updated_at');
			$table->index(['rotator_reset'], 'Ind_rotator_reset');
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
		Schema::drop('rotators_log');
	}
	
}
