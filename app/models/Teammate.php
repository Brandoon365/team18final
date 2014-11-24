<?php


class Teammate extends Eloquent {
    
    public $timestamps = false;

        // Will store a student's teammate preferences
	protected $table = 'teammates';

	protected $fillable = array('student', 'teammate', 'prefer', 'avoid');

}
