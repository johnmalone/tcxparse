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

			$table->integer('user_id');
			$table->integer('uploadParsing_id');
			$table->longText('activityXML');
			$table->integer('totalTrackPoints');
			$table->integer('processedTrackPoints');
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
