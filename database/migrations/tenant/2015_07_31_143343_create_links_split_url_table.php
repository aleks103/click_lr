<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksSplitUrlTable extends Migration
{
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links_split_url', function ($table) {
			$table->bigIncrements('id');
			$table->bigInteger('link_id')->index('Ind_linkid');
			$table->string('url_name', 150);
			$table->text('split_url');
			$table->integer('weight');
			$table->enum('is_deleted', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
			$table->enum('primary_url', array('0', '1'))->default('0')->comment('0-No, 1-Yes');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('links_split_url');
	}
	
}
