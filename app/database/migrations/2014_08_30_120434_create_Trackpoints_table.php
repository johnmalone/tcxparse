<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackpointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trackpoints', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('lap_id');

			$table->dateTime('time');

			$table->string('latitudeDegrees', 9)->nullable();
			$table->string('longitudeDegrees', 9)->nullable();
			$table->string('altitudeMeters', 15)->nullable();
			$table->string('distanceMeters', 15)->nullable();
			$table->integer('heartRateBpm')->nullable()->unsigned();
			$table->integer('cadence')->nullable()->unsigned();
			$table->enum('sensorState', array('Present','Absent'))->nullable();
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
		Schema::drop('trackpoints');
	}
}
