// app/database/seeds/UserTableSeeder.php

<?php

class UserTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('users')->delete();
		User::create(array(
			'name'     => 'TCX Parser Admin',
			'username' => 'tcxadmin',
			'email'    => 'foo@example.com',
			'password' => Hash::make('secretPass'),
			'usertype' => 'admin',
		));
	}

}

