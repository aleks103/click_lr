<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LinkNotification extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links_notification', function ($table) {
			$table->increments('id');
			$table->bigInteger('link_id')->unsigned()->nullable()->index();
			$table->enum('notification_type', array('1', '2', '3', '4', '5'))->default('1')->comment('1 - Action Conversion Rate, 2 - Engagement Conversion Rate, 3 - Sales Conversion Rate, 4 - Earnings Per Click, 5 - Average Customer Value');
			$table->enum('relational', array('1', '2'))->default('1')->comment('1 - Greater Than, 2 - Less Than');
			$table->bigInteger('value')->unsigned()->nullable()->index();
			$table->bigInteger('clicks')->unsigned()->nullable()->index();
			$table->enum('is_deleted', array('0', '1'))->default('0')->comment('0 - No, 1 - Yes')->index();
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
		Schema::drop('links_notification');
	}
	
}
