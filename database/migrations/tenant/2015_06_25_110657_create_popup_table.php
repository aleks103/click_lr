<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('popup', function ($table) {
			$table->increments('id');
			$table->string('popupname', 225);
			$table->string('width', 225);
			$table->string('height', 225);
			$table->enum('timing', array('', 'Onload', 'After'))->default('')->nullable();
			$table->string('delay_timing', 225);
			$table->enum('exit_method', array('', 'Intelligent', 'standered', 'Redir'))->default('')->nullable();
			$table->string('alert_msg', 225);
			$table->enum('closable', array('1', '0'))->default('1');
			$table->string('cookie_duration', 225);
			$table->text('url');
			$table->text('popup_contents');
			$table->enum('status', array('Active', 'Inactive', 'Deleted'))->default('Active')->index('Ind_status');
			$table->bigInteger('display_count')->default('0');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			$table->string('unix_created_at', 100);
			$table->string('unix_updated_at', 100);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('popup');
	}
	
}
