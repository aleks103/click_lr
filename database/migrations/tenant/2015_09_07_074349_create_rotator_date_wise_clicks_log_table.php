<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRotatorDateWiseClicksLogTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rotator_date_wise_clicks_log', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('rotator_id');
			$table->bigInteger('rotator_url_id');
			$table->string('date', 100);
			$table->bigInteger('total_clicks')->default('0');
			$table->bigInteger('unique_clicks')->default('0');
			$table->bigInteger('unique_click_per_day')->default('0');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rotator_date_wise_clicks_log');
	}
	
}
