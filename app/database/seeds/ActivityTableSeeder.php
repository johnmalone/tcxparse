// app/database/seeds/ActivityTableSeeder.php

<?php

class ActivityTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('activitys')->delete();
		$activity = Activity::create(array(
			'activityId'     => '2012-0506 07:46:54',
			'sport' => 'Biking',
		));

		$user = User::find(1);
		$this->command->info($user);

		$user->activitys()->save($activity);
	}
}

