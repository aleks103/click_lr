<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagickBarTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('magick_bar', function ($table) {
			$table->bigIncrements('id');
			$table->string('bar_name', 100);
			$table->enum('position', array('1', '2'))->default('1')->comment('1-Onload, 2-Later')->nullable();
			$table->integer('height');
			$table->enum('timing', array('', '1', '2'))->default('')->comment('1-Onload, 2-Later')->nullable();
			$table->string('delay_timing', 250);
			$table->enum('shadow', array('1', '2'))->default('1')->comment('1-Yes, 2-No')->nullable();
			$table->enum('closable', array('1', '2'))->default('1')->comment('1-Yes, 2-No')->nullable();
			$table->enum('spacer', array('1', '2'))->default('1')->comment('1-Yes, 2-No')->nullable();
			$table->string('button_color', 100);
			$table->enum('transparent_background', array('1', '2'))->default('2')->comment('1-Yes, 2-No')->nullable();
			$table->text('url');
			$table->text('content');
			$table->enum('status', array('0', '1', '2'))->default('0')->comment('0-Active, 1-Inactive, 2-Deleted');
			$table->bigInteger('display_count')->default('0');
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
		Schema::drop('magick_bar');
	}
	
}
