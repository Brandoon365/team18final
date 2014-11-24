<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Run the users seeds.
	 *
	 * @return void
	 */
	public function run()
	{
            DB::table('users')->delete();
            //Load in the data from the CSV file
            $filename = app_path().'/database/csv/Students.csv';
            $contents = file($filename);
            $count = 1;
            foreach($contents as $line) {
                if($count > 1) {
                    $parts = explode(",", $line);
                    if(count($parts) == 4) {
                        DB::table('users')->insert(array('first'=>trim($parts[0]), 'last'=>trim($parts[1]), 'password'=>Hash::make(trim($parts[2])), 'email'=>trim($parts[3]), 'is_admin'=>false));
                    }
                }
                $count++;
            }

            //Insert the admin last 
            DB::table('users')->insert(array('first'=>"admin", 'last'=>"", 'password'=>Hash::make("1500"), 'email'=>"admin", 'is_admin'=>true));
	}


}