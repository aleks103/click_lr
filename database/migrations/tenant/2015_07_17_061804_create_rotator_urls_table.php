<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotatorUrlsTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rotator_urls', function ($table) {
			$table->bigIncrements('id');
			$table->string('name', 100);
			$table->text('url');
			$table->integer('position');
			$table->integer('max_clicks');
			$table->integer('max_daily_clicks');
			$table->integer('bonus');
			$table->integer('min_t1');
			$table->integer('max_t1');
			$table->integer('min_mobile');
			$table->integer('max_mobile');
			$table->string('start_date', 100);
			$table->string('end_date', 100);
			$table->text('notes');
			$table->bigInteger('rotator_id');
			$table->bigInteger('user_id');
			$table->enum('notify_max_clicks_reached', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('status', array('0', '1', '2', '3'))->default('0')->comment('0 - Active, 1 - Paused, 2 - Archived, 3 - Deleted');
			$table->bigInteger('total_clicks');
			$table->bigInteger('unique_clicks');
			$table->enum('unique_click_per_day', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes');
			$table->enum('geo_targeting', array('0', '1'))->default('0')->comment('0-All countries, 1-Specified countries');
			$table->string('geo_targeting_include_countries', 250);
			$table->string('geo_targeting_exclude_countries', 250);
			$table->bigInteger('popup_id');
			$table->bigInteger('timer_id');
			$table->bigInteger('magickbar_id');
			$table->index(['rotator_id', 'position'], 'Ind_rotatorurl');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rotator_urls');
	}
	
}
