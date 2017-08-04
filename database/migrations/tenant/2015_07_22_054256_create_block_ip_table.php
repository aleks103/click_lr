<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockIpTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('block_ip_address', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id');
			$table->bigInteger('from_ip_address');
			$table->bigInteger('to_ip_address')->nullable();
			$table->string('note', 250);
			$table->enum('delete_existing_clicks', array('0', '1'))->default('0')->comment('0-Not delete, 1-delete');
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
		Schema::drop('block_ip_address');
	}
	
}
