<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkGroupsTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('link_groups', function ($table) {
			$table->bigIncrements('id');
			$table->string('link_group', 250);
			$table->enum('status', array('0', '1', '2'))->default('0')->comment('0-Active, 1-Inactive, 2-Deleted');
			$table->bigInteger('parent_id');
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
		Schema::drop('link_groups');
	}
	
}
