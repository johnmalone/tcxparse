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

			$table->integer('activity_id')->nullable();

			$table->dateTime('startTime');

			$table->string('totalTimeSeconds', 15)->nullable();
			$table->string('distanceMeters', 15)->nullable();
			$table->string('maximumSpeed', 15)->nullable();
			$table->string('calories', 15)->nullable();
			$table->string('averageHeartRateBpm', 15)->nullable();
			$table->string('maximumHeartRateBpm', 15)->nullable();
			$table->string('intensity', 15)->nullable();
			$table->string('triggerMethod', 15)->nullable();

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
