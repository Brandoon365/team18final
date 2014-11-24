<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
	
	public $timestamps = false;

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	
	protected $fillable = array('first', 'last', 'password', 'email', 'is_admin', 'preference1', 'preference2', 'preference3', 'preference4', 'teamFirst');
	
	/**
	 * Return a user's password
	 */
	public function getAuthPassword() {
		return $this->password;
	}
	
	/**
	 * Returns whether a user is an admin or not
	 */
	public function isAdmin() {
		return $this->is_admin;
	}

}
