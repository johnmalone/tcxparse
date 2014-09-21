<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnsavedActvitysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('unsavedActivitys', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('user_id')->nullable();
			$table->integer('uploadParsing_id')->nullable();
			$table->longText('activityXML')->nullable();
			$table->integer('totalTrackPoints')->nullable();
			$table->integer('processedTrackPoints')->nullable();
			$table->softDeletes();
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
		Schema::drop('unsavedActivitys');
	}


}
