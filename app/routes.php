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

Route::get('/ViewTeams', function() {
	$teams = Teammate::all();

	$filledTeams = array();
	foreach ($teams as $team) {
		if ($filledTeams[$team->projectID] != true) {

			$users = User::join('users', 'team.member', '=', 'user.id')->get();

			$filledTeams[$team->projectID] = true;
			print("Project name: $team->projectID. Members:\n");
			foreach ($users as $user) {
				print("$user->last, $user-first\n");
			}
		}
	}

	return View::make('field.view')->with('teams', Project::all());
});

Route::get('/GenerateTeams', function() {
	$users = User::where('teamFirst', '=', '1')->get();
	$projects = Project::all();
	$remainingUsers = User::all();

	// Process by project preference first
	$projects->each(function($project) {
		$potentialTeammates = User::where('preference1', '=', $project->id)->get();
		
		$potentialTeammates->each(function($teammate) {
			$team = new Team;
			$team->projectID = $teammate->preference1;
			$team->member = $teammate->id;
			$team->save();
			//Remove user from remaining list
		});
	});

	$teams = Team::all();
	foreach ($remainingUsers as $rem) {
		foreach ($projects as $proj) {
			$max = ($proj->max > count($remainingUsers) ) ? $proj->max : count($remainingUsers);
			$num = count( Team::where('projectID', '=', $proj->id) );

			if ($num + 1 <= $max) {
				$team = new Team;
				$team->projectID = $proj->id;
				$team->member = $rem->id;
				$team->save();
				break;
			}
		}
	}
	return Redirect::intended('/ViewTeams')->with('teams', Project::all());
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
