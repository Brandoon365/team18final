<?php


class Team extends Eloquent {
    
    public $timestamps = false;

        // Will store a project id and the student's assigned to it.
	protected $table = 'teams';

	protected $fillable = array('projectID', 'member');
        
        public function getMembers($projectID) {
            $ids = DB::table('teams')->where('projectID', '=', $projectID);
            return $ids;
        }
        
        public function getSize($projectID) {
            $ids = DB::table('teams')->where('projectID', '=', $projectID);
            return count($ids);
        }

}
