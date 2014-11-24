<?php


class Teammate extends Eloquent {
    
    public $timestamps = false;

        // Will store a project id and the student's assigned to it.
	protected $table = 'teams';

	protected $fillable = array('projectID', 'member');

}
