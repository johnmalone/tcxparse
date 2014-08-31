<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadParsingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('uploadParsing', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('completedActivitiesCount')->default(0);
			$table->enum('allActivitiesInDb', array('y','n'))->default('n');
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
		Schema::drop('uploadParsing');
	}


}

