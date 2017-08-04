<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimerTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('timer', function ($table) {
			$table->bigIncrements('id');
			$table->string('timer_name', 100);
			$table->enum('timer_type', array('1', '2'))->default('1')->comment('1-Evergreen, 2-Date-based');
			$table->enum('position', array('1', '2'))->default('1')->comment('1-Top, 2-Bottom');
			$table->enum('transparent', array('1', '2'))->default('1')->comment('1-Transparent, 2-Custom');
			$table->bigInteger('timer_type_days');
			$table->bigInteger('timer_type_hours');
			$table->bigInteger('timer_type_minutes');
			$table->string('timer_type_expires_at', 100);
			$table->enum('timer_style', array('1', '2', '3', '4'))->default('1')->comment('1-Flip, 2-Slide, 3-Metal, 4-Crystal');
			$table->enum('color', array('1', '2'))->default('1')->comment('1-Black, 2-White');
			$table->string('timer_width', 250);
			$table->string('background_color', 10);
			$table->enum('show_day', array('', '1'))->default('')->comment('1-yes');
			$table->enum('show_hour', array('', '1'))->default('')->comment('1-yes');
			$table->enum('show_minute', array('', '1'))->default('')->comment('1-yes');
			$table->enum('show_seconds', array('', '1'))->default('')->comment('1-yes');
			$table->enum('day_width', array('1', '2'))->default('1')->comment('1-two, 2-three');
			$table->enum('on_expires', array('1', '2', '3'))->default('2')->comment('1-Do Nothing, 2-Hide Timer, 3-Redirect to');
			$table->text('redirect_url');
			$table->enum('status', array('0', '1', '2'))->default('0')->comment('0-Active, 1-Inactive, 2-Deleted');
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
		Schema::drop('timer');
	}
	
}
