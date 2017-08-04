<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->bigInteger('link_group_id');
			$table->string('link_name', 250);
			$table->bigInteger('tracking_domain');
			$table->string('tracking_link', 250)->index('Ind_tracking_link');
			$table->text('primary_url');
			$table->enum('cloak_link', array('Yes', 'No'))->default('No');
			$table->string('cloak_page_title', 250);
			$table->text('cloak_page_description');
			$table->text('cloak_page_image_url');
			$table->enum('front_end', array('Yes', 'No'))->default('No');
			$table->enum('conversions_to_this_link', array('Yes', 'No'))->default('Yes');
			$table->bigInteger('popup_id');
			$table->bigInteger('magickbar_id');
			$table->string('password', 250);
			$table->integer('max_clicks');
			$table->bigInteger('smartswap_id');
			$table->enum('smartswap_type', array('0', '1'))->default('0')->comment('0 - Links, 1- Rotators');
			$table->decimal('traffic_cost', 15, 2);
			$table->enum('traffic_cost_for', array('None', 'CPC', 'CPA', 'Daily', 'Monthly'))->default('None');
			$table->enum('geo_targeting', array('All', 'Specified'))->default('All');
			$table->string('geo_targeting_include_countries', 250);
			$table->string('geo_targeting_exclude_countries', 250);
			$table->bigInteger('timer_id');
			$table->text('backup_url');
			$table->text('mobile_url');
			$table->text('repeat_url');
			$table->enum('tracking_link_visited', array('Yes', 'No'))->default('No');
			$table->text('pixel_code');
			$table->enum('abuser', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('anon', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('bot', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('spider', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('server', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('user', array('Filter', 'Block', 'Nothing'))->default('Filter');
			$table->enum('detect_new_bots', array('Yes', 'No'))->default('No');
			$table->text('notes');
			$table->enum('status', array('Active', 'Inactive', 'Deleted'))->default('Active');
			$table->bigInteger('total_clicks')->default('0');
			$table->bigInteger('unique_clicks')->default('0');
			$table->bigInteger('unique_click_per_day')->default('0');
			$table->bigInteger('actions')->default('0');
			$table->bigInteger('sales')->default('0');
			$table->bigInteger('events')->default('0');
			$table->timestamp('date_added');
			$table->enum('link_type', array('all-links', 'archived-link'))->default('all-links');
			$table->string('unix_date_added', 100);
		});
		DB::statement('CREATE INDEX Ind_primary_url ON links (primary_url(255));');
		DB::statement('CREATE INDEX Ind_user_id ON links (`user_id`);');
		DB::statement('CREATE INDEX Ind_link_group_id ON links (`link_group_id`);');
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('links');
	}
}