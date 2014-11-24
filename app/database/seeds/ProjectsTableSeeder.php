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

	}

}