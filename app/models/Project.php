<?php


class Project extends Eloquent {
	
	public $timestamps = false;

	protected $table = 'projects';

	protected $fillable = array('client', 'project', 'min', 'max');

}
