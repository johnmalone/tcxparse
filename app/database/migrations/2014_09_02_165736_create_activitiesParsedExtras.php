<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesParsedExtras extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activitysParsedExtras', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('activity_id');

			$table->longtext('leafletJSLatLongArray')->nullable();
			$table->text('jsHRArray')->nullable();
			$table->text('jsAltArray')->nullable();
			$table->text('jsCadenceArray')->nullable();
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
		Schema::drop('activitysParsedExtras');
	}
}
	
