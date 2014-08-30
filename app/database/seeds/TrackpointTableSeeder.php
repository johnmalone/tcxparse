// app/database/seeds/TrackpointTableSeeder.php

<?php

class TrackpointTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('trackpoints')->delete();
		$trackpoint = Trackpoint::create(array(
			'time' => '2012-05-06 07:46:55' ,
			'latitudeDegrees' => '54.278813',
			'longitudeDegrees' => '-8.464502',
			'altitudeMeters' => '12.863',
			'distanceMeters' => '0.016',
			'heartRateBpm' => '99',
		));

		
		$lap = Lap::find(1);
		$lap->trackpoints()->save($trackpoint);
	}

}

