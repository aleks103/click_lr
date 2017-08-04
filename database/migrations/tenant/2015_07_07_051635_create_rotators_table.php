<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotatorsTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rotators', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->bigInteger('rotator_group_id');
			$table->string('rotator_name', 250);
			$table->bigInteger('tracking_domain');
			$table->string('rotator_link', 250)->index('Ind_rotator_link');
			$table->enum('rotator_mode', array('0', '1', '2'))->default('0')->comment('0-Fulfillment, 1-Spillover, 2-Random');
			$table->enum('on_finish', array('0', '1', '2'))->default('0')->comment('0-Backup URL, 1-Last URL, 2-Top of rotator');
			$table->enum('cloak_rotator', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->string('cloak_page_title', 250);
			$table->text('cloak_page_description');
			$table->text('cloak_page_image_url');
			$table->text('backup_url');
			$table->text('mobile_url');
			$table->bigInteger('popup_id');
			$table->bigInteger('magickbar_id');
			$table->integer('cookie_duration');
			$table->integer('randomize');
			$table->enum('ignore_last_url', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('geo_targeting', array('0', '1'))->default('0')->comment('0-All countries, 1-Specified countries');
			$table->string('geo_targeting_include_countries', 250);
			$table->string('geo_targeting_exclude_countries', 250);
			$table->bigInteger('timer_id');
			$table->text('pixel_code');
			$table->enum('abuser', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('anon', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('bot', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('spider', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('server', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('user', array('0', '1', '2'))->default('0')->comment('0-Filter, 1-Block, 2-Nothing');
			$table->enum('detect_new_bots', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->text('notes');
			$table->enum('status', array('0', '1', '2'))->default('0')->comment('0-Active, 1-Inactive, 2-Deleted');
			$table->bigInteger('total_clicks');
			$table->bigInteger('unique_clicks');
			$table->bigInteger('unique_click_per_day')->default('0');
			$table->bigInteger('backup_url_clicks');
			$table->bigInteger('mobile_url_clicks');
			$table->string('created_at', 100);
			$table->string('updated_at', 100);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rotators');
	}
	
}
