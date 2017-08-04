<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomDomainTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('custom_domain', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->string('domain_name', 250);
			$table->enum('domain_for', array('1', '2'))->default('1')->comment('1-Links, 2-Rotator');
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
		Schema::drop('custom_domain');
	}
	
}
