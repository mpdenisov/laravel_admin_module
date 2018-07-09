<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email', 191)->unique();
			$table->string('password');
			$table->string('gender', 45)->nullable();
			$table->date('birth_date')->nullable();
			$table->boolean('active')->nullable()->default(1);
			$table->timestamps();
			$table->string('api_token', 60)->nullable()->unique();
			$table->string('remember_token', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
