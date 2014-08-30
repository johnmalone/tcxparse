<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('laps', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('activity_id');

			$table->dateTime('startTime');

			$table->string('totalTimeSeconds', 15);
			$table->string('distanceMeters', 15);
			$table->string('maximumSpeed', 15);
			$table->string('calories', 15);
			$table->string('averageHeartRateBpm', 15);
			$table->string('maximumHeartRateBpm', 15);
			$table->string('intensity', 15);
			$table->string('triggerMethod', 15);

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
		Schema::drop('laps');
	}
}
