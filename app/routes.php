<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * This will display the home page
 */
Route::get('/', function() {
	
	if(!Auth::check()) {
		return View::make('field.home');
	}
	else {
		if(Auth::user()->is_admin) {
			return Redirect::intended('/admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
});

Route::post('/', function() {
	$email = Input::get('email');
	$password = Input::get('password');
	
	$credentials = array(
		'email' => $email,
		'password' => $password
	);
	if(Auth::attempt($credentials)) {
		if(Auth::user()->is_admin) {
			return Redirect::intended('/admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
	else {
		return Redirect::back()->withInput()->with('error', "Invalid Credentials");
	}
});

Route::get('/student', function() {
	if(Auth::check()) {
		return View::make('field.form');
	}
	else {
		return Redirect::intended('/');
	}
});

Route::post('/student', function() {
	
	$user=User::whereEmail(Auth::user()->email);
	$userInfo = array(
		'preference1' => Input::get('proj_0'),
		'preference2' => Input::get('proj_1'),
		'preference3' => Input::get('proj_2'),
		'preference4' => Input::get('proj_3'),
		'teamFirst' => Input::get('teamFirst'),
	);

	$user->update($userInfo);
	$preferred = Input::get('pref');
	$avoid = Input::get('avoid');

	foreach($preferred as $p) {
		DB::table('teammates')->insert(array('student' => Auth::user()->id, 'teammate' => $p, 'prefer' => true, 'avoid' => false,));
	}
	
	foreach($avoid as $a) {
		DB::table('teammates')->insert(array('student' => Auth::user()->id, 'teammate' => $a, 'prefer' => false, 'avoid' => true,));
	}
	
	
	return Redirect::back()->with('message', 'Successfully updated preferences');
});

Route::get('/admin', function() {
	if(Auth::check()) {
		if(Auth::user()->is_admin) {
			return View::make('field.admin');
		}
		else {
			return Redirect::intended('/student');
		}
	}
	else {
		return Redirect::intended('/');
	}
});

Route::get('logout', function() {
	Auth::logout();
	return Redirect::to('/')->with('message', 'Successfully logged out');
});

Route::get('/viewTeams', function() {
	$teams=Teammate::all();
	var_dump($teams);
});
