// app/database/seeds/LapTableSeeder.php

<?php

class LapTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('laps')->delete();
		$lap = Lap::create(array(
			'startTime' => '2012-05-06 07:46:54' ,
			'totalTimeSeconds' => '1.34',
			'distanceMeters' => '0.015949',
			'maximumSpeed' => '0.000000',
			'calories' => '0',
			'averageHeartRateBpm' => '99',
			'maximumHeartRateBpm' => '99',
			'intensity' => 'Active',
			'triggerMethod' => 'Manual',
		));
		
		$activity = Activity::find(1);
		$activity->laps()->save($lap);
	}
}

