<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempMagickbarTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('temp_magickbar_cookies', function ($table) {
			$table->bigIncrements('id');
			$table->text('html_contents');
			$table->bigInteger('cookie_id');
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
		//
		Schema::drop('temp_magickbar_cookies');
	}
	
}
