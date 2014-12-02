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
			$pref1 = rand(1,23);
			$pref2 = rand(1,23);
			$pref3 = rand(1,23);
			$pref4 = rand(1,23);
			$team = rand(0,1);
                        DB::table('users')->insert(array('first'=>trim($parts[0]), 'last'=>trim($parts[1]), 'password'=>Hash::make(trim($parts[2])), 'email'=>trim($parts[3]), 'is_admin'=>false, 'teamFirst'=>$team,//));
							 //To remove random preferences remove the line below and
							 //uncomment the parentheses in the previous line
							 'preference1'=>$pref1, 'preference2'=>$pref2, 'preference3'=>$pref3, 'preference4'=>$pref4 ));
                    }
                }
                $count++;
            }

            //Insert the admin last 
            DB::table('users')->insert(array('first'=>"admin", 'last'=>"", 'password'=>Hash::make("1500"), 'email'=>"admin", 'is_admin'=>true));
	}


}