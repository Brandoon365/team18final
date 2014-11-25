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
	$numTeams = count(Team::all());

	$teamIDS = array();
	
	for($i = 1; $i <= $numTeams; $i++) {
		$team = Team::find($i);
		$user = User::find($team->member);
		if (!array_key_exists($team->projectID, $teamIDS)) {
			$teamIDS[$team->projectID] = array();
			$teamIDS[$team->projectID][0]=$team->projectID;
		}
		array_push($teamIDS[$team->projectID], $user->first." ".$user->last);
	}
	return View::make('field.teams')->with('teamIDS', $teamIDS);
});

Route::get('/GenerateTeams', function() {
	Team::truncate();
	
	$users = User::where('teamFirst', '=', '1')->get();
	$projects = Project::all();
	$remainingUsers = User::where('is_admin', '=', 0)->get();
	

	foreach ($users as $index => $u) {
		$desiredProject = Project::find( $u->preference1 );
		$number = count( Team::where('projectID', '=', $desiredProject->id) );
		if ($number < $desiredProject->min) {
			$team = new Team;
			$team->projectID = $desiredProject->id;
			$team->member = $u->id;
			$team->save();
			unset($remainingUsers[$index]);
			continue;
		}

		$desiredProject = Project::find( $u->preference2 );
		$number = count( Team::where('projectID', '=', $desiredProject->id) );
		if ($number < $desiredProject->min) {
			$team = new Team;
			$team->projectID = $desiredProject->id;
			$team->member = $u->id;
			$team->save();
			unset($remainingUsers[$index]);
			continue;
		}

		$desiredProject = Project::find( $u->preference3 );
		$number = count( Team::where('projectID', '=', $desiredProject->id) );
		if ($number < $desiredProject->min) {
			$team = new Team;
			$team->projectID = $desiredProject->id;
			$team->member = $u->id;
			$team->save();
			unset($remainingUsers[$index]);
			continue;
		}

		$desiredProject = Project::find( $u->preference4 );
		$number = count( Team::where('projectID', '=', $desiredProject->id) );
		if ($number < $desiredProject->min) {
			$team = new Team;
			$team->projectID = $desiredProject->id;
			$team->member = $u->id;
			$team->save();
			unset($remainingUsers[$index]);
			continue;
		}

	}
	
	foreach ($projects as $proj) {
		$count = count(Team::where('projectID', '=', $proj->id)->get());
		while ($count < $proj->min && $remainingUsers->first() != null) {
			$team = new Team;
			$team->projectID = $proj->id;
			$team->member = $remainingUsers->first()->id;
			$team->save();
			$remainingUsers->shift();
			$count = count(Team::where('projectID', '=', $proj->id)->get());
		}
	}
	
	foreach ($remainingUsers as $rem) {
		foreach ($projects as $proj) {
			if(count(Team::where('projectID', '=', $proj->id))->get()) {
				$team = new Team;
				$team->projectID = $proj->id;
				$team->member = $remainingUsers->first()->id;
				$team->save();
				break;
			}
		}
	}
	
	return Redirect::intended('/ViewTeams');
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
