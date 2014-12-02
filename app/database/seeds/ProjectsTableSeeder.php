<?php

class ProjectsTableSeeder extends Seeder {

	/**
	 * Run the projects seeds.
	 *
	 * @return void
	 */
	public function run()
	{
            DB::table('projects')->delete();
            //Load in the data from the CSV file
            $filename = app_path().'/database/csv/Projects.csv';
            $contents = file($filename);
            $count = 1;
            foreach($contents as $line) {
                if($count > 1) {
                    $parts = explode(",", $line);
                    DB::table('projects')->insert(array('client'=>$parts[0], 'project'=>$parts[1], 'min'=>$parts[2], 'max'=>$parts[3]));
                }
                $count++;
            }
	    
	    //Go through and remove the projects with the highest minimum team size
	    //requirements until all teams can be filled at least to the minimum amount

	    //Get count of users, subtract one for admin
            $users = count(DB::table('users')->get()) - 1;
	    
	    $neededUsers = DB::table('projects')->sum('min');
	    
	    while($neededUsers >= $users) {
		$max = DB::table('projects')->orderBy('min')->first();
		$neededUsers -= $max->min;
		Project::destroy($max->id);
	    }
	}

}