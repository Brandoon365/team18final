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
		return View::make('field.home');
	}
});

Route::get('/ViewTeams', function() {
	$teams = Teammate::all();

	$filledTeams = array();
	foreach ($teams as $team) {
		if ($filledTeams[$team->projectID] != true) {

			$users = User::join('users', 'team.member', '=', 'user.id')->get();

			$filledTeams[$team->projectID] = true;
			echo("Project name: $team->projectID. Members:\n");
			foreach ($users as $user) {
				echo("$user->last, $user-first\n");
			}
		}
	}
});

Route::get('/GenerateTeams', function() {
	$users = User::where('teamFirst', '=', '1')->get();
	$projects = Project::all()->get();
	$remainingUsers = User::all()->get();

	// Process by project preference first
	foreach ($projects as $project) {
		$potentialTeammates = User::where('preference1', '=', $project->id);
		$max = $project->max;
		// Note that this may not categorize all people w/ this pref
		// Catch-all code at end
		for ($i = 0; $i < $max; $i++) {
			$team = new Team;
			$team->projectID = $project->id;
			$team->member = $potentialTeammates[$i];
			$team->save();
		}
		$toRemove = $potentialTeammates[$i];
		unset($remainingUsers[$i]);
	}

	$teams = Team::all()->get();
	foreach ($remainingUsers as $rem) {
		foreach ($projects as $proj) {
			$max = $proj->max;
			$num = count( Teams::where('projectID', '=', $proj->id) );
			if ($num + 1 <= $max) {
				$team = new Team;
				$team->projectID = $proj->id;
				$team->member = $rem;
				$team->save();
				break;
			}
		}
	}
})


Route::post('/', function() {
	$email = Input::get('email');
	$password = Input::get('password');
	
	$credentials = array(
		'email' => $email,
		'password' => $password
	);
	if(Auth::attempt($credentials)) {
		return Redirect::intended('/'.Auth::user()->first.Auth::user()->last);
	}
	else {
		return Redirect::back()->withInput()->with('error', "Invalid Credentials");
	}
});

Route::get('/{user}', function() {
	if(Auth::check()) {
		return View::make('field.form');
	}
	else {
		return Redirect::intended('/');
	}
});

Route::post('/{user}', function() {
	
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
