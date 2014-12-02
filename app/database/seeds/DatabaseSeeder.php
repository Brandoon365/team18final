<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UsersTableSeeder');
		$this->call('ProjectsTableSeeder');
		
		//Comment out this line to remove random
		//teammate preferences
		$this->call('TeammatesTableSeeder');
	}

}
