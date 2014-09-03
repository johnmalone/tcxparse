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

			$table->longtext('jsonCoordArray')->nullable();
			$table->longtext('jsHRArray')->nullable();
			$table->longtext('jsAltArray')->nullable();
			$table->longtext('jsCadenceArray')->nullable();
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
	
