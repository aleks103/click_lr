<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateWiseClicksLogTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('date_wise_clicks_log', function ($table) {
			$table->bigIncrements('id');
			$table->string('date', 100);
			$table->bigInteger('total_clicks')->default('0');
			$table->bigInteger('unique_clicks')->default('0');
			$table->bigInteger('unique_click_per_day')->default('0');
			$table->bigInteger('type')->default('1')->comment('1=links, 2=rotators');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('date_wise_clicks_log');
	}
	
}
