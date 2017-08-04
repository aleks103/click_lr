<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('email', 100)->unique();
			$table->string('domain', 100)->index();
			$table->string('password', 100);
			$table->string('bba_token', 40);
			$table->text('permissions');
			$table->tinyInteger('activated');
			$table->string('activation_code', 100)->index();
			$table->timestamp('activated_at');
			$table->timestamp('last_login');
			$table->string('persist_code');
			$table->string('first_name', 20);
			$table->string('last_name', 20);
			$table->string('img_ext', 50);
			$table->string('company', 50);
			$table->text('address');
			$table->string('city', 50);
			$table->string('postal_code', 20);
			$table->string('country', 50);
			$table->string('state_code', 20);
			$table->string('phone', 20);
			$table->string('user_code', 20);
			$table->string('signup_ip', 40);
			$table->enum('openid_used', ['Yes', 'No']);
			$table->tinyInteger('user_banned');
			$table->dateTime('user_banned_date');
			$table->string('tracking_domain', 50);
			$table->bigInteger('vault_id');
			$table->string('vault_key', 100);
			$table->string('paykey', 100);
			$table->string('user_handle', 100);
			$table->string('api_key', 50);
			$table->enum('fb_connected', ['Yes', 'No']);
			$table->integer('mailer_id');
			$table->integer('auto_mailer_id');
			$table->bigInteger('bulk_smtp');
			$table->bigInteger('autoresponder_smtp');
			$table->bigInteger('transaction_smtp');
			$table->enum('mailer_settings', ['Enabled', 'Disabled', 'Need to Approve']);
			$table->dateTime('online_duration');
			$table->enum('reputation', ['Trusted', 'Untrusted']);
			$table->bigInteger('current_plan');
			$table->string('coupon_code', 20);
			$table->bigInteger('current_addon');
			$table->enum('sift_logic', ['Enabled', 'Disabled']);
			$table->enum('import_visible', ['Yes', 'No']);
			$table->enum('import', ['Enabled', 'Disabled']);
			$table->enum('email_verification', ['Enabled', 'Disabled']);
			$table->enum('transaction_api', ['Enabled', 'Disabled']);
			$table->timestamp('unflagged_date');
			$table->integer('click_counts');
			$table->enum('account_status', ['yes', 'no']);
			$table->rememberToken();
			$table->timestamps();
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
